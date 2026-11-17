@extends('layouts.app')

@section('title', 'Tour Packages & Holiday Deals | BharatTravel')

@section('content')
<!-- Header -->
<div class="bg-slate-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-xs font-bold uppercase tracking-widest text-teal-400 block mb-2">Curated Holiday Collections</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Tour Packages & Itineraries</h1>
        <p class="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto mt-2">Book complete domestic and international tours with verified hotels, transfers, sightseeing, and breakfast.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Filters Sidebar -->
        <div class="lg:col-span-1">
            <form action="{{ route('packages.index') }}" method="GET" class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-6 text-xs sticky top-24">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="font-bold text-sm text-slate-900">Filter Tours</span>
                    <a href="{{ route('packages.index') }}" class="text-[11px] text-teal-600 hover:underline font-semibold">Reset All</a>
                </div>

                <!-- Search -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-[10px]">Keyword Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Package title, city..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-hidden focus:border-teal-500">
                </div>

                <!-- Tour Type -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-[10px]">Tour Type</label>
                    <div class="space-y-1.5">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="type" value="" {{ !request('type') ? 'checked' : '' }} class="text-teal-600 focus:ring-teal-500">
                            <span>All Tours</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="type" value="domestic" {{ request('type') === 'domestic' ? 'checked' : '' }} class="text-teal-600 focus:ring-teal-500">
                            <span>Domestic (Incredible India)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="type" value="international" {{ request('type') === 'international' ? 'checked' : '' }} class="text-teal-600 focus:ring-teal-500">
                            <span>International Holidays</span>
                        </label>
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-[10px]">Theme / Category</label>
                    <select name="category" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-hidden focus:border-teal-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Duration -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-[10px]">Duration</label>
                    <select name="duration" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-hidden focus:border-teal-500">
                        <option value="">Any Duration</option>
                        <option value="3" {{ request('duration') == '3' ? 'selected' : '' }}>Weekend / Short (1-4 Days)</option>
                        <option value="7" {{ request('duration') == '7' ? 'selected' : '' }}>Standard Trip (5-7 Days)</option>
                        <option value="10" {{ request('duration') == '10' ? 'selected' : '' }}>Extended Holiday (8+ Days)</option>
                    </select>
                </div>

                <!-- Budget -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-[10px]">Maximum Budget (per adult)</label>
                    <select name="budget" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-hidden focus:border-teal-500">
                        <option value="">Any Price</option>
                        <option value="25000" {{ request('budget') == '25000' ? 'selected' : '' }}>Under ₹25,000</option>
                        <option value="50000" {{ request('budget') == '50000' ? 'selected' : '' }}>Under ₹50,000</option>
                        <option value="100000" {{ request('budget') == '100000' ? 'selected' : '' }}>Under ₹1,00,000</option>
                    </select>
                </div>

                <!-- Sort By -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-[10px]">Sort Order</label>
                    <select name="sort" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-hidden focus:border-teal-500">
                        <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>Featured / Recommended</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="duration_desc" {{ request('sort') === 'duration_desc' ? 'selected' : '' }}>Longest Duration</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs py-2.5 rounded-xl transition shadow-xs">
                    Apply Filters
                </button>
            </form>
        </div>

        <!-- Packages Grid -->
        <div class="lg:col-span-3">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($packages as $pkg)
                    <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/90 shadow-xs hover:shadow-xl transition duration-300 flex flex-col group">
                        <!-- Image Box -->
                        <div class="relative h-52 overflow-hidden">
                            <img src="{{ $pkg->featured_image }}" alt="{{ $pkg->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 left-3 flex gap-2">
                                <span class="bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">
                                    {{ $pkg->duration_days }}D / {{ $pkg->duration_nights }}N
                                </span>
                            </div>
                            <span class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-md text-slate-900 text-[10px] font-bold px-2.5 py-0.5 rounded-full">
                                {{ $pkg->type === 'international' ? 'International' : 'Domestic' }}
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-1 text-[11px] text-teal-700 font-semibold mb-1.5">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> {{ $pkg->destination ? $pkg->destination->name : 'India' }}
                                </div>
                                <h3 class="font-bold text-slate-900 text-sm line-clamp-2 mb-2 group-hover:text-teal-600 transition leading-snug">
                                    <a href="{{ route('packages.show', $pkg->slug) }}">{{ $pkg->title }}</a>
                                </h3>

                                @if(!empty($pkg->highlights))
                                    <ul class="text-[11px] text-slate-500 space-y-1 mb-4">
                                        @foreach(array_slice($pkg->highlights, 0, 2) as $hl)
                                            <li class="flex items-start gap-1.5">
                                                <i data-lucide="check" class="w-3 h-3 text-teal-600 shrink-0 mt-0.5"></i>
                                                <span class="line-clamp-1">{{ $hl }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[9px] text-slate-400 block uppercase font-bold">Starting From</span>
                                    <span class="text-base font-extrabold text-slate-900">₹{{ number_format($pkg->discount_price ?: $pkg->adult_price) }}</span>
                                </div>
                                <a href="{{ route('packages.show', $pkg->slug) }}" class="bg-slate-900 hover:bg-teal-600 text-white font-bold text-xs px-3.5 py-2 rounded-xl transition shadow-xs">
                                    View Tour
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center text-slate-500 text-xs bg-white rounded-3xl border border-slate-200">
                        No packages match your search filters. <a href="{{ route('packages.index') }}" class="text-teal-600 font-bold hover:underline">Clear filters</a> or <a href="{{ route('services.custom_tour') }}" class="text-teal-600 font-bold hover:underline">request a custom itinerary</a>.
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $packages->links() }}
            </div>
        </div>

    </div>
</div>
@endsection

