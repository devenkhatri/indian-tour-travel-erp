@extends('layouts.app')

@section('title', 'Custom Holiday Itinerary Planner - BharatTravel')

@section('content')
<section class="bg-gradient-to-r from-slate-900 via-amber-950 to-slate-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-400 border border-amber-500/30 uppercase tracking-widest mb-4">
            Custom Holiday Architect
        </span>
        <h1 class="text-4xl md:text-5xl font-bold font-serif mb-4">Craft Your Dream Custom Tour</h1>
        <p class="text-slate-300 max-w-2xl mx-auto text-base md:text-lg">
            Tell us where you wish to explore, your dates, luxury preferences, and vehicle choice. We will curate a customized day-by-day itinerary quotation with transparent pricing within 2 hours.
        </p>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 sm:p-12 rounded-2xl shadow-sm border border-slate-200">
            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-2">Build Your Custom Vacation</h2>
            <p class="text-slate-600 text-sm mb-8">Personalized packages designed from scratch by our senior travel designers.</p>

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

            <form action="{{ route('services.custom_tour.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Target Destination(s) *</label>
                        <input type="text" name="destination" required value="{{ old('destination') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                               placeholder="e.g. Kashmir (Srinagar-Gulmarg-Pahalgam)">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Departure City</label>
                        <input type="text" name="departure_city" value="{{ old('departure_city') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                               placeholder="e.g. New Delhi, Mumbai, Ahmedabad">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Travel Start Date *</label>
                        <input type="date" name="travel_start_date" required min="{{ date('Y-m-d') }}" value="{{ old('travel_start_date') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Travel End Date</label>
                        <input type="date" name="travel_end_date" min="{{ date('Y-m-d') }}" value="{{ old('travel_end_date') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Adults (12+ Yrs) *</label>
                        <select name="adults" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('adults') == $i ? 'selected' : '' }}>{{ $i }} Adult{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Children (2-12) & Infants (<2)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <select name="children" class="w-full px-3 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                                <option value="0">0 Child</option>
                                <option value="1">1 Child</option>
                                <option value="2">2 Children</option>
                                <option value="3">3 Children</option>
                            </select>
                            <select name="infants" class="w-full px-3 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                                <option value="0">0 Infant</option>
                                <option value="1">1 Infant</option>
                                <option value="2">2 Infants</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Approximate Budget (₹ INR Per Person)</label>
                        <input type="number" name="budget" value="{{ old('budget') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                               placeholder="e.g. 45000">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Hotel Category Preference</label>
                        <select name="hotel_category" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                            <option value="3 Star Standard">3 Star Standard & Comfortable</option>
                            <option value="4 Star Premium" selected>4 Star Premium & Boutique</option>
                            <option value="5 Star Luxury">5 Star Luxury / Heritage Palaces</option>
                            <option value="Ultra Luxury / Pool Villas">Ultra Luxury & Private Villas</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Private Vehicle Preference</label>
                        <select name="transport_preference" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                            <option value="Sedan (Dzire / Etios)">Sedan (Dzire / Etios - 1 to 3 Pax)</option>
                            <option value="SUV (Innova Crysta)" selected>Premium SUV (Innova Crysta - 4 to 6 Pax)</option>
                            <option value="Luxury Coach (Tempo Traveller)">Luxury AC Coach (Tempo Traveller - 7 to 12 Pax)</option>
                            <option value="Flight + Cab Combo">Flight + Cab Complete Combo</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Your Full Name *</label>
                        <input type="text" name="name" required value="{{ old('name', Auth::user()?->name) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                               placeholder="e.g. Ananya Sen">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Mobile Phone (WhatsApp) *</label>
                        <input type="tel" name="phone" required value="{{ old('phone', Auth::user()?->phone) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                               placeholder="+91 98765 43210">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Address *</label>
                        <input type="email" name="email" required value="{{ old('email', Auth::user()?->email) }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                               placeholder="ananya@gmail.com">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Special Occasion & Detailed Requirements</label>
                    <textarea name="special_requirements" rows="3"
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"
                              placeholder="Honeymoon candlelight dinner, anniversary cake, vegetarian/jain meals, senior citizen ground floor room requests...">{{ old('special_requirements') }}</textarea>
                </div>

                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-10 py-4 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <i data-lucide="sparkles" class="w-5 h-5 mr-2"></i>
                    Design My Custom Holiday Itinerary
                </button>
            </form>
        </div>
    </div>
</section>
@endsection

