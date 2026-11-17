<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingVoucher;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\TaxRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::with(['customer', 'package.destination', 'agent']);

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        if ($request->filled('status')) {
            $query->where('booking_status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(int $id): View
    {
        $booking = Booking::with([
            'customer.passengers',
            'package.itineraries',
            'package.destination',
            'passengers',
            'vouchers',
            'payments.creator',
            'invoices.tax',
            'agent',
            'assignedEmployee',
        ])->findOrFail($id);

        $taxes = TaxRate::where('is_active', true)->get();

        return view('admin.bookings.show', compact('booking', 'taxes'));
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'booking_status' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $booking->update([
            'booking_status' => $validated['booking_status'],
            'notes' => $booking->notes.($validated['remarks'] ? "\n[".now()->toDateTimeString().'] Status changed: '.$validated['remarks'] : ''),
        ]);

        return back()->with('success', "Booking status updated to {$validated['booking_status']}.");
    }

    public function collectPayment(Request $request, int $id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:'.max(1, (float) $booking->balance_amount),
            'payment_method' => 'required|string|in:cash,upi,bank_transfer,credit_card,debit_card,cheque',
            'transaction_id' => 'nullable|string|max:100',
            'payment_date' => 'required|date',
            'type' => 'required|string|in:advance,partial,full',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($booking, $validated) {
            $amount = (float) $validated['amount'];

            $payment = Payment::create([
                'payment_number' => 'PAY-'.date('Y').'-'.strtoupper(Str::random(6)),
                'booking_id' => $booking->id,
                'customer_id' => $booking->customer_id,
                'amount' => $amount,
                'payment_method' => $validated['payment_method'],
                'transaction_id' => $validated['transaction_id'] ?? ('MAN-'.strtoupper(Str::random(8))),
                'payment_date' => $validated['payment_date'],
                'type' => $validated['type'],
                'status' => 'completed',
                'notes' => $validated['notes'] ?? 'Manual payment recorded by staff',
                'created_by' => Auth::id(),
            ]);

            $newPaid = (float) $booking->paid_amount + $amount;
            $newBalance = max(0, (float) $booking->total_amount - $newPaid);
            $paymentStatus = $newBalance <= 0 ? 'paid' : 'partially_paid';

            $booking->update([
                'paid_amount' => $newPaid,
                'balance_amount' => $newBalance,
                'payment_status' => $paymentStatus,
            ]);

            return back()->with('success', 'Payment of ₹'.number_format($amount, 2)." recorded successfully. Receipt #{$payment->payment_number} generated.");
        });
    }

    public function generateVoucher(Request $request, int $id): RedirectResponse
    {
        $booking = Booking::with(['package.destination', 'customer', 'passengers'])->findOrFail($id);

        $voucherNumber = 'VCH-'.date('Y').'-'.strtoupper(Str::random(6));

        BookingVoucher::create([
            'booking_id' => $booking->id,
            'voucher_number' => $voucherNumber,
            'voucher_type' => $request->get('voucher_type', 'customer'),
            'details' => [
                'package' => $booking->package ? $booking->package->title : 'Travel Service Booking',
                'destination' => $booking->package && $booking->package->destination ? $booking->package->destination->name : 'Tour',
                'departure_date' => $booking->departure_date ? $booking->departure_date->format('d M Y') : 'TBD',
                'return_date' => $booking->return_date ? $booking->return_date->format('d M Y') : 'TBD',
                'pax_count' => "{$booking->adults} Adults, {$booking->children} Children",
                'notes' => $request->get('special_notes', 'Please present this voucher along with government photo IDs during hotel check-in.'),
            ],
            'generated_at' => now(),
        ]);

        return back()->with('success', "Travel Voucher #{$voucherNumber} created.");
    }

    public function issueInvoice(Request $request, int $id): RedirectResponse
    {
        $booking = Booking::with(['package', 'customer'])->findOrFail($id);

        $invoiceNumber = 'INV-'.date('Y').'-'.strtoupper(Str::random(6));

        $subtotal = (float) $booking->selling_amount - (float) $booking->discount_amount;
        $taxAmount = (float) $booking->tax_amount;
        $totalAmount = (float) $booking->total_amount;
        $paidAmount = (float) $booking->paid_amount;
        $balanceAmount = (float) $booking->balance_amount;

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'booking_id' => $booking->id,
            'customer_id' => $booking->customer_id,
            'financial_year' => '2026-2027',
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addDays(7)->toDateString(),
            'subtotal' => $subtotal,
            'tax_id' => 1,
            'tax_rate' => 5.00,
            'cgst_amount' => round($subtotal * 0.025, 2),
            'sgst_amount' => round($subtotal * 0.025, 2),
            'igst_amount' => 0.00,
            'discount_amount' => $booking->discount_amount,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'balance_amount' => $balanceAmount,
            'status' => $balanceAmount <= 0 ? 'paid' : ($paidAmount > 0 ? 'partially_paid' : 'issued'),
            'items' => [
                [
                    'description' => $booking->package ? $booking->package->title : 'Travel Reservation Booking #'.$booking->booking_number,
                    'qty' => 1,
                    'rate' => $booking->selling_amount,
                    'amount' => $booking->selling_amount,
                ],
            ],
            'notes' => 'Generated automatically by BharatTravel ERP Billing System',
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', "Tax Invoice #{$invoiceNumber} generated successfully.");
    }

    public function cancelBooking(Request $request, int $id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'cancellation_reason' => 'required|string',
        ]);

        $booking->update([
            'booking_status' => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason'],
        ]);

        return back()->with('success', "Booking #{$booking->booking_number} cancelled.");
    }
}
