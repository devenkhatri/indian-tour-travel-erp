@extends('layouts.app')

@section('title', 'Comprehensive Travel Services - BharatTravel')

@section('content')
<section class="bg-gradient-to-r from-slate-900 to-slate-800 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-400 border border-amber-500/30 uppercase tracking-widest mb-4">
            End-to-End Travel Logistics
        </span>
        <h1 class="text-4xl md:text-5xl font-bold font-serif mb-4">Comprehensive Travel & Concierge Services</h1>
        <p class="text-slate-300 max-w-2xl mx-auto text-base md:text-lg">
            From single-day train reservations to worldwide multi-entry business visas, BharatTravel manages every dimension of your journey with complete peace of mind.
        </p>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Custom Holidays -->
            <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mb-6">
                        <i data-lucide="map" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Tailor-Made Custom Holidays</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        Custom day-by-day itineraries tailored to your dates, family preferences, vehicle choice, and curated sightseeing tours.
                    </p>
                </div>
                <a href="{{ route('services.custom_tour') }}" class="inline-flex items-center font-bold text-sm text-amber-600 hover:text-amber-700">
                    Plan Custom Itinerary <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                </a>
            </div>

            <!-- Visa Assistance -->
            <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
                        <i data-lucide="stamp" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">International Visa Services</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        Fast-track eVisa and sticker visa assistance for Dubai, Schengen, UK, US, Singapore, Thailand, Bali, and 45+ countries.
                    </p>
                </div>
                <a href="{{ route('services.visa') }}" class="inline-flex items-center font-bold text-sm text-blue-600 hover:text-blue-700">
                    Apply for Visa Assistance <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                </a>
            </div>

            <!-- Passport Assistance -->
            <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                        <i data-lucide="book-open" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Indian Passport Assistance</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        Document validation, online portal filing, and appointment slot booking for Fresh, Reissue, Renewal, and Tatkal passports.
                    </p>
                </div>
                <a href="{{ route('services.passport') }}" class="inline-flex items-center font-bold text-sm text-indigo-600 hover:text-indigo-700">
                    Get Passport Help <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                </a>
            </div>

            <!-- Flight Ticketing -->
            <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-6">
                        <i data-lucide="plane" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Domestic & Global Flights</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        Corporate discounted fares, group baggage allowances, free seat selection advice, and 24x7 reschedule/cancellation support.
                    </p>
                </div>
                <a href="{{ route('services.flights') }}" class="inline-flex items-center font-bold text-sm text-emerald-600 hover:text-emerald-700">
                    Enquire Flight Fares <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                </a>
            </div>

            <!-- Hotel Bookings -->
            <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-6">
                        <i data-lucide="building" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Luxury Hotels & Resorts</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        Direct contracted B2B wholesale rates across premium chains (Taj, Oberoi, Marriott, Hyatt) with breakfast and room upgrades.
                    </p>
                </div>
                <a href="{{ route('services.hotels') }}" class="inline-flex items-center font-bold text-sm text-purple-600 hover:text-purple-700">
                    Request Hotel Availability <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                </a>
            </div>

            <!-- Trains & Buses -->
            <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center mb-6">
                        <i data-lucide="train" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">IRCTC Trains & Volvo Buses</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        Confirmed train tickets assistance, Vande Bharat Express reservations, and inter-city Volvo multi-axle luxury coach charters.
                    </p>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('services.trains') }}" class="font-bold text-sm text-rose-600 hover:text-rose-700">Trains</a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('services.buses') }}" class="font-bold text-sm text-rose-600 hover:text-rose-700">Volvo Buses</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

