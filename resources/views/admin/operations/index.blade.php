@extends('layouts.admin')

@section('title', 'Ground Operations & Trip Manifests | BharatTravel')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Ground Operations & Trip Control</h1>
            <p class="text-xs text-slate-500 mt-1">Vehicle assignments, chauffeur allocations, local tourist guides, and supplier service fulfillments.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.operations.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                       placeholder="Search trip code or title...">
            </div>

            <div>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    <option value="">All Trip Statuses</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming Departures</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing on Ground</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="delayed" {{ request('status') == 'delayed' ? 'selected' : '' }}>Delayed</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">
                    Filter
                </button>
                <a href="{{ route('admin.operations.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Trips Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">Trip Code</th>
                        <th class="py-3.5 px-6">Tour Title</th>
                        <th class="py-3.5 px-6">Guest / Booking</th>
                        <th class="py-3.5 px-6">Journey Dates</th>
                        <th class="py-3.5 px-6">Driver & Vehicle</th>
                        <th class="py-3.5 px-6">Tour Leader</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Dispatch Sheet</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($trips as $trip)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <a href="{{ route('admin.operations.show', $trip->id) }}" class="text-amber-600 hover:text-amber-700">
                                    {{ $trip->trip_code }}
                                </a>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-800">
                                {{ $trip->title }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-900 block">{{ $trip->booking?->customer?->name }}</span>
                                <span class="text-[10px] text-slate-400">#{{ $trip->booking?->booking_number }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-800 block">{{ \Carbon\Carbon::parse($trip->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($trip->end_date)->format('M d, Y') }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-slate-800 font-medium">{{ $trip->vehicle_info ?? 'Pending Vehicle' }}</p>
                                <p class="text-[10px] text-slate-400">{{ $trip->driver_info ?? 'Pending Driver' }}</p>
                            </td>
                            <td class="py-4 px-6">
                                {{ $trip->tourLeader?->name ?? 'None' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                                    {{ $trip->status == 'ongoing' ? 'bg-amber-100 text-amber-800' : ($trip->status == 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($trip->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.operations.show', $trip->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-lg transition">
                                    Operations &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                No ground trips registered.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($trips->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $trips->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

