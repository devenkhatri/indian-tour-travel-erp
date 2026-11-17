@extends('layouts.app')

@section('title', 'Luxury Volvo Bus Tickets & Charters - BharatTravel')

@section('content')
<section class="bg-gradient-to-r from-slate-900 to-amber-950 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-400 border border-amber-500/30 uppercase tracking-widest mb-4">
            Intercity Highway Luxury Coaches
        </span>
        <h1 class="text-4xl md:text-5xl font-bold font-serif mb-4">Volvo AC Sleeper & Multi-Axle Bus Bookings</h1>
        <p class="text-slate-300 max-w-2xl mx-auto text-base md:text-lg">
            Guaranteed single sleeper berths, sanitized fleet, GPS tracking, and corporate group coach charters across all major tourist corridors.
        </p>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-sm border border-slate-200">
            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-2">Book Bus Tickets or Charter Coaches</h2>
            <p class="text-slate-600 text-sm mb-6">Select your journey route and coach tier for hassle-free seat confirmation.</p>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-sm rounded-r flex items-center gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 text-sm rounded-r">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('services.buses.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Departure City *</label>
                        <input type="text" name="from_city" required value="{{ old('from_city') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                               placeholder="e.g. Delhi / Bangalore / Mumbai">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Destination City *</label>
                        <input type="text" name="to_city" required value="{{ old('to_city') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                               placeholder="e.g. Manali / Goa / Jaipur">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Journey Date *</label>
                        <input type="date" name="journey_date" required min="{{ date('Y-m-d') }}" value="{{ old('journey_date') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Bus / Coach Category *</label>
                        <select name="bus_type" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                            <option value="Volvo Multi-Axle AC Sleeper">Volvo 9600 Multi-Axle AC Sleeper</option>
                            <option value="Scania AC Semi-Sleeper">Scania AC Semi-Sleeper (2+2)</option>
                            <option value="Mercedes Benz Super Luxury">Mercedes Benz Super Luxury</option>
                            <option value="Private Coach Charter">Private Bus / Tempo Traveller Charter</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Number of Passengers *</label>
                        <select name="passenger_count" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }} Passenger{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                            <option value="15">15 Passengers (Tempo Traveller)</option>
                            <option value="25">25+ Passengers (Mini Bus)</option>
                            <option value="45">45 Passengers (Full Coach)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Contact Name *</label>
                        <input type="text" name="contact_name" required value="{{ old('contact_name', Auth::user()?->name) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                               placeholder="Full Name">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Mobile Phone (WhatsApp) *</label>
                        <input type="tel" name="contact_phone" required value="{{ old('contact_phone', Auth::user()?->phone) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                               placeholder="+91 98765 43210">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Boarding Point Preference / Special Notes</label>
                    <textarea name="remarks" rows="2"
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                              placeholder="Mention preferred boarding point, lower berth preference, or charter duration...">{{ old('remarks') }}</textarea>
                </div>

                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <i data-lucide="bus" class="w-4 h-4 mr-2"></i>
                    Request Bus Reservation
                </button>
            </form>
        </div>
    </div>
</section>
@endsection

