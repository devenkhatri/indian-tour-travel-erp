@extends('layouts.admin')

@section('title', 'Customer CRM & Guest Directory | BharatTravel')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Customer CRM Directory</h1>
            <p class="text-xs text-slate-500 mt-1">Complete database of retail travellers, corporate accounts, guest history, and lifetime value.</p>
        </div>
        <a href="{{ route('admin.customers.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Add New Customer
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 gap-3">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                       placeholder="Search name, email, phone, customer code, city...">
            </div>

            <div>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="blacklisted" {{ request('status') == 'blacklisted' ? 'selected' : '' }}>Blacklisted</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">
                    Filter
                </button>
                <a href="{{ route('admin.customers.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Customer Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">Customer Code</th>
                        <th class="py-3.5 px-6">Guest Name</th>
                        <th class="py-3.5 px-6">Contact Details</th>
                        <th class="py-3.5 px-6">Location</th>
                        <th class="py-3.5 px-6 text-center">Bookings</th>
                        <th class="py-3.5 px-6 text-center">Invoices</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $cust)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <a href="{{ route('admin.customers.show', $cust->id) }}" class="text-amber-600 hover:text-amber-700">
                                    {{ $cust->customer_code }}
                                </a>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-900 block">{{ $cust->name }}</span>
                                @if($cust->passport_number)
                                    <span class="text-[10px] text-teal-700 font-semibold">PPT: {{ $cust->passport_number }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-slate-800 font-medium">{{ $cust->phone }}</p>
                                <p class="text-[11px] text-slate-400">{{ $cust->email ?? 'No email' }}</p>
                            </td>
                            <td class="py-4 px-6">
                                {{ $cust->city ?? 'N/A' }}{{ $cust->state ? ', ' . $cust->state : '' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-800">
                                    {{ $cust->bookings_count }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $cust->invoices_count }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                                    {{ $cust->status == 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ ucfirst($cust->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-1">
                                <a href="{{ route('admin.customers.show', $cust->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-lg transition inline-block">
                                    Profile
                                </a>
                                <a href="{{ route('admin.customers.edit', $cust->id) }}" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold rounded-lg transition inline-block">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                No customer records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

