@extends('layouts.agent')

@section('title', 'B2B Agency Dashboard | ' . $agent->agency_name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800 uppercase tracking-wider">
                    Agency Code: {{ $agent->agent_code }}
                </span>
                <span class="text-slate-300">&bull;</span>
                <span class="text-xs text-slate-500">{{ $agent->city }}, {{ $agent->state }}</span>
            </div>
            <h1 class="text-2xl font-bold font-serif text-slate-900">{{ $agent->agency_name }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">Primary Contact: <span class="font-bold text-slate-700">{{ $agent->contact_person }}</span> ({{ $agent->phone }})</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('agent.packages') }}" class="px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-xl transition shadow-xs flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Book For Client
            </a>
        </div>
    </div>

    <!-- Financial KPI Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Wallet Balance</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-emerald-600">₹{{ number_format($agent->wallet_balance, 2) }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Available for Instant Debits</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Available Credit</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-slate-900">₹{{ number_format($availableCredit, 2) }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Limit: ₹{{ number_format($agent->credit_limit, 2) }}</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Bookings</span>
                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                    <i data-lucide="ticket" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-slate-900">{{ $totalBookings }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Confirmed Pax Trips</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Earned Commission</span>
                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                    <i data-lucide="percent" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-purple-700">₹{{ number_format($totalCommission, 2) }}</div>
            <p class="text-[11px] text-purple-600 font-semibold mt-1">Rate: {{ $agent->commission_rate }}% Flat</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Client Bookings -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900">Recent Client Bookings</h3>
                <a href="{{ route('agent.bookings') }}" class="text-xs font-bold text-teal-600 hover:text-teal-700">All Bookings &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-6">Booking Ref</th>
                            <th class="py-3 px-6">Client Name</th>
                            <th class="py-3 px-6">Package</th>
                            <th class="py-3 px-6">Total Amount</th>
                            <th class="py-3 px-6">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentBookings as $bk)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-6 font-bold text-slate-900">{{ $bk->booking_number }}</td>
                                <td class="py-3 px-6 font-semibold text-slate-800">{{ $bk->customer ? $bk->customer->name : 'Walk-in' }}</td>
                                <td class="py-3 px-6">{{ $bk->package ? $bk->package->title : 'Custom' }}</td>
                                <td class="py-3 px-6 font-bold text-slate-900">₹{{ number_format($bk->total_amount, 2) }}</td>
                                <td class="py-3 px-6">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-100 text-teal-800">
                                        {{ ucfirst($bk->booking_status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">
                                    No client bookings created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Commission Earnings -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900">Commission Log</h3>
                <a href="{{ route('agent.commissions') }}" class="text-xs font-bold text-teal-600 hover:text-teal-700">Statement &rarr;</a>
            </div>
            <div class="divide-y divide-slate-100 p-4 space-y-3">
                @forelse($recentCommissions as $comm)
                    <div class="p-3 bg-slate-50 rounded-xl flex items-center justify-between text-xs">
                        <div>
                            <p class="font-bold text-slate-900">+ ₹{{ number_format($comm->commission_amount, 2) }}</p>
                            <p class="text-[10px] text-slate-400">{{ $comm->booking?->booking_number }} &bull; {{ $comm->commission_percent }}%</p>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                            {{ ucfirst($comm->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-slate-400 text-xs py-4 text-center">No commission records yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

