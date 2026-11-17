@extends('layouts.app')

@section('title', 'Train Ticket Booking Assistance - BharatTravel')

@section('content')
<section class="bg-gradient-to-r from-slate-900 to-rose-950 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-rose-500/20 text-rose-400 border border-rose-500/30 uppercase tracking-widest mb-4">
            IRCTC Authorized Rail Concierge
        </span>
        <h1 class="text-4xl md:text-5xl font-bold font-serif mb-4">Indian Railways Ticket Booking Assistance</h1>
        <p class="text-slate-300 max-w-2xl mx-auto text-base md:text-lg">
            Guaranteed Tatkal assistance, Vande Bharat Express, Rajdhani & Tejas seat allocation with real-time PNR status tracking.
        </p>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-sm border border-slate-200">
            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-2">Book Train Tickets with Expert Help</h2>
            <p class="text-slate-600 text-sm mb-6">Avoid last-minute waitlist stress. Our rail ticketing desk monitors seat matrices and charts.</p>

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

            <form action="{{ route('services.trains.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Origin Station *</label>
                        <input type="text" name="from_station" required value="{{ old('from_station') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500 text-sm"
                               placeholder="e.g. New Delhi (NDLS)">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Destination Station *</label>
                        <input type="text" name="to_station" required value="{{ old('to_station') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500 text-sm"
                               placeholder="e.g. Jammu Tawi (JAT) / Mumbai Central">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Journey Date *</label>
                        <input type="date" name="journey_date" required min="{{ date('Y-m-d') }}" value="{{ old('journey_date') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Class Preference *</label>
                        <select name="class_type" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500 text-sm">
                            <option value="1A - First AC">1A - Executive First AC</option>
                            <option value="2A - AC 2 Tier" selected>2A - AC 2 Tier</option>
                            <option value="3A - AC 3 Tier">3A - AC 3 Tier</option>
                            <option value="CC - AC Chair Car (Vande Bharat)">CC - AC Chair Car (Vande Bharat)</option>
                            <option value="EC - Executive Chair Car">EC - Executive Chair Car</option>
                            <option value="SL - Sleeper Class">SL - Sleeper Class</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Number of Passengers *</label>
                        <select name="passenger_count" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500 text-sm">
                            @for($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}">{{ $i }} Passenger{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Your Full Name *</label>
                        <input type="text" name="contact_name" required value="{{ old('contact_name', Auth::user()?->name) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500 text-sm"
                               placeholder="Full Name">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Mobile Number (WhatsApp) *</label>
                        <input type="tel" name="contact_phone" required value="{{ old('contact_phone', Auth::user()?->phone) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500 text-sm"
                               placeholder="+91 98765 43210">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Train Number Preference / Senior Citizen / Berth Notes</label>
                    <textarea name="remarks" rows="2"
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500 text-sm"
                              placeholder="Mention specific train name/number, lower berth request, or Tatkal requirement...">{{ old('remarks') }}</textarea>
                </div>

                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 bg-gradient-to-r from-rose-600 to-red-700 hover:from-rose-700 hover:to-red-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <i data-lucide="train" class="w-4 h-4 mr-2"></i>
                    Request Train Reservation
                </button>
            </form>
        </div>
    </div>
</section>
@endsection

