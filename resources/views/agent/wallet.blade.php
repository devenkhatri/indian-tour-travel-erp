@extends('layouts.agent')

@section('title', 'Agency Wallet & Credit Ledger | BharatTravel')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Agency Wallet & Financial Ledger</h1>
            <p class="text-xs text-slate-500 mt-1">Review live balance, credit line utilization, automated commission credits, and debit history.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold">
                Wallet Status: Active
            </span>
        </div>
    </div>

    <!-- Financial Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 text-white rounded-2xl p-6 shadow-sm">
            <p class="text-emerald-100 text-xs uppercase font-bold tracking-wider">Current Wallet Balance</p>
            <p class="text-3xl font-extrabold mt-2">₹{{ number_format($agent->wallet_balance, 2) }}</p>
            <p class="text-emerald-200 text-[11px] mt-2">Automatically credited upon booking confirmations</p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
            <div class="flex justify-between items-center mb-1">
                <p class="text-slate-400 text-xs uppercase font-bold tracking-wider">Credit Line</p>
                <span class="text-xs font-bold text-slate-700">₹{{ number_format($agent->credit_limit, 2) }}</span>
            </div>
            <p class="text-2xl font-extrabold text-slate-900 mt-2">
                ₹{{ number_format(max(0, (float) $agent->credit_limit - (float) $agent->used_credit), 2) }}
            </p>
            <div class="mt-3 w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                @php
                    $pct = $agent->credit_limit > 0 ? min(100, round(($agent->used_credit / $agent->credit_limit) * 100)) : 0;
                @endphp
                <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $pct }}%"></div>
            </div>
            <p class="text-[11px] text-slate-400 mt-1.5">Used: ₹{{ number_format($agent->used_credit, 2) }} ({{ $pct }}%)</p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col justify-between">
            <div>
                <p class="text-slate-400 text-xs uppercase font-bold tracking-wider">Need Wallet Top-up?</p>
                <p class="text-xs text-slate-600 mt-1">Transfer funds via NEFT/RTGS to our corporate account and quote your Agent Code <span class="font-bold text-slate-800">#{{ $agent->agent_code }}</span> for instant credit.</p>
            </div>
            <div class="pt-2 text-[11px] text-teal-700 font-bold">
                Account Desk: accounts@bharattravel.com
            </div>
        </div>
    </div>

    <!-- Payments Ledger -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Wallet & Settlement Transactions</h3>
            <span class="text-xs text-slate-400 font-semibold">{{ $payments->count() }} Entries</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-6">Payment Ref</th>
                        <th class="py-3 px-6">Date</th>
                        <th class="py-3 px-6">Booking ID</th>
                        <th class="py-3 px-6">Method</th>
                        <th class="py-3 px-6">Amount</th>
                        <th class="py-3 px-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $pay)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3.5 px-6 font-bold text-slate-900">{{ $pay->payment_number }}</td>
                            <td class="py-3.5 px-6">{{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('M d, Y') : $pay->created_at->format('M d, Y') }}</td>
                            <td class="py-3.5 px-6 font-semibold text-slate-800">#{{ $pay->booking?->booking_number ?? $pay->booking_id }}</td>
                            <td class="py-3.5 px-6 uppercase text-[10px] font-bold text-slate-500">{{ str_replace('_', ' ', $pay->payment_method) }}</td>
                            <td class="py-3.5 px-6 font-bold text-slate-900">₹{{ number_format($pay->amount, 2) }}</td>
                            <td class="py-3.5 px-6">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    {{ ucfirst($pay->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                No wallet settlements recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

