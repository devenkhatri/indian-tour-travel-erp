<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentCommission;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\TourPackage;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AgentPortalController extends Controller
{
    protected function getAgent(): Agent
    {
        $user = Auth::user();
        if (! $user->agent) {
            abort(403, 'No approved B2B Travel Agency profile found for this user.');
        }

        return $user->agent;
    }

    public function dashboard(): View
    {
        $agent = $this->getAgent();
        $totalBookings = Booking::where('agent_id', $agent->id)->count();
        $totalCommission = AgentCommission::where('agent_id', $agent->id)->sum('commission_amount');
        $availableCredit = max(0, (float) $agent->credit_limit - (float) $agent->used_credit);

        $recentBookings = Booking::where('agent_id', $agent->id)
            ->with(['package', 'customer'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentCommissions = AgentCommission::where('agent_id', $agent->id)
            ->with('booking.package')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('agent.dashboard', compact(
            'agent',
            'totalBookings',
            'totalCommission',
            'availableCredit',
            'recentBookings',
            'recentCommissions'
        ));
    }

    public function packages(): View
    {
        $agent = $this->getAgent();
        $packages = TourPackage::with(['destination', 'category'])
            ->where('is_active', true)
            ->where('status', 'published')
            ->paginate(9);

        return view('agent.packages.index', compact('agent', 'packages'));
    }

    public function bookings(): View
    {
        $agent = $this->getAgent();
        $bookings = Booking::where('agent_id', $agent->id)
            ->with(['package.destination', 'customer', 'passengers'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('agent.bookings.index', compact('agent', 'bookings'));
    }

    public function createBookingForm(int $packageId): View
    {
        $agent = $this->getAgent();
        $package = TourPackage::with(['destination', 'departures'])->findOrFail($packageId);
        $availableCredit = max(0, (float) $agent->credit_limit - (float) $agent->used_credit);

        return view('agent.bookings.create', compact('agent', 'package', 'availableCredit'));
    }

    public function submitBooking(Request $request, int $packageId): RedirectResponse
    {
        $agent = $this->getAgent();
        $package = TourPackage::findOrFail($packageId);

        $validated = $request->validate([
            'client_name' => 'required|string|max:150',
            'client_email' => 'required|email|max:150',
            'client_phone' => 'required|string|max:25',
            'departure_date' => 'required|date|after_or_equal:today',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'payment_choice' => 'required|string|in:wallet,credit,pay_later',
        ]);

        return DB::transaction(function () use ($agent, $package, $validated) {
            $adults = (int) $validated['adults'];
            $children = (int) ($validated['children'] ?? 0);

            $unitPrice = (float) ($package->discount_price ?: $package->adult_price);
            $sellingAmount = ($adults * $unitPrice) + ($children * (float) $package->child_price);
            $taxAmount = round($sellingAmount * 0.05, 2);
            $totalAmount = $sellingAmount + $taxAmount;

            // Commission calculation
            $commPercent = (float) $agent->commission_rate;
            $commAmount = round(($sellingAmount * $commPercent) / 100, 2);

            // Find or create customer
            $customer = Customer::firstOrCreate(
                ['email' => $validated['client_email']],
                [
                    'customer_code' => 'CUST-B2B-'.rand(1000, 9999),
                    'name' => $validated['client_name'],
                    'phone' => $validated['client_phone'],
                    'status' => 'active',
                ]
            );

            $bookingNumber = 'BK-B2B-'.strtoupper(Str::random(6));

            $paidAmount = 0.00;
            $paymentStatus = 'unpaid';

            if ($validated['payment_choice'] === 'wallet') {
                if ((float) $agent->wallet_balance < $totalAmount) {
                    return back()->with('error', 'Insufficient agent wallet balance.');
                }
                $agent->decrement('wallet_balance', $totalAmount);
                $paidAmount = $totalAmount;
                $paymentStatus = 'paid';
            } elseif ($validated['payment_choice'] === 'credit') {
                $availableCredit = max(0, (float) $agent->credit_limit - (float) $agent->used_credit);
                if ($availableCredit < $totalAmount) {
                    return back()->with('error', 'Booking exceeds available agency credit limit.');
                }
                $agent->increment('used_credit', $totalAmount);
                $paidAmount = $totalAmount;
                $paymentStatus = 'paid';
            }

            $booking = Booking::create([
                'booking_number' => $bookingNumber,
                'customer_id' => $customer->id,
                'user_id' => Auth::id(),
                'agent_id' => $agent->id,
                'service_type' => 'package',
                'package_id' => $package->id,
                'departure_date' => $validated['departure_date'],
                'return_date' => Carbon::parse($validated['departure_date'])->addDays($package->duration_days)->toDateString(),
                'adults' => $adults,
                'children' => $children,
                'selling_amount' => $sellingAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'balance_amount' => $totalAmount - $paidAmount,
                'supplier_cost' => round($sellingAmount * 0.70, 2),
                'expected_profit' => round($sellingAmount * 0.30, 2),
                'booking_status' => 'confirmed',
                'payment_status' => $paymentStatus,
                'notes' => "B2B Agent Booking via {$agent->agency_name}",
            ]);

            // Create agent commission record
            AgentCommission::create([
                'agent_id' => $agent->id,
                'booking_id' => $booking->id,
                'booking_amount' => $sellingAmount,
                'commission_percent' => $commPercent,
                'commission_amount' => $commAmount,
                'status' => 'credited',
            ]);

            // Add commission to agent wallet
            $agent->increment('wallet_balance', $commAmount);

            if ($paidAmount > 0) {
                Payment::create([
                    'payment_number' => 'PAY-B2B-'.strtoupper(Str::random(6)),
                    'booking_id' => $booking->id,
                    'customer_id' => $customer->id,
                    'agent_id' => $agent->id,
                    'amount' => $paidAmount,
                    'payment_method' => $validated['payment_choice'] === 'wallet' ? 'agent_wallet' : 'agent_credit',
                    'transaction_id' => 'TXN-B2B-'.strtoupper(Str::random(8)),
                    'payment_date' => now()->toDateString(),
                    'type' => 'full',
                    'status' => 'completed',
                    'notes' => "B2B Settlement via {$validated['payment_choice']}",
                ]);
            }

            return redirect()->route('agent.bookings')
                ->with('success', "Booking #{$bookingNumber} confirmed successfully! Commission of ₹".number_format($commAmount, 2).' has been credited to your agency wallet.');
        });
    }

    public function wallet(): View
    {
        $agent = $this->getAgent();
        $payments = Payment::where('agent_id', $agent->id)->orderBy('created_at', 'desc')->get();
        $commissions = AgentCommission::where('agent_id', $agent->id)->orderBy('created_at', 'desc')->get();

        return view('agent.wallet', compact('agent', 'payments', 'commissions'));
    }

    public function commissions(): View
    {
        $agent = $this->getAgent();
        $commissions = AgentCommission::where('agent_id', $agent->id)
            ->with(['booking.package', 'booking.customer'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('agent.commissions', compact('agent', 'commissions'));
    }
}
