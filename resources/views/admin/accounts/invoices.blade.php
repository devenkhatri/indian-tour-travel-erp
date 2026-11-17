@extends('layouts.admin')

@section('title', 'Tax Invoices Master | BharatTravel')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">GST Tax Invoices Master</h1>
            <p class="text-xs text-slate-500 mt-1">Official GST SAC 9985 tour invoices, CGST/SGST/IGST breakdown, and TCS compliance records.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.accounts.gst') }}" class="px-4 py-2 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-2">
                <i data-lucide="bar-chart-2" class="w-4 h-4"></i> GST Compliance Report
            </a>
        </div>
    </div>

    <!-- Financial Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Invoiced (Gross)</span>
            <div class="text-2xl font-extrabold text-slate-900 mt-1">₹{{ number_format($totalBilled, 2) }}</div>
            <p class="text-[11px] text-slate-400 mt-1">All active issued invoices</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Paid Collections</span>
            <div class="text-2xl font-extrabold text-emerald-600 mt-1">₹{{ number_format($totalPaid, 2) }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Total revenue collected</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Outstanding Receivables</span>
            <div class="text-2xl font-extrabold text-rose-600 mt-1">₹{{ number_format($totalBalance, 2) }}</div>
            <p class="text-[11px] text-rose-500 font-medium mt-1">Pending payments</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.accounts.invoices') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                       placeholder="Search invoice number, customer name...">
            </div>

            <div>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    <option value="">All Invoice Statuses</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                    <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>Unpaid / Issued</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">
                    Filter
                </button>
                <a href="{{ route('admin.accounts.invoices') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">Invoice Number</th>
                        <th class="py-3.5 px-6">Date</th>
                        <th class="py-3.5 px-6">Billed To (Guest)</th>
                        <th class="py-3.5 px-6">Taxable (INR)</th>
                        <th class="py-3.5 px-6">GST SAC 9985</th>
                        <th class="py-3.5 px-6">Total Amount</th>
                        <th class="py-3.5 px-6">Balance Due</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <a href="{{ route('admin.accounts.invoice.show', $inv->id) }}" class="text-amber-600 hover:text-amber-700">
                                    {{ $inv->invoice_number }}
                                </a>
                            </td>
                            <td class="py-4 px-6">{{ \Carbon\Carbon::parse($inv->invoice_date)->format('M d, Y') }}</td>
                            <td class="py-4 px-6 font-semibold text-slate-900">
                                {{ $inv->customer?->name }}
                            </td>
                            <td class="py-4 px-6">₹{{ number_format($inv->subtotal, 2) }}</td>
                            <td class="py-4 px-6">₹{{ number_format($inv->tax_amount, 2) }}</td>
                            <td class="py-4 px-6 font-bold text-slate-900">₹{{ number_format($inv->total_amount, 2) }}</td>
                            <td class="py-4 px-6 font-semibold {{ $inv->balance_amount > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                                ₹{{ number_format($inv->balance_amount, 2) }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                                    {{ $inv->status == 'paid' ? 'bg-emerald-100 text-emerald-800' : ($inv->status == 'partially_paid' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $inv->status)) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-1">
                                <a href="{{ route('admin.accounts.invoice.pdf', $inv->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-lg transition inline-flex items-center gap-1">
                                    <i data-lucide="download" class="w-3 h-3"></i> PDF
                                </a>
                                <a href="{{ route('admin.accounts.invoice.show', $inv->id) }}" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold rounded-lg transition inline-block">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400">
                                No invoices found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

