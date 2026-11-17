<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingVoucher;
use App\Models\Customer;
use App\Models\CustomerPassenger;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\PassportApplication;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\VisaApplication;
use App\Models\Wishlist;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CustomerDashboardController extends Controller
{
    protected function getCustomer(): ?Customer
    {
        $user = Auth::user();
        if (! $user) {
            return null;
        }

        if (! $user->customer) {
            return Customer::create([
                'user_id' => $user->id,
                'customer_code' => 'CUST-'.date('Y').'-'.str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '0000000000',
                'status' => 'active',
            ]);
        }

        return $user->customer;
    }

    public function dashboard(): View
    {
        $customer = $this->getCustomer();
        $user = Auth::user();

        $bookings = Booking::where('customer_id', $customer->id)
            ->with(['package.destination'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $upcomingTrip = Booking::where('customer_id', $customer->id)
            ->where('booking_status', 'confirmed')
            ->where('departure_date', '>=', now()->toDateString())
            ->orderBy('departure_date', 'asc')
            ->first();

        $totalBookings = Booking::where('customer_id', $customer->id)->count();
        $pendingPayments = Booking::where('customer_id', $customer->id)->where('balance_amount', '>', 0)->sum('balance_amount');

        $visaApplications = VisaApplication::where('customer_id', $customer->id)->orWhere('contact_email', $user->email)->count();
        $passportApplications = PassportApplication::where('customer_id', $customer->id)->orWhere('contact_email', $user->email)->count();

        $recentInquiries = Lead::where('customer_id', $customer->id)
            ->orWhere('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('customer.dashboard', compact(
            'customer',
            'bookings',
            'upcomingTrip',
            'totalBookings',
            'pendingPayments',
            'visaApplications',
            'passportApplications',
            'recentInquiries'
        ));
    }

    public function bookings(): View
    {
        $customer = $this->getCustomer();
        $bookings = Booking::where('customer_id', $customer->id)
            ->with(['package', 'passengers', 'payments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.bookings.index', compact('bookings'));
    }

    public function bookingDetails(int $id): View
    {
        $customer = $this->getCustomer();
        $booking = Booking::where('customer_id', $customer->id)
            ->where('id', $id)
            ->with(['package.destination', 'package.itineraries', 'passengers', 'payments', 'vouchers', 'invoices'])
            ->firstOrFail();

        return view('customer.bookings.show', compact('booking'));
    }

    public function downloadVoucher(int $id)
    {
        $customer = $this->getCustomer();
        $voucher = BookingVoucher::where('id', $id)
            ->whereHas('booking', function ($q) use ($customer) {
                $q->where('customer_id', $customer->id);
            })
            ->with(['booking.customer', 'booking.package.destination', 'booking.passengers'])
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.voucher', compact('voucher'));

        return $pdf->download("Voucher-{$voucher->voucher_number}.pdf");
    }

    public function downloadInvoice(int $id)
    {
        $customer = $this->getCustomer();
        $invoice = Invoice::where('id', $id)
            ->where('customer_id', $customer->id)
            ->with(['customer', 'booking.package', 'payments', 'tax'])
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));

        return $pdf->download("Tax-Invoice-{$invoice->invoice_number}.pdf");
    }

    public function inquiries(): View
    {
        $customer = $this->getCustomer();
        $user = Auth::user();

        $inquiries = Lead::where('customer_id', $customer->id)
            ->orWhere('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.inquiries.index', compact('inquiries'));
    }

    public function visaPassport(): View
    {
        $customer = $this->getCustomer();
        $user = Auth::user();

        $visas = VisaApplication::where('customer_id', $customer->id)
            ->orWhere('contact_email', $user->email)
            ->with('country')
            ->orderBy('created_at', 'desc')
            ->get();

        $passports = PassportApplication::where('customer_id', $customer->id)
            ->orWhere('contact_email', $user->email)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.visa_passport.index', compact('visas', 'passports'));
    }

    public function passengers(): View
    {
        $customer = $this->getCustomer();
        $passengers = CustomerPassenger::where('customer_id', $customer->id)->get();

        return view('customer.passengers.index', compact('passengers', 'customer'));
    }

    public function storePassenger(Request $request): RedirectResponse
    {
        $customer = $this->getCustomer();

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|string|in:Male,Female,Other',
            'dob' => 'nullable|date',
            'relationship' => 'required|string|max:50',
            'passport_number' => 'nullable|string|max:50',
            'passport_expiry' => 'nullable|date',
            'passport_country' => 'nullable|string|max:100',
        ]);

        CustomerPassenger::create(array_merge($validated, ['customer_id' => $customer->id]));

        return back()->with('success', 'Passenger profile added successfully.');
    }

    public function profile(): View
    {
        $customer = $this->getCustomer();
        $user = Auth::user();

        return view('customer.profile', compact('customer', 'user'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $customer = $this->getCustomer();

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:25',
            'alternate_phone' => 'nullable|string|max:25',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'passport_number' => 'nullable|string|max:50',
            'passport_expiry' => 'nullable|date',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'address' => $validated['address'] ?? null,
            'pincode' => $validated['pincode'] ?? null,
        ]);

        $customer->update($validated);

        return back()->with('success', 'Your traveller profile details have been updated.');
    }

    public function wishlist(): View
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with(['package.destination'])
            ->get();

        return view('customer.wishlist', compact('wishlists'));
    }

    public function support(): View
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
            ->withCount('messages')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.support.index', compact('tickets'));
    }

    public function storeTicket(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:200',
            'category' => 'required|string|max:50',
            'priority' => 'required|string|in:low,medium,high',
            'message' => 'required|string',
        ]);

        $ticket = SupportTicket::create([
            'ticket_number' => 'TCK-'.date('Y').'-'.strtoupper(Str::random(6)),
            'user_id' => Auth::id(),
            'subject' => $validated['subject'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'status' => 'open',
        ]);

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        return back()->with('success', "Support Ticket #{$ticket->ticket_number} created successfully. An agent will respond shortly.");
    }

    public function ticketDetails(int $id): View
    {
        $ticket = SupportTicket::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['messages.user'])
            ->firstOrFail();

        return view('customer.support.show', compact('ticket'));
    }

    public function replyTicket(Request $request, int $id): RedirectResponse
    {
        $ticket = SupportTicket::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        $ticket->update(['status' => 'in_progress']);

        return back()->with('success', 'Your reply has been posted.');
    }
}
