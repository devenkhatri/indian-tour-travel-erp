@extends('layouts.admin')

@section('title', 'Onboard New Supplier | BharatTravel')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.suppliers.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Suppliers</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-amber-600 uppercase">Vendor Master</span>
            </div>
            <h1 class="text-xl font-bold font-serif text-slate-900">Onboard Supplier Partner</h1>
            <p class="text-xs text-slate-500 mt-0.5">Register hotelier, transport fleet vendor, or local destination DMC.</p>
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
        <form action="{{ route('admin.suppliers.store') }}" method="POST" class="space-y-6 text-xs">
            @csrf

            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="briefcase" class="w-4 h-4 text-amber-500"></i> Vendor Identity
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 mb-1">Company / Supplier Legal Name *</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                               placeholder="e.g. Kashmir Horizon Transports & Cabs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Supplier Category *</label>
                        <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="hotel">Hotels & Resorts</option>
                            <option value="transport" selected>Transporters & Cabs</option>
                            <option value="dmc">Destination Management Company (DMC)</option>
                            <option value="airline">Airlines</option>
                            <option value="visa">Visa Facilitation</option>
                            <option value="insurance">Travel Insurance</option>
                            <option value="cruise">Cruise Liners</option>
                            <option value="other">Other Service Vendor</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Contact Person Name</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs"
                               placeholder="e.g. Tariq Mir">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Phone Number (WhatsApp)</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs"
                               placeholder="+91 94190 12345">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs"
                               placeholder="tariq@horizonkashmir.com">
                    </div>
                </div>
            </div>

            <!-- Tax & Address -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-amber-500"></i> Location & Tax Details
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city', 'Srinagar') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">State</label>
                        <input type="text" name="state" value="{{ old('state', 'Jammu & Kashmir') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">GSTIN Number</label>
                        <input type="text" name="gstin" value="{{ old('gstin') }}" placeholder="01AAAAA0000A1Z5" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs uppercase">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">PAN Number</label>
                        <input type="text" name="pan" value="{{ old('pan') }}" placeholder="AAAAA0000A" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs uppercase">
                    </div>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Supplier Rating (1 to 5 Stars)</label>
                <input type="number" step="0.1" min="1" max="5" name="rating" value="{{ old('rating', 5.0) }}" class="w-32 px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Internal Notes & Contracting Rates</label>
                <textarea name="notes" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs" placeholder="Preferred vehicle supplier in Srinagar with 20 Innovas on contract...">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition shadow-sm flex items-center justify-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i> Onboard Supplier
            </button>
        </form>
    </div>
</div>
@endsection

