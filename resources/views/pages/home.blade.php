@extends('layouts.app')

@section('title', 'BharatTravel | Discover Incredible India & World Holiday Packages')

@section('content')

<!-- Hero Banner Section with Search Widget -->
<section class="relative bg-slate-900 text-white min-h-[580px] flex items-center justify-center overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="{{ $banners->first() ? $banners->first()->image : 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?auto=format&fit=crop&w=1600&q=80' }}" alt="Incredible India Travel" class="w-full h-full object-cover opacity-35 scale-105 transform duration-1000">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/60 to-transparent"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center w-full">
        
        <!-- Tagline Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-teal-500/20 border border-teal-400/30 text-teal-300 text-xs font-bold mb-6 backdrop-blur-md">
            <i data-lucide="award" class="w-3.5 h-3.5 text-teal-400"></i> India's Premier Holiday Management & Tour Operator
        </div>

        <!-- Headline -->
        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight max-w-4xl mx-auto leading-tight sm:leading-none mb-6">
            Explore Incredible India & <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-emerald-300 to-amber-300">The Wonders Beyond</span>
        </h1>
        <p class="text-sm sm:text-base text-slate-300 max-w-2xl mx-auto mb-10 leading-relaxed font-light">
            Curated domestic escapes, Kashmir houseboats, Dubai desert safaris, Bali private villas, and personalized holiday itineraries with verified hotels and 24x7 support.
        </p>

        <!-- Travel Search Engine Widget -->
        <div class="bg-white/95 backdrop-blur-md p-4 sm:p-5 rounded-3xl shadow-2xl border border-white/20 text-slate-800 max-w-4xl mx-auto text-left">
            <form action="{{ route('packages.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Destination -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1 flex items-center gap-1">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-teal-600"></i> Where to?
                    </label>
                    <input type="text" name="search" placeholder="Kashmir, Dubai, Bali..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-semibold focus:outline-hidden focus:border-teal-500">
                </div>

                <!-- Tour Type -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1 flex items-center gap-1">
                        <i data-lucide="globe" class="w-3.5 h-3.5 text-teal-600"></i> Tour Scope
                    </label>
                    <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-semibold focus:outline-hidden focus:border-teal-500">
                        <option value="">All Destinations</option>
                        <option value="domestic">Domestic (India)</option>
                        <option value="international">International</option>
                    </select>
                </div>

                <!-- Budget -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1 flex items-center gap-1">
                        <i data-lucide="indian-rupee" class="w-3.5 h-3.5 text-teal-600"></i> Max Budget
                    </label>
                    <select name="budget" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-semibold focus:outline-hidden focus:border-teal-500">
                        <option value="">Any Budget</option>
                        <option value="25000">Under ₹25,000</option>
                        <option value="50000">Under ₹50,000</option>
                        <option value="100000">Under ₹1,00,000</option>
                    </select>
                </div>

                <!-- Search Action -->
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs py-3 px-4 rounded-xl shadow-md transition flex items-center justify-center gap-2">
                        <i data-lucide="search" class="w-4 h-4"></i> Search Packages
                    </button>
                </div>

            </form>
        </div>

    </div>
</section>

<!-- Quick Categories Strip -->
<section class="py-8 bg-white border-b border-slate-100 shadow-2xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 overflow-x-auto pb-2 scrollbar-none">
            @foreach($categories as $cat)
                <a href="{{ route('packages.index', ['category' => $cat->slug]) }}" class="flex items-center gap-2.5 bg-slate-50 hover:bg-teal-50 hover:border-teal-300 border border-slate-200 px-4 py-2.5 rounded-2xl shrink-0 transition group">
                    <div class="w-7 h-7 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center group-hover:bg-teal-600 group-hover:text-white transition">
                        <i data-lucide="{{ $cat->icon ?: 'compass' }}" class="w-4 h-4"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-700 group-hover:text-teal-900">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Packages Section -->
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-widest text-teal-600 block mb-1">Handcrafted Journeys</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Trending Holiday Packages</h2>
            </div>
            <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-700 hover:text-teal-800 transition">
                Browse All Packages <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredPackages as $pkg)
                <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col group">
                    <!-- Image Box -->
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ $pkg->featured_image ?: 'https://images.unsplash.com/photo-1595815771614-ade9d652a65d?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $pkg->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute top-3 left-3 flex gap-2">
                            <span class="bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                {{ $pkg->duration_days }}D / {{ $pkg->duration_nights }}N
                            </span>
                            @if($pkg->type === 'international')
                                <span class="bg-teal-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">International</span>
                            @else
                                <span class="bg-amber-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Domestic</span>
                            @endif
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-2 font-medium">
                                <span class="flex items-center gap-1 text-teal-700 font-semibold">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> {{ $pkg->destination ? $pkg->destination->name : 'India' }}
                                </span>
                                <span class="flex items-center gap-1 text-amber-500 font-bold">
                                    <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400"></i> 4.9 (48)
                                </span>
                            </div>

                            <h3 class="font-bold text-slate-900 text-base line-clamp-2 mb-3 leading-snug group-hover:text-teal-600 transition">
                                <a href="{{ route('packages.show', $pkg->slug) }}">{{ $pkg->title }}</a>
                            </h3>

                            @if(!empty($pkg->highlights))
                                <ul class="space-y-1 mb-4 text-xs text-slate-600">
                                    @foreach(array_slice($pkg->highlights, 0, 2) as $hl)
                                        <li class="flex items-start gap-1.5">
                                            <i data-lucide="check" class="w-3.5 h-3.5 text-teal-600 shrink-0 mt-0.5"></i>
                                            <span class="line-clamp-1">{{ $hl }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <!-- Price & CTA -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-semibold">Starting From</span>
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-lg font-extrabold text-slate-900">₹{{ number_format($pkg->discount_price ?: $pkg->adult_price) }}</span>
                                    @if($pkg->discount_price)
                                        <span class="text-xs text-slate-400 line-through">₹{{ number_format($pkg->starting_price) }}</span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('packages.show', $pkg->slug) }}" class="bg-slate-900 hover:bg-teal-600 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition shadow-xs">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>

<!-- Popular Destinations Grid -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-extrabold uppercase tracking-widest text-teal-600 block mb-1">Top Travel Hubs</span>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Popular Destinations</h2>
            <p class="text-xs text-slate-500 mt-2">Explore the most sought-after holiday getaways with verified local ground transport and sightseeing.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($popularDestinations as $dest)
                <a href="{{ route('destinations.show', $dest->slug) }}" class="relative rounded-3xl overflow-hidden h-72 group shadow-sm hover:shadow-xl transition duration-300">
                    <img src="{{ $dest->banner_image }}" alt="{{ $dest->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/30 to-transparent"></div>
                    <div class="absolute bottom-5 left-5 right-5 text-white">
                        <span class="text-[10px] uppercase font-bold tracking-widest text-teal-300 block mb-1">{{ $dest->type === 'international' ? 'International' : 'Domestic' }}</span>
                        <h3 class="text-base font-extrabold text-white leading-tight mb-1">{{ $dest->name }}</h3>
                        <p class="text-[11px] text-slate-300 line-clamp-1">{{ $dest->best_time_to_visit }}</p>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
</section>

<!-- Services Showcase -->
<section class="py-16 bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-extrabold uppercase tracking-widest text-teal-400 block mb-1">Comprehensive Assistance</span>
            <h2 class="text-3xl font-extrabold text-white tracking-tight">Full-Spectrum Travel Services</h2>
            <p class="text-xs text-slate-400 mt-2">From rapid e-visa vetting to dedicated flight booking desks and group rail allocations.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Visa -->
            <a href="{{ route('services.visa') }}" class="p-6 rounded-2xl bg-slate-800/80 border border-slate-700 hover:border-teal-500 transition group block">
                <div class="w-10 h-10 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center mb-4 group-hover:bg-teal-500 group-hover:text-slate-950 transition">
                    <i data-lucide="stamp" class="w-5 h-5"></i>
                </div>
                <h3 class="text-base font-bold text-white mb-2">Visa Application Assistance</h3>
                <p class="text-xs text-slate-400 leading-relaxed mb-4">Complete document verification, checklist clearance, and expedited filing for Dubai, Schengen, Thailand, and UK visas.</p>
                <span class="text-xs font-bold text-teal-400 group-hover:underline flex items-center gap-1">Apply Online &rarr;</span>
            </a>

            <!-- Passport -->
            <a href="{{ route('services.passport') }}" class="p-6 rounded-2xl bg-slate-800/80 border border-slate-700 hover:border-teal-500 transition group block">
                <div class="w-10 h-10 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center mb-4 group-hover:bg-teal-500 group-hover:text-slate-950 transition">
                    <i data-lucide="file-badge" class="w-5 h-5"></i>
                </div>
                <h3 class="text-base font-bold text-white mb-2">Passport Seva Assistance</h3>
                <p class="text-xs text-slate-400 leading-relaxed mb-4">Form preparation, document vetting, and priority slot booking assistance for Fresh, Renewal, and Tatkal passports.</p>
                <span class="text-xs font-bold text-teal-400 group-hover:underline flex items-center gap-1">Book Consultation &rarr;</span>
            </a>

            <!-- Flights -->
            <a href="{{ route('services.flights') }}" class="p-6 rounded-2xl bg-slate-800/80 border border-slate-700 hover:border-teal-500 transition group block">
                <div class="w-10 h-10 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center mb-4 group-hover:bg-teal-500 group-hover:text-slate-950 transition">
                    <i data-lucide="plane" class="w-5 h-5"></i>
                </div>
                <h3 class="text-base font-bold text-white mb-2">Flight Reservations Desk</h3>
                <p class="text-xs text-slate-400 leading-relaxed mb-4">Contracted corporate and leisure airfares with baggage allowances, seat selection, and flexible rescheduling.</p>
                <span class="text-xs font-bold text-teal-400 group-hover:underline flex items-center gap-1">Enquire Flights &rarr;</span>
            </a>

            <!-- Hotels -->
            <a href="{{ route('services.hotels') }}" class="p-6 rounded-2xl bg-slate-800/80 border border-slate-700 hover:border-teal-500 transition group block">
                <div class="w-10 h-10 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center mb-4 group-hover:bg-teal-500 group-hover:text-slate-950 transition">
                    <i data-lucide="hotel" class="w-5 h-5"></i>
                </div>
                <h3 class="text-base font-bold text-white mb-2">Hotels & Heritage Resorts</h3>
                <p class="text-xs text-slate-400 leading-relaxed mb-4">Direct contracted tariffs with verified properties, Dal Lake houseboats, private pool villas, and beachfront stays.</p>
                <span class="text-xs font-bold text-teal-400 group-hover:underline flex items-center gap-1">Reserve Hotel &rarr;</span>
            </a>

            <!-- Custom Tour Planner -->
            <a href="{{ route('services.custom_tour') }}" class="p-6 rounded-2xl bg-gradient-to-br from-teal-900/60 to-slate-800 border border-teal-500/40 hover:border-teal-400 transition group block">
                <div class="w-10 h-10 rounded-xl bg-teal-500 text-slate-950 flex items-center justify-center mb-4 font-bold">
                    <i data-lucide="sparkles" class="w-5 h-5"></i>
                </div>
                <h3 class="text-base font-bold text-white mb-2">Custom Holiday Planner</h3>
                <p class="text-xs text-slate-300 leading-relaxed mb-4">Tailor your exact travel dates, preferred vehicle category, hotel stars, and private activities with a dedicated holiday architect.</p>
                <span class="text-xs font-bold text-teal-300 group-hover:underline flex items-center gap-1">Design Custom Tour &rarr;</span>
            </a>

            <!-- Trains & Buses -->
            <a href="{{ route('services.trains') }}" class="p-6 rounded-2xl bg-slate-800/80 border border-slate-700 hover:border-teal-500 transition group block">
                <div class="w-10 h-10 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center mb-4 group-hover:bg-teal-500 group-hover:text-slate-950 transition">
                    <i data-lucide="train" class="w-5 h-5"></i>
                </div>
                <h3 class="text-base font-bold text-white mb-2">Rail & Luxury Bus Tickets</h3>
                <p class="text-xs text-slate-400 leading-relaxed mb-4">Assisted IRCTC train itinerary routing, Tatkal guidance, and premium multi-axle Volvo AC sleeper reservations.</p>
                <span class="text-xs font-bold text-teal-400 group-hover:underline flex items-center gap-1">Enquire Surface Transport &rarr;</span>
            </a>
        </div>

    </div>
</section>

<!-- Why Choose Us -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center p-6 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="w-12 h-12 mx-auto rounded-full bg-teal-100 text-teal-700 flex items-center justify-center mb-4">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-900 mb-2">100% Verified Partners</h4>
                <p class="text-xs text-slate-500">Every hotel, cab driver, and houseboat is personally audited for safety and hygiene.</p>
            </div>
            <div class="text-center p-6 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="w-12 h-12 mx-auto rounded-full bg-teal-100 text-teal-700 flex items-center justify-center mb-4">
                    <i data-lucide="headset" class="w-6 h-6"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-900 mb-2">24x7 Guest Helpline</h4>
                <p class="text-xs text-slate-500">Our dedicated tour manager is available on call and WhatsApp throughout your trip.</p>
            </div>
            <div class="text-center p-6 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="w-12 h-12 mx-auto rounded-full bg-teal-100 text-teal-700 flex items-center justify-center mb-4">
                    <i data-lucide="credit-card" class="w-6 h-6"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-900 mb-2">25% Advance Booking</h4>
                <p class="text-xs text-slate-500">Book your dream holiday today with just 25% down payment. Balance payable before departure.</p>
            </div>
            <div class="text-center p-6 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="w-12 h-12 mx-auto rounded-full bg-teal-100 text-teal-700 flex items-center justify-center mb-4">
                    <i data-lucide="receipt" class="w-6 h-6"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-900 mb-2">GST Compliant Billing</h4>
                <p class="text-xs text-slate-500">Official tax invoices with SAC 9985 code for business claims and complete accounting peace of mind.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-16 bg-slate-50 border-t border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-extrabold uppercase tracking-widest text-teal-600 block mb-1">Guest Experiences</span>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Real Reviews from Real Travelers</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            @foreach($testimonials as $test)
                <div class="bg-white p-7 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1 text-amber-400 mb-4">
                            @for($i = 0; $i < $test->rating; $i++)
                                <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                            @endfor
                        </div>
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed italic mb-6">"{{ $test->content }}"</p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <img src="{{ $test->avatar }}" alt="{{ $test->name }}" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">{{ $test->name }}</h4>
                            <p class="text-[11px] text-slate-500">{{ $test->designation_or_city }} &bull; Visited <span class="text-teal-700 font-semibold">{{ $test->destination_visited }}</span></p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>

<!-- Travel Blogs & Guides -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-widest text-teal-600 block mb-1">Inspirational Guides</span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Latest Travel Stories</h2>
            </div>
            <a href="{{ route('blogs.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-teal-700 hover:text-teal-800 transition">
                Read All Guides <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($latestBlogs as $blog)
                <a href="{{ route('blogs.show', $blog->slug) }}" class="group block rounded-3xl overflow-hidden bg-slate-50 border border-slate-200/80 shadow-xs hover:shadow-lg transition">
                    <div class="h-48 overflow-hidden relative">
                        <img src="{{ $blog->image }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-slate-900 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">
                            {{ $blog->category }}
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="text-[11px] text-slate-400 font-medium mb-2">{{ $blog->published_at ? $blog->published_at->format('d M Y') : date('d M Y') }} &bull; By {{ $blog->author }}</div>
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-teal-600 transition line-clamp-2 mb-2 leading-snug">{{ $blog->title }}</h3>
                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ $blog->summary }}</p>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
</section>

<!-- FAQs Section -->
<section class="py-16 bg-slate-50 border-t border-slate-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <span class="text-xs font-extrabold uppercase tracking-widest text-teal-600 block mb-1">Clear Answers</span>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Frequently Asked Questions</h2>
        </div>

        <div class="space-y-4" x-data="{ active: null }">
            @foreach($faqs as $idx => $faq)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                    <button @click="active = active === {{ $idx }} ? null : {{ $idx }}" class="w-full text-left p-5 flex items-center justify-between font-bold text-slate-900 text-xs sm:text-sm">
                        <span>{{ $faq->question }}</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="active === {{ $idx }} ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="active === {{ $idx }}" x-collapse class="px-5 pb-5 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        {{ $faq->answer }}
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>

@endsection

