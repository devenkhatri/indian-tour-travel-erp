@extends('layouts.admin')

@section('title', 'Payments Ledger & Cashflow | BharatTravel')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Payments & Collections Ledger</h1>
            <p class="text-xs text-slate-500 mt-1">Audit trail of customer UPI, NetBanking, credit card, and agency wallet settlements.</p>
        </div>
    </div>

    <!-- Financial Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Net Collections</span>
            <div class="text-2xl font-extrabold text-emerald-600 mt-1">₹{{ number_format($totalCollected, 2) }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Settled payments credited across all accounts</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Refunds Processed</span>
            <div class="text-2xl font-extrabold text-rose-600 mt-1">₹{{ number_format($totalRefunded, 2) }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Cancellations & trip credit chargebacks</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.accounts.payments') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                       placeholder="Search payment number, transaction UTR, guest...">
            </div>

            <div>
                <select name="method" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    <option value="">All Payment Modes</option>
                    <option value="upi" {{ request('method') == 'upi' ? 'selected' : '' }}>UPI / QR</option>
                    <option value="bank_transfer" {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>NEFT / RTGS</option>
                    <option value="credit_card" {{ request('method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="agent_wallet" {{ request('method') == 'agent_wallet' ? 'selected' : '' }}>Agent Wallet</option>
                    <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">
                    Filter
                </button>
                <a href="{{ route('admin.accounts.payments') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">Payment Ref</th>
                        <th class="py-3.5 px-6">Payment Date</th>
                        <th class="py-3.5 px-6">Customer / Agent</th>
                        <th class="py-3.5 px-6">Booking Number</th>
                        <th class="py-3.5 px-6">Mode & UTR</th>
                        <th class="py-3.5 px-6">Amount (INR)</th>
                        <th class="py-3.5 px-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $pay)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 px-6 font-bold text-slate-900">{{ $pay->payment_number }}</td>
                            <td class="py-4 px-6">{{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('M d, Y') : $pay->created_at->format('M d, Y') }}</td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-900 block">{{ $pay->customer?->name ?? ($pay->agent?->agency_name ?? 'Guest') }}</span>
                                <span class="text-[10px] text-slate-400">{{ $pay->customer?->phone }}</span>
                            </td>
                            <td class="py-4 px-6">
                                @if($pay->booking)
                                    <a href="{{ route('admin.bookings.show', $pay->booking_id) }}" class="text-amber-600 hover:text-amber-800 font-bold">
                                        #{{ $pay->booking->booking_number }}
                                    </a>
                                @else
                                    <span>N/A</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold uppercase text-[10px] text-slate-700 block">{{ str_replace('_', ' ', $pay->payment_method) }}</span>
                                <span class="text-[10px] font-mono text-slate-400">{{ $pay->transaction_id }}</span>
                            </td>
                            <td class="py-4 px-6 font-extrabold text-slate-900">
                                ₹{{ number_format($pay->amount, 2) }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                                    {{ $pay->status == 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ ucfirst($pay->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                No payments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

