@extends('layouts.admin')

@section('title', 'Register New Sales Lead | BharatTravel')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.leads.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Pipeline</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-amber-600 uppercase">New Inbound Intake</span>
            </div>
            <h1 class="text-xl font-bold font-serif text-slate-900">Add Lead Record</h1>
            <p class="text-xs text-slate-500 mt-0.5">Capture client travel requirement, assign to branch executive, and initialize CRM sales tracking.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 text-xs rounded-r">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-xs">
        <form action="{{ route('admin.leads.store') }}" method="POST" class="space-y-6 text-xs">
            @csrf

            <!-- Contact Information -->
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4 text-amber-500"></i> Client Details
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Prospect Full Name *</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                               placeholder="e.g. Alok Singhania">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Mobile Phone (WhatsApp) *</label>
                        <input type="tel" name="phone" required value="{{ old('phone') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                               placeholder="+91 98765 43210">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                               placeholder="alok@example.com">
                    </div>
                </div>
            </div>

            <!-- Travel Requirements -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-amber-500"></i> Travel Specification
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Destination / Holiday Title *</label>
                        <input type="text" name="destination" required value="{{ old('destination') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                               placeholder="e.g. Kashmir Valley 6D5N">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Departure City</label>
                        <input type="text" name="departure_city" value="{{ old('departure_city') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                               placeholder="e.g. New Delhi">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Service Category *</label>
                        <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="custom_holiday">Custom Holiday Package</option>
                            <option value="domestic_package">Domestic Tour Package</option>
                            <option value="international_package">International Tour Package</option>
                            <option value="visa">Visa Assistance</option>
                            <option value="passport">Passport Assistance</option>
                            <option value="flight">Flight Booking</option>
                            <option value="hotel">Hotel Reservation</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Adults (12+ Yrs) *</label>
                        <input type="number" name="adults" min="1" required value="{{ old('adults', 2) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Children (2-12 Yrs)</label>
                        <input type="number" name="children" min="0" value="{{ old('children', 0) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Approx Budget (₹)</label>
                        <input type="number" name="budget" value="{{ old('budget') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                               placeholder="e.g. 75000">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Expected Travel Date</label>
                        <input type="date" name="travel_start_date" value="{{ old('travel_start_date') }}" min="{{ date('Y-m-d') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Lead Source</label>
                        <select name="lead_source_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="">Direct / Walk-in</option>
                            @foreach($sources as $src)
                                <option value="{{ $src->id }}" {{ old('lead_source_id') == $src->id ? 'selected' : '' }}>{{ $src->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Branch Office</label>
                        <select name="branch_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="">Headquarters</option>
                            @foreach($branches as $br)
                                <option value="{{ $br->id }}" {{ old('branch_id') == $br->id ? 'selected' : '' }}>{{ $br->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- CRM Routing & Assignment -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="shield" class="w-4 h-4 text-amber-500"></i> Routing & Priority
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Assign To Executive</label>
                        <select name="assigned_to" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="">Auto-Assign Later</option>
                            @foreach($staffMembers as $stf)
                                <option value="{{ $stf->id }}" {{ old('assigned_to') == $stf->id ? 'selected' : '' }}>{{ $stf->name }} ({{ $stf->role }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Initial Priority *</label>
                        <select name="priority" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Lead Status *</label>
                        <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="new" selected>New Lead</option>
                            <option value="contacted">Contacted</option>
                            <option value="proposal_sent">Proposal Sent</option>
                            <option value="negotiation">Negotiation</option>
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Internal Notes & Client Requirements</label>
                <textarea name="notes" rows="3"
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                          placeholder="Client preferences, flight requests, hotel star preferences...">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition shadow-sm flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i> Create Lead & Open Timeline
            </button>
        </form>
    </div>
</div>
@endsection

