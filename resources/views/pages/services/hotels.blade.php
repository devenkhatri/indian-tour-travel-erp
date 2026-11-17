@extends('layouts.app')

@section('title', 'Hotel & Luxury Resort Reservations - BharatTravel')

@section('content')
<section class="bg-gradient-to-r from-slate-900 to-purple-950 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-purple-500/20 text-purple-400 border border-purple-500/30 uppercase tracking-widest mb-4">
            Direct Contracted B2B Hotel Rates
        </span>
        <h1 class="text-4xl md:text-5xl font-bold font-serif mb-4">Verified Hotels, Heritage Haveli & Luxury Resorts</h1>
        <p class="text-slate-300 max-w-2xl mx-auto text-base md:text-lg">
            Guaranteed early check-in, complimentary buffet breakfast, room upgrades, and wholesale pricing on 50,000+ properties across India and abroad.
        </p>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-sm border border-slate-200">
            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-2">Book Your Preferred Hotel Stay</h2>
            <p class="text-slate-600 text-sm mb-6">Tell us your destination, dates, and meal preference. We will lock in the best available wholesale room rate.</p>

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

            <form action="{{ route('services.hotels.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Destination City / Location *</label>
                        <input type="text" name="city" required value="{{ old('city') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm"
                               placeholder="e.g. Srinagar, Gulmarg, Jaipur, Dubai">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Specific Hotel Name (Optional)</label>
                        <input type="text" name="hotel_name" value="{{ old('hotel_name') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm"
                               placeholder="e.g. Taj Lake Palace / Atlantis The Palm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Check-in Date *</label>
                        <input type="date" name="check_in_date" required min="{{ date('Y-m-d') }}" value="{{ old('check_in_date') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Check-out Date *</label>
                        <input type="date" name="check_out_date" required min="{{ date('Y-m-d') }}" value="{{ old('check_out_date') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Number of Rooms *</label>
                        <select name="rooms_count" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                            @for($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}" {{ old('rooms_count') == $i ? 'selected' : '' }}>{{ $i }} Room{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Adults & Children</label>
                        <div class="grid grid-cols-2 gap-2">
                            <select name="adults" required class="w-full px-3 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }} Adult{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                            <select name="children" class="w-full px-3 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                                <option value="0">0 Child</option>
                                <option value="1">1 Child</option>
                                <option value="2">2 Children</option>
                                <option value="3">3 Children</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Room Category Preference</label>
                        <select name="room_type" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                            <option value="Deluxe Room">Deluxe Room</option>
                            <option value="Super Deluxe / Valley View">Super Deluxe / Valley View</option>
                            <option value="Executive Suite">Executive Suite</option>
                            <option value="Luxury Villa / Pool Cottage">Luxury Villa / Pool Cottage</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Meal Plan</label>
                        <select name="meal_plan" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                            <option value="CP (Breakfast Included)">CP (Bed & Buffet Breakfast)</option>
                            <option value="MAP (Breakfast + Dinner)">MAP (Half Board: Breakfast + Dinner)</option>
                            <option value="AP (All Meals Included)">AP (Full Board: Breakfast + Lunch + Dinner)</option>
                            <option value="EP (Room Only)">EP (Room Only - No Meals)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Your Name *</label>
                        <input type="text" name="contact_name" required value="{{ old('contact_name', Auth::user()?->name) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm"
                               placeholder="Full Name">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Mobile (WhatsApp) *</label>
                        <input type="tel" name="contact_phone" required value="{{ old('contact_phone', Auth::user()?->phone) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm"
                               placeholder="+91 98765 43210">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Address *</label>
                        <input type="email" name="contact_email" required value="{{ old('contact_email', Auth::user()?->email) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm"
                               placeholder="you@email.com">
                    </div>
                </div>

                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-700 hover:from-purple-700 hover:to-indigo-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <i data-lucide="building" class="w-4 h-4 mr-2"></i>
                    Request Hotel Availability
                </button>
            </form>
        </div>
    </div>
</section>
@endsection

