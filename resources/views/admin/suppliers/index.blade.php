@extends('layouts.admin')

@section('title', 'Supplier Partners & Vendors | BharatTravel')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Suppliers, Hoteliers & Transporters</h1>
            <p class="text-xs text-slate-500 mt-1">Vendor contracts, local DMCs, transport operators, and service procurement directory.</p>
        </div>
        <a href="{{ route('admin.suppliers.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Supplier
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.suppliers.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                       placeholder="Search supplier name, city, contact...">
            </div>

            <div>
                <select name="category" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    <option value="">All Categories</option>
                    <option value="hotel" {{ request('category') == 'hotel' ? 'selected' : '' }}>Hotels & Resorts</option>
                    <option value="transport" {{ request('category') == 'transport' ? 'selected' : '' }}>Transporters & Cabs</option>
                    <option value="dmc" {{ request('category') == 'dmc' ? 'selected' : '' }}>Local DMCs</option>
                    <option value="airline" {{ request('category') == 'airline' ? 'selected' : '' }}>Airlines</option>
                    <option value="visa" {{ request('category') == 'visa' ? 'selected' : '' }}>Visa Agents</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">
                    Filter
                </button>
                <a href="{{ route('admin.suppliers.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Supplier Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">Supplier Code</th>
                        <th class="py-3.5 px-6">Supplier Name</th>
                        <th class="py-3.5 px-6">Category</th>
                        <th class="py-3.5 px-6">Contact Person</th>
                        <th class="py-3.5 px-6">City & State</th>
                        <th class="py-3.5 px-6 text-center">Assignments</th>
                        <th class="py-3.5 px-6">Rating</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($suppliers as $sup)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <a href="{{ route('admin.suppliers.show', $sup->id) }}" class="text-amber-600 hover:text-amber-700">
                                    {{ $sup->code }}
                                </a>
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-900">
                                {{ $sup->name }}
                                @if($sup->gstin)
                                    <span class="block text-[10px] font-normal text-slate-400">GST: {{ $sup->gstin }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 uppercase text-[10px] font-bold text-slate-700">
                                {{ $sup->category }}
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-slate-800 font-medium">{{ $sup->contact_person ?? 'Direct' }}</p>
                                <p class="text-[11px] text-slate-400">{{ $sup->phone }}</p>
                            </td>
                            <td class="py-4 px-6">
                                {{ $sup->city ?? 'N/A' }}{{ $sup->state ? ', ' . $sup->state : '' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-800">
                                    {{ $sup->assignments_count }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-amber-500 font-bold flex items-center gap-1">
                                    <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400"></i> {{ $sup->rating ?? '5.0' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-1">
                                <a href="{{ route('admin.suppliers.show', $sup->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-lg transition inline-block">
                                    Profile
                                </a>
                                <a href="{{ route('admin.suppliers.edit', $sup->id) }}" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold rounded-lg transition inline-block">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                No suppliers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($suppliers->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

