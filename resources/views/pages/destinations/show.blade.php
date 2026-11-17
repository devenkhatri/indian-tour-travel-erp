@extends('layouts.app')

@section('title', $destination->seo_title ?: $destination->name . ' Tour Packages | BharatTravel')
@section('meta_description', $destination->meta_description ?: $destination->description)

@section('content')
<!-- Destination Hero Banner -->
<div class="relative bg-slate-900 text-white min-h-[420px] flex items-end">
    <img src="{{ $destination->banner_image }}" alt="{{ $destination->name }}" class="absolute inset-0 w-full h-full object-cover opacity-45">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/50 to-transparent"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">
        <span class="inline-block bg-teal-500/30 text-teal-300 border border-teal-400/30 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest mb-3 backdrop-blur-md">
            {{ $destination->type === 'international' ? 'International Destination' : 'Incredible India &bull; ' . $destination->state }}
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-3">{{ $destination->name }}</h1>
        <div class="flex flex-wrap items-center gap-6 text-xs text-slate-300 font-medium">
            <span class="flex items-center gap-1.5"><i data-lucide="calendar" class="w-4 h-4 text-teal-400"></i> Best Time: {{ $destination->best_time_to_visit }}</span>
            <span class="flex items-center gap-1.5"><i data-lucide="sun" class="w-4 h-4 text-amber-400"></i> Weather: {{ $destination->weather_info }}</span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        <!-- Left: Destination Information & Packages -->
        <div class="lg:col-span-2 space-y-10">
            <!-- About Destination -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xs space-y-4">
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">About {{ $destination->name }}</h2>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ $destination->description }}</p>

                @if(!empty($destination->popular_attractions))
                    <div class="pt-4 border-t border-slate-100">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Popular Attractions & Sights</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($destination->popular_attractions as $att)
                                <span class="bg-teal-50 text-teal-800 border border-teal-200/60 text-xs font-semibold px-3 py-1 rounded-xl flex items-center gap-1.5">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-teal-600"></i> {{ $att }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(!empty($destination->popular_activities))
                    <div class="pt-4 border-t border-slate-100">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Top Things to Do</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($destination->popular_activities as $act)
                                <span class="bg-amber-50 text-amber-800 border border-amber-200/60 text-xs font-semibold px-3 py-1 rounded-xl flex items-center gap-1.5">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-amber-600"></i> {{ $act }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Packages in this Destination -->
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight mb-6">Available Tour Packages in {{ $destination->name }}</h2>
                <div class="space-y-6">
                    @forelse($packages as $pkg)
                        <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-xs hover:shadow-lg transition flex flex-col sm:flex-row">
                            <div class="sm:w-64 h-52 sm:h-auto overflow-hidden relative shrink-0">
                                <img src="{{ $pkg->featured_image }}" alt="{{ $pkg->title }}" class="w-full h-full object-cover">
                                <span class="absolute top-3 left-3 bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">
                                    {{ $pkg->duration_days }}D / {{ $pkg->duration_nights }}N
                                </span>
                            </div>
                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 mb-2 hover:text-teal-600 transition">
                                        <a href="{{ route('packages.show', $pkg->slug) }}">{{ $pkg->title }}</a>
                                    </h3>
                                    @if(!empty($pkg->highlights))
                                        <ul class="text-xs text-slate-600 space-y-1 mb-4">
                                            @foreach(array_slice($pkg->highlights, 0, 2) as $hl)
                                                <li class="flex items-center gap-1.5">
                                                    <i data-lucide="check" class="w-3.5 h-3.5 text-teal-600 shrink-0"></i>
                                                    <span class="line-clamp-1">{{ $hl }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] text-slate-400 block uppercase font-bold">Price Per Adult</span>
                                        <span class="text-lg font-extrabold text-slate-900">₹{{ number_format($pkg->discount_price ?: $pkg->adult_price) }}</span>
                                    </div>
                                    <a href="{{ route('packages.show', $pkg->slug) }}" class="bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition shadow-xs">
                                        View Tour Itinerary &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-3xl p-8 text-center text-slate-500 border border-slate-200 text-xs">
                            Currently planning new departure dates for this destination. <a href="{{ route('services.custom_tour') }}" class="text-teal-600 font-bold hover:underline">Request a custom quote</a>.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right: Custom Inquiry Card -->
        <div class="space-y-6">
            <div class="bg-gradient-to-br from-slate-900 to-teal-950 text-white rounded-3xl p-7 shadow-xl border border-teal-500/30 text-xs space-y-4">
                <span class="text-teal-400 text-[10px] font-bold uppercase tracking-widest block">Personalized Travel</span>
                <h3 class="text-xl font-extrabold leading-tight">Want a Customized Trip to {{ $destination->name }}?</h3>
                <p class="text-slate-300 text-xs leading-relaxed">Let our holiday specialists design an exclusive package with your preferred hotel star, private cab, and customized sightseeing itinerary.</p>
                
                <form action="{{ route('services.custom_tour.submit') }}" method="POST" class="space-y-3 pt-2">
                    @csrf
                    <input type="hidden" name="destination" value="{{ $destination->name }}">
                    <div>
                        <input type="text" name="name" required placeholder="Your Full Name" class="w-full bg-slate-800/90 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-hidden focus:border-teal-400">
                    </div>
                    <div>
                        <input type="tel" name="phone" required placeholder="Mobile Number" class="w-full bg-slate-800/90 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-hidden focus:border-teal-400">
                    </div>
                    <div>
                        <input type="email" name="email" required placeholder="Email Address" class="w-full bg-slate-800/90 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-hidden focus:border-teal-400">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-400 mb-1">Expected Travel Date</label>
                        <input type="date" name="travel_start_date" required min="{{ date('Y-m-d') }}" class="w-full bg-slate-800/90 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-hidden focus:border-teal-400">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <input type="number" name="adults" value="2" min="1" placeholder="Adults" class="w-full bg-slate-800/90 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-hidden focus:border-teal-400">
                        </div>
                        <div>
                            <input type="number" name="children" value="0" min="0" placeholder="Children" class="w-full bg-slate-800/90 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-hidden focus:border-teal-400">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs py-3 rounded-xl transition shadow-md">
                        Get Instant Free Quote
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

