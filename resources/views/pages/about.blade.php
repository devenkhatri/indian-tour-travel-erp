@extends('layouts.app')

@section('title', 'About Us - BharatTravel Premier Travel DMC & Tour Operator')

@section('content')
<!-- Hero Section -->
<section class="relative bg-slate-900 text-white py-24 overflow-hidden">
    <div class="absolute inset-0 opacity-25">
        <img src="https://images.unsplash.com/photo-1524492412937-b28074a5d7da?auto=format&fit=crop&w=1920&q=80" alt="India Tourism" class="w-full h-full object-cover">
    </div>
    <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/90 to-transparent"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-400 border border-amber-500/30 uppercase tracking-widest mb-4">
            Our Heritage & Story
        </span>
        <h1 class="text-4xl md:text-6xl font-bold font-serif mb-6 leading-tight">
            Crafting Unforgettable Journeys <br><span class="text-amber-400">Across Incredible Bharat & The World</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-300 max-w-2xl leading-relaxed">
            Founded with a passion for soulful explorations, BharatTravel is a licensed IATA accredited destination management company delivering personalized domestic & international luxury vacations, corporate MICE, and seamless travel logistics.
        </p>
    </div>
</section>

<!-- Company Stats -->
<section class="py-12 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="p-4">
                <p class="text-4xl font-extrabold text-amber-600 font-serif">18+</p>
                <p class="text-sm font-medium text-slate-600 mt-1">Years of Excellence</p>
            </div>
            <div class="p-4">
                <p class="text-4xl font-extrabold text-amber-600 font-serif">85,000+</p>
                <p class="text-sm font-medium text-slate-600 mt-1">Happy Voyagers</p>
            </div>
            <div class="p-4">
                <p class="text-4xl font-extrabold text-amber-600 font-serif">120+</p>
                <p class="text-sm font-medium text-slate-600 mt-1">Destinations Covered</p>
            </div>
            <div class="p-4">
                <p class="text-4xl font-extrabold text-amber-600 font-serif">99.4%</p>
                <p class="text-sm font-medium text-slate-600 mt-1">Satisfaction Rate</p>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-16 md:py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-serif font-bold text-slate-900 mb-6">Why Modern Travellers Trust BharatTravel</h2>
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-amber-100 text-amber-600 rounded-xl mt-1 shrink-0">
                            <i data-lucide="compass" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-lg">Curated Local Experiences</h3>
                            <p class="text-slate-600 text-sm mt-1">From shikara rides in Srinagar's Dal Lake to desert glamping in Dubai, our journeys are handcrafted with authentic local cultural immersion and verified 4-star and 5-star properties.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-amber-100 text-amber-600 rounded-xl mt-1 shrink-0">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-lg">100% Financial Transparency & GST Compliance</h3>
                            <p class="text-slate-600 text-sm mt-1">No hidden surprises. Clear GST SAC 9985 invoicing, TCS compliance for international tours, and 24x7 emergency ground operations support throughout your journey.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-amber-100 text-amber-600 rounded-xl mt-1 shrink-0">
                            <i data-lucide="users" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-lg">Robust B2B Agent Network</h3>
                            <p class="text-slate-600 text-sm mt-1">We empower over 450+ sub-agents and freelance advisors with direct wholesale net rates, instant voucher generation, and automated commission payouts.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="rounded-3xl overflow-hidden shadow-2xl border-4 border-white">
                    <img src="https://images.unsplash.com/photo-1596178065887-1198b6148b2b?auto=format&fit=crop&w=800&q=80" alt="Kerala Houseboat" class="w-full h-[450px] object-cover">
                </div>
                <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-2xl shadow-xl border border-slate-100 hidden sm:block max-w-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center font-bold">
                            IATA
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">Accredited Partner</p>
                            <p class="text-xs text-slate-500">Ministry of Tourism (Govt of India)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
@if(isset($testimonials) && $testimonials->count() > 0)
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h2 class="text-3xl font-serif font-bold text-slate-900">Voices of Our Voyagers</h2>
            <p class="text-slate-600 mt-2">Real memories from travellers who experienced our tailor-made itineraries</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($testimonials as $item)
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1 text-amber-400 mb-3">
                            @for($i = 0; $i < $item->rating; $i++)
                                <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                            @endfor
                        </div>
                        <p class="text-slate-700 text-sm italic mb-4 leading-relaxed">"{{ $item->content }}"</p>
                    </div>
                    <div class="flex items-center gap-3 pt-3 border-t border-slate-200">
                        <img src="{{ $item->client_avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80' }}" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $item->client_name }}</p>
                            <p class="text-xs text-slate-500">{{ $item->client_city }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

