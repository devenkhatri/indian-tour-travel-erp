@extends('layouts.admin')

@section('title', 'Executive Travel ERP & CRM Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Top Executive Header -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 uppercase tracking-wider">
                    Operations Center
                </span>
                <span class="text-slate-300">•</span>
                <span class="text-xs text-slate-500">{{ now()->format('l, F d, Y') }}</span>
            </div>
            <h1 class="text-2xl font-bold font-serif text-slate-900">Welcome to BharatTravel Command HQ</h1>
            <p class="text-xs text-slate-500 mt-0.5">Real-time revenue, guest departures, booking pipeline, and team follow-ups.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.leads.create') }}" class="px-3.5 py-2 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-1.5">
                <i data-lucide="user-plus" class="w-3.5 h-3.5"></i> Add Lead
            </a>
            <a href="{{ route('admin.packages.create') }}" class="px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-1.5">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i> New Package
            </a>
            <a href="{{ route('admin.quotations.create') }}" class="px-3.5 py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-1.5">
                <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Build Quote
            </a>
        </div>
    </div>

    <!-- 4 Primary Financial KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Gross Sales</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-slate-900">₹{{ number_format($totalRevenue, 2) }}</div>
            <p class="text-[11px] text-emerald-600 font-semibold mt-1">Collected: ₹{{ number_format($totalPaidRevenue, 2) }}</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Gross Profit</span>
                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                    <i data-lucide="pie-chart" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-teal-700">₹{{ number_format($grossProfit, 2) }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Expenses: ₹{{ number_format($totalExpenses, 2) }}</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Payment Due</span>
                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-rose-600">₹{{ number_format($outstandingPayments, 2) }}</div>
            <p class="text-[11px] text-rose-500 font-medium mt-1">Receivables from Pax & Agents</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Active Pipeline</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-slate-900">{{ $totalLeads }} Leads</div>
            <p class="text-[11px] text-blue-600 font-semibold mt-1">{{ $convertedLeads }} Won &bull; {{ $newEnquiries }} New</p>
        </div>
    </div>

    <!-- Operational Alerts Bar -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Upcoming 7-Day Departures -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-400 flex items-center gap-1.5">
                    <i data-lucide="plane-takeoff" class="w-4 h-4"></i> 7-Day Departures
                </span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/20 text-white">{{ $upcomingDepartures->count() }}</span>
            </div>
            <div class="space-y-2 text-xs">
                @forelse($upcomingDepartures->take(3) as $dep)
                    <div class="p-2.5 bg-white/10 rounded-xl flex items-center justify-between">
                        <div>
                            <p class="font-bold text-white">{{ $dep->customer?->name }}</p>
                            <p class="text-[10px] text-slate-300">{{ $dep->package?->title }}</p>
                        </div>
                        <span class="text-[10px] font-bold text-amber-300">{{ \Carbon\Carbon::parse($dep->departure_date)->format('M d') }}</span>
                    </div>
                @empty
                    <p class="text-slate-400 text-xs py-2">No trips leaving this week.</p>
                @endforelse
            </div>
        </div>

        <!-- Pending Visa & Passport Files -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                        <i data-lucide="stamp" class="w-4 h-4 text-blue-600"></i> Visa & Passport Ops
                    </span>
                    <a href="{{ route('admin.visa.index') }}" class="text-[10px] font-bold text-blue-600 hover:text-blue-800">Desk &rarr;</a>
                </div>
                <div class="grid grid-cols-2 gap-3 mt-3 text-center">
                    <div class="p-3 bg-blue-50 rounded-xl">
                        <p class="text-xl font-extrabold text-blue-900">{{ $pendingVisas }}</p>
                        <p class="text-[10px] font-semibold text-blue-600 uppercase mt-0.5">Visas in Pipeline</p>
                    </div>
                    <div class="p-3 bg-indigo-50 rounded-xl">
                        <p class="text-xl font-extrabold text-indigo-900">{{ $pendingPassports }}</p>
                        <p class="text-[10px] font-semibold text-indigo-600 uppercase mt-0.5">PSK Files Active</p>
                    </div>
                </div>
            </div>
            <p class="text-[11px] text-slate-400 mt-3">Pre-scrutiny and biometric appointment tracking</p>
        </div>

        <!-- Priority Follow-ups Today -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                        <i data-lucide="phone-call" class="w-4 h-4 text-amber-500"></i> Follow-ups Due
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">{{ $upcomingFollowups->count() }} Due</span>
                </div>
                <div class="space-y-2 mt-2 text-xs">
                    @forelse($upcomingFollowups->take(3) as $fl)
                        <div class="p-2 bg-slate-50 rounded-lg flex items-center justify-between">
                            <div>
                                <p class="font-bold text-slate-800">{{ $fl->lead?->name }}</p>
                                <p class="text-[10px] text-slate-400">{{ $fl->type }} &bull; {{ $fl->lead?->destination }}</p>
                            </div>
                            <span class="text-[10px] font-bold text-rose-600">{{ \Carbon\Carbon::parse($fl->followup_date)->format('M d') }}</span>
                        </div>
                    @empty
                        <p class="text-slate-400 text-xs py-2">No follow-ups due right now.</p>
                    @endforelse
                </div>
            </div>
            <a href="{{ route('admin.leads.index') }}" class="text-[11px] font-bold text-amber-600 hover:text-amber-800 mt-2 block">
                Manage Lead Queue &rarr;
            </a>
        </div>
    </div>

    <!-- Split Tables: Recent Bookings & Inquiries -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Bookings Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900">Recent Tour Bookings</h3>
                <a href="{{ route('admin.bookings.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700">All Bookings &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-6">Booking Ref</th>
                            <th class="py-3 px-6">Customer</th>
                            <th class="py-3 px-6">Total Amount</th>
                            <th class="py-3 px-6">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentBookings as $bk)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-6 font-bold text-slate-900">
                                    <a href="{{ route('admin.bookings.show', $bk->id) }}" class="text-amber-600 hover:text-amber-700">
                                        {{ $bk->booking_number }}
                                    </a>
                                </td>
                                <td class="py-3 px-6 font-medium text-slate-800">{{ $bk->customer?->name }}</td>
                                <td class="py-3 px-6 font-bold text-slate-900">₹{{ number_format($bk->total_amount, 2) }}</td>
                                <td class="py-3 px-6">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                        {{ $bk->booking_status == 'confirmed' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ ucfirst($bk->booking_status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Leads Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900">New Sales Leads</h3>
                <a href="{{ route('admin.leads.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700">All Leads &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-6">Lead ID</th>
                            <th class="py-3 px-6">Prospect Name</th>
                            <th class="py-3 px-6">Destination</th>
                            <th class="py-3 px-6">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentLeads as $lead)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-6 font-bold text-slate-900">
                                    <a href="{{ route('admin.leads.show', $lead->id) }}" class="text-amber-600 hover:text-amber-700">
                                        {{ $lead->lead_number }}
                                    </a>
                                </td>
                                <td class="py-3 px-6 font-medium text-slate-800">{{ $lead->name }}</td>
                                <td class="py-3 px-6">{{ $lead->destination }}</td>
                                <td class="py-3 px-6">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                        {{ $lead->status == 'converted' ? 'bg-emerald-100 text-emerald-800' : ($lead->status == 'new' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ ucfirst($lead->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

