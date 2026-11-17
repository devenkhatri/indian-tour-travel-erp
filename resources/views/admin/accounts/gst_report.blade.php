@extends('layouts.admin')

@section('title', 'Indian GST & TCS Compliance Report | BharatTravel')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Indian GST & TCS Compliance Statement</h1>
            <p class="text-xs text-slate-500 mt-1">Tour operator taxation under SAC 9985 (5% GST), Inter-state IGST, and Tax Collected at Source (TCS) on Overseas Tours.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl bg-teal-50 border border-teal-200 text-teal-800 text-xs font-bold">
                FY: {{ $year }}
            </span>
        </div>
    </div>

    <!-- GST Tax Aggregate Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Taxable Value</span>
            <div class="text-xl font-extrabold text-slate-900 mt-1">₹{{ number_format($taxableTotal, 2) }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">SAC 9985 Base</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">CGST (2.5%)</span>
            <div class="text-xl font-extrabold text-blue-700 mt-1">₹{{ number_format($cgstTotal, 2) }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Central Tax Portion</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">SGST (2.5%)</span>
            <div class="text-xl font-extrabold text-indigo-700 mt-1">₹{{ number_format($sgstTotal, 2) }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">State Tax Portion</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">IGST (5%)</span>
            <div class="text-xl font-extrabold text-purple-700 mt-1">₹{{ number_format($igstTotal, 2) }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Inter-State Supply</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total GST Liability</span>
            <div class="text-xl font-extrabold text-emerald-700 mt-1">₹{{ number_format($cgstTotal + $sgstTotal + $igstTotal, 2) }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Gross Tax Payable</p>
        </div>
    </div>

    <!-- GSTR-1 Invoices Detailed Ledger -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">GSTR-1 Monthly Sales Return Invoices</h3>
            <span class="text-xs font-bold text-slate-400">{{ $invoices->count() }} Invoices</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-6">Invoice Number</th>
                        <th class="py-3 px-6">Date</th>
                        <th class="py-3 px-6">Client / Recipient</th>
                        <th class="py-3 px-6">Place of Supply</th>
                        <th class="py-3 px-6">Taxable (INR)</th>
                        <th class="py-3 px-6">CGST (2.5%)</th>
                        <th class="py-3 px-6">SGST (2.5%)</th>
                        <th class="py-3 px-6">Total Tax</th>
                        <th class="py-3 px-6">Invoice Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3.5 px-6 font-bold text-slate-900">
                                <a href="{{ route('admin.accounts.invoice.show', $inv->id) }}" class="text-amber-600 hover:text-amber-700">
                                    {{ $inv->invoice_number }}
                                </a>
                            </td>
                            <td class="py-3.5 px-6">{{ \Carbon\Carbon::parse($inv->invoice_date)->format('M d, Y') }}</td>
                            <td class="py-3.5 px-6 font-semibold text-slate-800">{{ $inv->customer?->name }}</td>
                            <td class="py-3.5 px-6">{{ $inv->customer?->state ?? 'Delhi (07)' }}</td>
                            <td class="py-3.5 px-6 font-semibold text-slate-800">₹{{ number_format($inv->subtotal, 2) }}</td>
                            <td class="py-3.5 px-6">₹{{ number_format($inv->cgst_amount, 2) }}</td>
                            <td class="py-3.5 px-6">₹{{ number_format($inv->sgst_amount, 2) }}</td>
                            <td class="py-3.5 px-6 font-bold text-slate-900">₹{{ number_format($inv->tax_amount, 2) }}</td>
                            <td class="py-3.5 px-6 font-extrabold text-slate-900">₹{{ number_format($inv->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400">
                                No tax invoices issued in this financial year.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

