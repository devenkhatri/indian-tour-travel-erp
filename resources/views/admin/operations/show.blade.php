@extends('layouts.admin')

@section('title', 'Trip Dispatch Control: ' . $trip->trip_code)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.operations.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Operations</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-teal-600 uppercase">#{{ $trip->trip_code }}</span>
                <span class="text-slate-300">•</span>
                <span class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($trip->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($trip->end_date)->format('M d, Y') }}</span>
            </div>
            <h1 class="text-2xl font-bold font-serif text-slate-900">{{ $trip->title }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Lead Guest: <a href="{{ route('admin.customers.show', $trip->booking?->customer_id ?? 0) }}" class="font-bold text-slate-900 hover:text-amber-600">{{ $trip->booking?->customer?->name }}</a>
                &bull; Booking: <a href="{{ route('admin.bookings.show', $trip->booking_id) }}" class="font-bold text-amber-600 hover:text-amber-700">#{{ $trip->booking?->booking_number }}</a>
            </p>
        </div>

        <div>
            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold 
                {{ $trip->status == 'ongoing' ? 'bg-amber-100 text-amber-800' : ($trip->status == 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800') }}">
                Status: {{ ucfirst($trip->status) }}
            </span>
        </div>
    </div>

    <!-- 2-Column Operations Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Col 1: Driver, Vehicle & Guide Dispatch Form -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs text-xs">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="truck" class="w-4 h-4 text-amber-500"></i> Dispatch & Crew Allocation
                </h3>

                <form action="{{ route('admin.operations.update', $trip->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Trip Status *</label>
                        <select name="status" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                            <option value="upcoming" {{ $trip->status == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="ongoing" {{ $trip->status == 'ongoing' ? 'selected' : '' }}>Ongoing (On Ground)</option>
                            <option value="completed" {{ $trip->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="delayed" {{ $trip->status == 'delayed' ? 'selected' : '' }}>Delayed</option>
                            <option value="cancelled" {{ $trip->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Tour Leader / Escort</label>
                        <select name="tour_leader_id" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                            <option value="">None / Self-Guided</option>
                            @foreach($staffMembers as $stf)
                                <option value="{{ $stf->id }}" {{ $trip->tour_leader_id == $stf->id ? 'selected' : '' }}>{{ $stf->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Vehicle Specification & Number</label>
                        <input type="text" name="vehicle_info" value="{{ old('vehicle_info', $trip->vehicle_info) }}" placeholder="e.g. Toyota Innova Crysta (JK-01-AB-1234)" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Driver Name & Mobile</label>
                        <input type="text" name="driver_info" value="{{ old('driver_info', $trip->driver_info) }}" placeholder="e.g. Bashir Ahmad (+91 94190 12345)" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Local Guide Contact</label>
                        <input type="text" name="guide_info" value="{{ old('guide_info', $trip->guide_info) }}" placeholder="e.g. Tariq (Govt Approved Guide)" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Special Ground Instructions</label>
                        <textarea name="special_instructions" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs" placeholder="Wheelchair required at airport, pure veg meals...">{{ old('special_instructions', $trip->special_instructions) }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">
                        Update Dispatch Info
                    </button>
                </form>
            </div>
        </div>

        <!-- Col 2 & 3: Supplier Procurements & Assignments -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Supplier Procurement Allocation Form -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs text-xs">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="link" class="w-4 h-4 text-teal-600"></i> Assign Supplier / Vendor Procurement
                </h3>

                <form action="{{ route('admin.operations.assign', $trip->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Supplier Partner *</label>
                            <select name="supplier_id" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->name }} ({{ $sup->category }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Service Type *</label>
                            <select name="type" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                                <option value="hotel">Hotel Stay</option>
                                <option value="vehicle" selected>Vehicle Hire</option>
                                <option value="driver">Driver Allowance</option>
                                <option value="guide">Guide Fee</option>
                                <option value="transfer">Airport Transfer</option>
                                <option value="activity">Activity Tickets</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Contract Cost (₹) *</label>
                            <input type="number" step="0.01" name="cost" required value="5000" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1">Service Description / Voucher Details</label>
                            <input type="text" name="details" placeholder="e.g. 5 days Innova Crysta pickup to drop" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Assignment Status *</label>
                            <select name="status" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                                <option value="assigned">Assigned</option>
                                <option value="confirmed" selected>Confirmed by Supplier</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl text-xs transition shadow-xs">
                        Record Supplier Procurement
                    </button>
                </form>
            </div>

            <!-- Existing Supplier Allocations Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i> Fulfillments on File ({{ $trip->assignments->count() }})
                    </h3>
                    <span class="text-xs font-bold text-slate-900">Total Supplier Cost: ₹{{ number_format($trip->assignments->sum('cost'), 2) }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-6">Supplier</th>
                                <th class="py-3 px-6">Type</th>
                                <th class="py-3 px-6">Details</th>
                                <th class="py-3 px-6">Cost (INR)</th>
                                <th class="py-3 px-6">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($trip->assignments as $asg)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-3.5 px-6 font-bold text-slate-900">{{ $asg->supplier?->name }}</td>
                                    <td class="py-3.5 px-6 uppercase text-[10px] font-bold text-slate-700">{{ $asg->type }}</td>
                                    <td class="py-3.5 px-6">{{ $asg->details['notes'] ?? 'Standard Fulfillment' }}</td>
                                    <td class="py-3.5 px-6 font-bold text-slate-900">₹{{ number_format($asg->cost, 2) }}</td>
                                    <td class="py-3.5 px-6">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">{{ $asg->status }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400">
                                        No suppliers assigned yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

