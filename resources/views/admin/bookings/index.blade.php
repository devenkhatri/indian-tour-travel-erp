@extends('layouts.admin')

@section('title', 'Central Tour Bookings Master | BharatTravel')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Central Tour Bookings Management</h1>
            <p class="text-xs text-slate-500 mt-1">Direct retail bookings, B2B sub-agent bookings, payment statuses, and travel operations tracking.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                       placeholder="Search booking ref, customer...">
            </div>

            <div>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    <option value="">All Booking Statuses</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="pending_payment" {{ request('status') == 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress (Touring)</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <select name="payment_status" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    <option value="">All Payment Statuses</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Fully Paid</option>
                    <option value="partially_paid" {{ request('payment_status') == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                </select>
            </div>

            <div>
                <select name="service_type" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    <option value="">All Service Types</option>
                    <option value="package" {{ request('service_type') == 'package' ? 'selected' : '' }}>Holiday Package</option>
                    <option value="flight" {{ request('service_type') == 'flight' ? 'selected' : '' }}>Flight</option>
                    <option value="hotel" {{ request('service_type') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                    <option value="train" {{ request('service_type') == 'train' ? 'selected' : '' }}>Train</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">
                    Filter
                </button>
                <a href="{{ route('admin.bookings.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">Booking Number</th>
                        <th class="py-3.5 px-6">Customer / Agent</th>
                        <th class="py-3.5 px-6">Package / Service</th>
                        <th class="py-3.5 px-6">Departure Date</th>
                        <th class="py-3.5 px-6">Total Amount</th>
                        <th class="py-3.5 px-6">Balance Due</th>
                        <th class="py-3.5 px-6">Payment</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Dossier</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bookings as $bk)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <a href="{{ route('admin.bookings.show', $bk->id) }}" class="text-amber-600 hover:text-amber-700">
                                    {{ $bk->booking_number }}
                                </a>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-900 block">{{ $bk->customer ? $bk->customer->name : 'Walk-in' }}</span>
                                @if($bk->agent)
                                    <span class="text-[10px] text-teal-700 font-bold">Via B2B: {{ $bk->agent->agency_name }}</span>
                                @else
                                    <span class="text-[10px] text-slate-400">Direct Retail</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-semibold text-slate-800 block">{{ $bk->package ? $bk->package->title : 'Custom Reservation' }}</span>
                                <span class="text-[11px] text-slate-400">{{ $bk->package?->destination?->name }}</span>
                            </td>
                            <td class="py-4 px-6">
                                {{ $bk->departure_date ? \Carbon\Carbon::parse($bk->departure_date)->format('M d, Y') : 'Open Date' }}
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-900">
                                ₹{{ number_format($bk->total_amount, 2) }}
                            </td>
                            <td class="py-4 px-6 font-semibold {{ $bk->balance_amount > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                                ₹{{ number_format($bk->balance_amount, 2) }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                    {{ $bk->payment_status == 'paid' ? 'bg-emerald-100 text-emerald-800' : ($bk->payment_status == 'partially_paid' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ ucfirst($bk->payment_status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                                    {{ $bk->booking_status == 'confirmed' ? 'bg-teal-100 text-teal-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ ucfirst($bk->booking_status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.bookings.show', $bk->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-lg transition">
                                    Manage &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400">
                                No bookings found matching the selected filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

