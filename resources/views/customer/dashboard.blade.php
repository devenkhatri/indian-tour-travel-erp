@extends('layouts.customer')

@section('title', 'Traveller Dashboard | BharatTravel')

@section('customer_content')
<!-- Welcome Header -->
<div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900">Welcome, {{ Auth::user()->name }} 👋</h1>
        <p class="text-xs text-slate-500 mt-1">Manage your tour itineraries, download travel vouchers, track visa approvals and payments.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('packages.index') }}" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> Book New Tour
        </a>
    </div>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div class="flex items-center justify-between text-slate-400 mb-2">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Trips</span>
            <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                <i data-lucide="plane" class="w-4 h-4"></i>
            </div>
        </div>
        <div class="text-2xl font-extrabold text-slate-900">{{ $totalBookings }}</div>
        <p class="text-[11px] text-slate-400 mt-1">Booked with BharatTravel</p>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div class="flex items-center justify-between text-slate-400 mb-2">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Pending Due</span>
            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                <i data-lucide="credit-card" class="w-4 h-4"></i>
            </div>
        </div>
        <div class="text-2xl font-extrabold text-slate-900">₹{{ number_format($pendingPayments, 2) }}</div>
        <p class="text-[11px] text-amber-600 font-medium mt-1">Outstanding Balance</p>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div class="flex items-center justify-between text-slate-400 mb-2">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Visas / Passports</span>
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                <i data-lucide="stamp" class="w-4 h-4"></i>
            </div>
        </div>
        <div class="text-2xl font-extrabold text-slate-900">{{ $visaApplications + $passportApplications }}</div>
        <p class="text-[11px] text-slate-400 mt-1">Active Facilitation</p>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div class="flex items-center justify-between text-slate-400 mb-2">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Active Inquiries</span>
            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                <i data-lucide="help-circle" class="w-4 h-4"></i>
            </div>
        </div>
        <div class="text-2xl font-extrabold text-slate-900">{{ $recentInquiries->count() }}</div>
        <p class="text-[11px] text-slate-400 mt-1">Under Quote Stage</p>
    </div>
</div>

<!-- Upcoming Departure Card -->
@if($upcomingTrip)
    <div class="bg-gradient-to-r from-teal-900 to-slate-900 text-white rounded-2xl p-6 shadow-sm border border-teal-800">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="space-y-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-teal-500/20 text-teal-300 border border-teal-500/30">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i> UPCOMING JOURNEY
                </span>
                <h3 class="text-xl font-bold font-serif">{{ $upcomingTrip->package ? $upcomingTrip->package->title : 'Custom Package' }}</h3>
                <p class="text-xs text-slate-300 flex items-center gap-3">
                    <span><i data-lucide="map-pin" class="w-3.5 h-3.5 inline mr-1 text-teal-400"></i>{{ $upcomingTrip->package?->destination?->name ?? 'India' }}</span>
                    <span>•</span>
                    <span><i data-lucide="clock" class="w-3.5 h-3.5 inline mr-1 text-teal-400"></i>Departure: {{ \Carbon\Carbon::parse($upcomingTrip->departure_date)->format('D, M d, Y') }}</span>
                    <span>•</span>
                    <span><i data-lucide="users" class="w-3.5 h-3.5 inline mr-1 text-teal-400"></i>{{ $upcomingTrip->adults_count + $upcomingTrip->children_count }} Travellers</span>
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('customer.bookings.show', $upcomingTrip->id) }}" class="px-4 py-2.5 bg-teal-500 hover:bg-teal-400 text-slate-900 font-bold rounded-xl text-xs transition shadow">
                    View Trip Dossier & Vouchers
                </a>
            </div>
        </div>
    </div>
@endif

<!-- Recent Bookings Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-sm font-bold text-slate-900">Recent Tour Bookings</h3>
        <a href="{{ route('customer.bookings') }}" class="text-xs font-bold text-teal-600 hover:text-teal-700">View All Bookings &rarr;</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                <tr>
                    <th class="py-3 px-6">Booking Ref</th>
                    <th class="py-3 px-6">Package & Destination</th>
                    <th class="py-3 px-6">Departure Date</th>
                    <th class="py-3 px-6">Amount</th>
                    <th class="py-3 px-6">Payment</th>
                    <th class="py-3 px-6">Status</th>
                    <th class="py-3 px-6 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-4 px-6 font-bold text-slate-900">
                            {{ $booking->booking_reference }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-bold text-slate-800 block">{{ $booking->package ? $booking->package->title : 'Custom Package' }}</span>
                            <span class="text-[11px] text-slate-400">{{ $booking->package?->destination?->name }}</span>
                        </td>
                        <td class="py-4 px-6">
                            {{ $booking->departure_date ? \Carbon\Carbon::parse($booking->departure_date)->format('M d, Y') : 'Open Date' }}
                        </td>
                        <td class="py-4 px-6 font-bold text-slate-900">
                            ₹{{ number_format($booking->total_amount, 2) }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold 
                                {{ $booking->payment_status == 'paid' ? 'bg-emerald-100 text-emerald-800' : ($booking->payment_status == 'partial' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold 
                                {{ $booking->booking_status == 'confirmed' ? 'bg-teal-100 text-teal-800' : 'bg-slate-100 text-slate-700' }}">
                                {{ ucfirst($booking->booking_status) }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('customer.bookings.show', $booking->id) }}" class="text-teal-600 hover:text-teal-800 font-bold">
                                Dossier &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">
                            No bookings yet. Explore our domestic & international packages!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Active Inquiries Table -->
@if($recentInquiries->count() > 0)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Your Recent Enquiries & Quotes</h3>
            <a href="{{ route('customer.inquiries') }}" class="text-xs font-bold text-teal-600 hover:text-teal-700">View All &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-6">Lead ID</th>
                        <th class="py-3 px-6">Service / Destination</th>
                        <th class="py-3 px-6">Type</th>
                        <th class="py-3 px-6">Date Submitted</th>
                        <th class="py-3 px-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recentInquiries as $inq)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-6 font-bold text-slate-800">{{ $inq->lead_number }}</td>
                            <td class="py-3 px-6 font-medium text-slate-700">{{ $inq->destination }}</td>
                            <td class="py-3 px-6 uppercase text-[10px] font-bold text-slate-500">{{ $inq->category }}</td>
                            <td class="py-3 px-6 text-slate-500">{{ $inq->created_at->format('M d, Y') }}</td>
                            <td class="py-3 px-6">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700">
                                    {{ ucfirst($inq->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection

