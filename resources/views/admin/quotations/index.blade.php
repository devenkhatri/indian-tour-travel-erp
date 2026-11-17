@extends('layouts.admin')

@section('title', 'Holiday Quotations & Proposals | BharatTravel')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Holiday Quotations & Proposals</h1>
            <p class="text-xs text-slate-500 mt-1">Generate branded PDF travel proposals, itinerary quotations, profit margin estimates, and 1-click booking conversions.</p>
        </div>
        <a href="{{ route('admin.quotations.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Create Quotation
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.quotations.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                       placeholder="Search quotation number, title, customer...">
            </div>

            <div>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent to Guest</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted to Booking</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">
                    Filter
                </button>
                <a href="{{ route('admin.quotations.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Quotations Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">Quote Number</th>
                        <th class="py-3.5 px-6">Prospect / Guest</th>
                        <th class="py-3.5 px-6">Tour Title & Destination</th>
                        <th class="py-3.5 px-6">Total (INR)</th>
                        <th class="py-3.5 px-6">Profit Margin</th>
                        <th class="py-3.5 px-6">Valid Until</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($quotations as $quot)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <a href="{{ route('admin.quotations.show', $quot->id) }}" class="text-amber-600 hover:text-amber-700">
                                    {{ $quot->quotation_number }}
                                </a>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-900 block">{{ $quot->customer?->name }}</span>
                                <span class="text-[11px] text-slate-400">{{ $quot->customer?->phone }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-semibold text-slate-800 block">{{ $quot->title }}</span>
                                <span class="text-[11px] text-slate-400">{{ $quot->destination }} &bull; {{ $quot->adults }} Adults</span>
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-900">
                                ₹{{ number_format($quot->total_amount, 2) }}
                            </td>
                            <td class="py-4 px-6 font-bold text-emerald-700">
                                ₹{{ number_format($quot->profit_margin, 2) }}
                            </td>
                            <td class="py-4 px-6">
                                {{ $quot->valid_until ? \Carbon\Carbon::parse($quot->valid_until)->format('M d, Y') : 'Open' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                                    {{ $quot->status == 'converted' ? 'bg-emerald-100 text-emerald-800' : ($quot->status == 'draft' ? 'bg-slate-100 text-slate-700' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($quot->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-1">
                                <a href="{{ route('admin.quotations.pdf.download', $quot->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-lg transition inline-flex items-center gap-1">
                                    <i data-lucide="download" class="w-3 h-3"></i> PDF
                                </a>
                                <a href="{{ route('admin.quotations.show', $quot->id) }}" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold rounded-lg transition inline-block">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                No quotations found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($quotations->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $quotations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
