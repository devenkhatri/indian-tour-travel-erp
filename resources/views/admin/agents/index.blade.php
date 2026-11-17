@extends('layouts.admin')

@section('title', 'B2B Travel Agent Network | BharatTravel')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">B2B Travel Agent Network</h1>
            <p class="text-xs text-slate-500 mt-1">Manage approved travel partners, wholesale commission contracts, wallet limits, and credit facilities.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.agents.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                       placeholder="Search agency name, code, contact person, city...">
            </div>

            <div>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    <option value="">All Agency Statuses</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved / Active</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">
                    Filter
                </button>
                <a href="{{ route('admin.agents.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Agents Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">Agency Code</th>
                        <th class="py-3.5 px-6">Agency Name</th>
                        <th class="py-3.5 px-6">Contact Details</th>
                        <th class="py-3.5 px-6">City & State</th>
                        <th class="py-3.5 px-6">Wallet Balance</th>
                        <th class="py-3.5 px-6">Credit Limit</th>
                        <th class="py-3.5 px-6">Commission</th>
                        <th class="py-3.5 px-6 text-center">Bookings</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($agents as $agent)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <a href="{{ route('admin.agents.show', $agent->id) }}" class="text-amber-600 hover:text-amber-700">
                                    {{ $agent->agent_code ?? $agent->agency_code }}
                                </a>
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-900">
                                {{ $agent->agency_name }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-medium text-slate-800 block">{{ $agent->contact_person ?? $agent->owner_name }}</span>
                                <span class="text-[11px] text-slate-400">{{ $agent->phone }}</span>
                            </td>
                            <td class="py-4 px-6">
                                {{ $agent->city ?? 'N/A' }}{{ $agent->state ? ', ' . $agent->state : '' }}
                            </td>
                            <td class="py-4 px-6 font-bold text-emerald-600">
                                ₹{{ number_format($agent->wallet_balance, 2) }}
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-900">
                                ₹{{ number_format($agent->credit_limit, 2) }}
                            </td>
                            <td class="py-4 px-6 font-extrabold text-teal-700">
                                {{ $agent->commission_rate }}%
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-800">
                                    {{ $agent->bookings_count }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                                    {{ $agent->status == 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ ucfirst($agent->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.agents.show', $agent->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-lg transition inline-block">
                                    Profile &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-12 text-center text-slate-400">
                                No B2B travel partners found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($agents->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $agents->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

