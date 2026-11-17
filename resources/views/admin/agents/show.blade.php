@extends('layouts.admin')

@section('title', 'B2B Partner Profile: ' . $agent->agency_name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.agents.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Agent Network</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-teal-600 uppercase">#{{ $agent->agent_code ?? $agent->agency_code }}</span>
                <span class="text-slate-300">•</span>
                <span class="text-xs text-slate-500">Contract Rate: {{ $agent->commission_rate }}%</span>
            </div>
            <h1 class="text-2xl font-bold font-serif text-slate-900">{{ $agent->agency_name }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Primary Contact: <span class="font-bold text-slate-800">{{ $agent->contact_person ?? $agent->owner_name }}</span>
                &bull; Phone: <span class="text-slate-800">{{ $agent->phone }}</span>
                &bull; City: <span class="text-slate-800">{{ $agent->city }}, {{ $agent->state }}</span>
            </p>
        </div>

        <div>
            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold 
                {{ $agent->status == 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                Status: {{ ucfirst($agent->status) }}
            </span>
        </div>
    </div>

    <!-- 4 Financial Stat Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Wallet Balance</span>
            <div class="text-xl font-extrabold text-emerald-600 mt-1">₹{{ number_format($agent->wallet_balance, 2) }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Available for instant debits</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Approved Credit Line</span>
            <div class="text-xl font-extrabold text-slate-900 mt-1">₹{{ number_format($agent->credit_limit, 2) }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Used: ₹{{ number_format($agent->used_credit, 2) }}</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Client Bookings</span>
            <div class="text-xl font-extrabold text-slate-900 mt-1">{{ $agent->bookings->count() }} Trips</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Generated via portal</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Commissions Paid</span>
            <div class="text-xl font-extrabold text-purple-700 mt-1">₹{{ number_format($agent->commissions->sum('commission_amount'), 2) }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">{{ $agent->commission_rate }}% Contract</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Col 1: Credit & Status Management Form -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs text-xs">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="shield" class="w-4 h-4 text-teal-600"></i> Partner Tier & Credit Controls
                </h3>

                <form action="{{ route('admin.agents.status.update', $agent->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Partnership Status *</label>
                        <select name="status" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold">
                            <option value="approved" {{ $agent->status == 'approved' ? 'selected' : '' }}>Approved / Active</option>
                            <option value="pending" {{ $agent->status == 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="suspended" {{ $agent->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="rejected" {{ $agent->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Approved Credit Limit (₹)</label>
                        <input type="number" step="0.01" name="credit_limit" value="{{ old('credit_limit', $agent->credit_limit) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Commission Rate (%)</label>
                        <input type="number" step="0.1" name="commission_rate" min="0" max="100" value="{{ old('commission_rate', $agent->commission_rate) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Internal Notes & Contracting Remarks</label>
                        <textarea name="notes" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">{{ old('notes', $agent->notes) }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">
                        Update Partner Agreement
                    </button>
                </form>
            </div>
        </div>

        <!-- Col 2 & 3: Bookings & Commission Statements -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Bookings List -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="ticket" class="w-4 h-4 text-teal-600"></i> Client Bookings via {{ $agent->agency_name }}
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-6">Booking Number</th>
                                <th class="py-3 px-6">Package</th>
                                <th class="py-3 px-6">Amount</th>
                                <th class="py-3 px-6">Payment</th>
                                <th class="py-3 px-6">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($agent->bookings as $bk)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-3.5 px-6 font-bold text-slate-900">
                                        <a href="{{ route('admin.bookings.show', $bk->id) }}" class="text-amber-600 hover:text-amber-700">
                                            {{ $bk->booking_number }}
                                        </a>
                                    </td>
                                    <td class="py-3.5 px-6 font-semibold text-slate-800">{{ $bk->package ? $bk->package->title : 'Custom Package' }}</td>
                                    <td class="py-3.5 px-6 font-bold text-slate-900">₹{{ number_format($bk->total_amount, 2) }}</td>
                                    <td class="py-3.5 px-6">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            {{ ucfirst($bk->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-100 text-teal-800">
                                            {{ ucfirst($bk->booking_status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400">
                                        No client bookings yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Commission Statements -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="percent" class="w-4 h-4 text-purple-600"></i> Commission Audit Trail
                    </h3>
                </div>
                <div class="divide-y divide-slate-100 p-4 space-y-2 text-xs">
                    @forelse($agent->commissions as $comm)
                        <div class="p-3 bg-slate-50 rounded-xl flex items-center justify-between">
                            <div>
                                <p class="font-bold text-slate-900">+ ₹{{ number_format($comm->commission_amount, 2) }} ({{ $comm->commission_percent }}%)</p>
                                <p class="text-[10px] text-slate-400">Booking Ref #{{ $comm->booking?->booking_number }} &bull; Base: ₹{{ number_format($comm->booking_amount, 2) }}</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                {{ ucfirst($comm->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-slate-400 py-3 text-center">No commission records.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

