@extends('layouts.admin')

@section('title', 'Tax Invoice: ' . $invoice->invoice_number)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.accounts.invoices') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Invoices</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-teal-600 uppercase">#{{ $invoice->invoice_number }}</span>
                <span class="text-slate-300">•</span>
                <span class="text-xs text-slate-500">Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</span>
            </div>
            <h1 class="text-2xl font-bold font-serif text-slate-900">Tax Invoice & Billing Record</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Billed To: <a href="{{ route('admin.customers.show', $invoice->customer_id) }}" class="font-bold text-slate-900 hover:text-amber-600">{{ $invoice->customer?->name }}</a>
                &bull; Financial Year: <span class="font-bold text-slate-800">{{ $invoice->financial_year }}</span>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.accounts.invoice.pdf', $invoice->id) }}" class="px-4 py-2 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                <i data-lucide="download" class="w-3.5 h-3.5"></i> Download Tax Invoice (PDF)
            </a>
        </div>
    </div>

    <!-- Invoice Details Card -->
    <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-xs space-y-6">
        <!-- Billing Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-slate-100 text-xs">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Billed To (Client)</p>
                <h3 class="font-bold text-slate-900 text-base mt-1">{{ $invoice->customer?->name }}</h3>
                <p class="text-slate-600 mt-0.5">{{ $invoice->customer?->phone }} &bull; {{ $invoice->customer?->email }}</p>
                <p class="text-slate-500 mt-0.5">{{ $invoice->customer?->address }}</p>
                <p class="text-slate-500">{{ $invoice->customer?->city }}, {{ $invoice->customer?->state }} {{ $invoice->customer?->pincode }}</p>
                @if($invoice->customer?->gstin)
                    <p class="text-teal-700 font-bold mt-1">Client GSTIN: {{ $invoice->customer->gstin }}</p>
                @endif
            </div>

            <div class="sm:text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Issued By (Tour Operator)</p>
                <h3 class="font-bold text-slate-900 text-base mt-1">BharatTravel Holidays Private Limited</h3>
                <p class="text-slate-600 mt-0.5">Connaught Place, New Delhi - 110001</p>
                <p class="text-slate-700 font-bold mt-1">GSTIN: 07AAACB1234F1Z9 &bull; State: Delhi (07)</p>
                <p class="text-teal-700 font-bold mt-0.5">HSN / SAC Code: 9985 (Tour Operator Services)</p>
            </div>
        </div>

        <!-- Line Items -->
        <div>
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Service Breakdown</h3>
            <table class="w-full text-left text-xs text-slate-600 border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Description of Service</th>
                        <th class="py-3 px-4">SAC Code</th>
                        <th class="py-3 px-4 text-center">Qty</th>
                        <th class="py-3 px-4 text-right">Taxable Amount (INR)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr>
                        <td class="py-4 px-4 font-bold text-slate-900">
                            {{ $invoice->booking?->package ? $invoice->booking->package->title : 'Tour Package Reservation' }}
                            <span class="block text-[11px] font-normal text-slate-400">Booking Ref #{{ $invoice->booking?->booking_number }}</span>
                        </td>
                        <td class="py-4 px-4 font-mono font-semibold text-slate-700">9985</td>
                        <td class="py-4 px-4 text-center font-semibold">1</td>
                        <td class="py-4 px-4 text-right font-bold text-slate-900">₹{{ number_format($invoice->subtotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tax & Totals Summary -->
        <div class="flex justify-end pt-4 border-t border-slate-100">
            <div class="w-full sm:w-80 space-y-2 text-xs">
                <div class="flex justify-between text-slate-600">
                    <span>Taxable Subtotal:</span>
                    <span class="font-bold text-slate-800">₹{{ number_format($invoice->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>CGST (2.5%):</span>
                    <span class="font-bold text-slate-800">₹{{ number_format($invoice->cgst_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>SGST (2.5%):</span>
                    <span class="font-bold text-slate-800">₹{{ number_format($invoice->sgst_amount, 2) }}</span>
                </div>
                <div class="pt-2 border-t border-slate-200 flex justify-between font-extrabold text-sm text-slate-900">
                    <span>Grand Total:</span>
                    <span>₹{{ number_format($invoice->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-emerald-700 font-bold">
                    <span>Total Amount Paid:</span>
                    <span>₹{{ number_format($invoice->paid_amount, 2) }}</span>
                </div>
                <div class="pt-1.5 border-t border-slate-200 flex justify-between font-extrabold text-sm {{ $invoice->balance_amount > 0 ? 'text-amber-600' : 'text-slate-900' }}">
                    <span>Remaining Balance:</span>
                    <span>₹{{ number_format($invoice->balance_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

