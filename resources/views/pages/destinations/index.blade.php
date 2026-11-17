@extends('layouts.app')

@section('title', 'Holiday Destinations | BharatTravel')

@section('content')
<div class="bg-slate-900 text-white py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-xs font-bold uppercase tracking-widest text-teal-400 block mb-2">Explore the World</span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">Holiday Destinations</h1>
        <p class="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto mt-3">From the snows of Kashmir and valleys of Himachal to the glittering skylines of Dubai and beaches of Bali.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs mb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('destinations.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ !request('type') ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">All Destinations</a>
            <a href="{{ route('destinations.index', ['type' => 'domestic']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('type') === 'domestic' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Domestic (India)</a>
            <a href="{{ route('destinations.index', ['type' => 'international']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('type') === 'international' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">International</a>
        </div>
        
        <form action="{{ route('destinations.index') }}" method="GET" class="w-full sm:w-72">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search destination..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-hidden focus:border-teal-500">
        </form>
    </div>

    <!-- Destinations Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($destinations as $dest)
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-xs hover:shadow-xl transition duration-300 flex flex-col group">
                <div class="relative h-60 overflow-hidden">
                    <img src="{{ $dest->banner_image }}" alt="{{ $dest->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/20 to-transparent"></div>
                    <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-md text-slate-900 text-[10px] font-bold px-3 py-1 rounded-full uppercase">
                        {{ $dest->type === 'international' ? 'International' : 'Domestic' }}
                    </span>
                    <div class="absolute bottom-4 left-4 right-4 text-white">
                        <h3 class="text-lg font-bold leading-tight">{{ $dest->name }}</h3>
                        <p class="text-[11px] text-teal-300">{{ $dest->best_time_to_visit }}</p>
                    </div>
                </div>

                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <p class="text-xs text-slate-600 line-clamp-3 mb-4 leading-relaxed">{{ $dest->description }}</p>

                        @if(!empty($dest->popular_attractions))
                            <div class="mb-4">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Top Attractions:</span>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach(array_slice($dest->popular_attractions, 0, 3) as $att)
                                        <span class="bg-slate-100 text-slate-700 text-[10px] font-medium px-2 py-0.5 rounded-md">{{ $att }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('destinations.show', $dest->slug) }}" class="w-full text-center bg-slate-900 hover:bg-teal-600 text-white font-bold text-xs py-2.5 rounded-xl transition shadow-xs">
                        Explore Destination &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-slate-500 text-xs">
                No destinations matching your filter criteria.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $destinations->links() }}
    </div>
</div>
@endsection

