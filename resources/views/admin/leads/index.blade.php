@extends('layouts.admin')

@section('title', 'Sales Leads & Enquiries Management')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Lead Pipeline & Enquiries</h1>
            <p class="text-xs text-slate-500 mt-1">Manage incoming website leads, custom tour requests, assigned sales staff, and conversion pipelines.</p>
        </div>
        <a href="{{ route('admin.leads.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Add New Lead
        </a>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.leads.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                       placeholder="Search name, phone, ref, dest...">
            </div>

            <div>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    <option value="">All Statuses</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New Enquiries</option>
                    <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="proposal_sent" {{ request('status') == 'proposal_sent' ? 'selected' : '' }}>Proposal Sent</option>
                    <option value="negotiation" {{ request('status') == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                    <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted / Won</option>
                    <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                </select>
            </div>

            <div>
                <select name="priority" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>

            <div>
                <select name="assigned_to" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    <option value="">All Staff</option>
                    @foreach($staffMembers as $stf)
                        <option value="{{ $stf->id }}" {{ request('assigned_to') == $stf->id ? 'selected' : '' }}>{{ $stf->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">
                    Filter
                </button>
                <a href="{{ route('admin.leads.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Leads Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">Lead ID</th>
                        <th class="py-3.5 px-6">Prospect Name</th>
                        <th class="py-3.5 px-6">Contact Info</th>
                        <th class="py-3.5 px-6">Destination / Category</th>
                        <th class="py-3.5 px-6">Pax & Budget</th>
                        <th class="py-3.5 px-6">Assigned Staff</th>
                        <th class="py-3.5 px-6">Priority</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($leads as $lead)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <a href="{{ route('admin.leads.show', $lead->id) }}" class="text-amber-600 hover:text-amber-700">
                                    {{ $lead->lead_number }}
                                </a>
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-900">
                                {{ $lead->name }}
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-slate-800 font-medium">{{ $lead->phone }}</p>
                                <p class="text-[11px] text-slate-400">{{ $lead->email ?? 'No email' }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-semibold text-slate-800 block">{{ $lead->destination }}</span>
                                <span class="text-[10px] uppercase font-bold text-slate-400">{{ $lead->category }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-slate-700">{{ $lead->adults }} Pax</p>
                                <p class="text-[11px] font-bold text-emerald-700">{{ $lead->budget ? '₹' . number_format($lead->budget) : 'Flexible' }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $lead->assignedEmployee?->name ?? 'Unassigned' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                    {{ $lead->priority == 'urgent' || $lead->priority == 'high' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ ucfirst($lead->priority) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                                    {{ $lead->status == 'converted' ? 'bg-emerald-100 text-emerald-800' : ($lead->status == 'new' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($lead->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.leads.show', $lead->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-lg transition">
                                    Dossier &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400">
                                No leads match your search criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leads->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $leads->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

