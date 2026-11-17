@extends('layouts.customer')

@section('title', 'My Saved Wishlist | BharatTravel')

@section('customer_content')
<div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900">My Saved Travel Wishlist</h1>
        <p class="text-xs text-slate-500 mt-1">Tour packages and destinations you've bookmarked for upcoming vacations.</p>
    </div>
    <a href="{{ route('packages.index') }}" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl transition shadow-xs">
        Browse More Packages
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($wishlists as $wish)
        @if($wish->package)
            <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-xs flex flex-col justify-between group">
                <div>
                    <div class="relative h-44 overflow-hidden">
                        <img src="{{ $wish->package->featured_image ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80' }}"
                             alt="{{ $wish->package->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-black/60 backdrop-blur-md text-white">
                            {{ $wish->package->destination?->name }}
                        </span>
                    </div>
                    <div class="p-5">
                        <p class="text-[11px] text-teal-600 font-bold uppercase">{{ $wish->package->duration_days }}D / {{ $wish->package->duration_nights }}N</p>
                        <h3 class="font-bold text-slate-900 text-sm mt-1 line-clamp-2">{{ $wish->package->title }}</h3>
                        <div class="mt-3 flex items-baseline gap-2">
                            <span class="text-xs text-slate-400">Starting from</span>
                            <span class="text-base font-extrabold text-slate-900">₹{{ number_format($wish->package->sale_price ?? $wish->package->price, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-5 pt-0 flex gap-2">
                    <a href="{{ route('packages.show', $wish->package->slug) }}" class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl text-xs text-center transition">
                        View Itinerary
                    </a>
                    <a href="{{ route('packages.book', $wish->package->slug) }}" class="flex-1 py-2 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl text-xs text-center transition">
                        Book Now
                    </a>
                </div>
            </div>
        @endif
    @empty
        <div class="col-span-full bg-white rounded-2xl p-12 text-center border border-slate-200">
            <i data-lucide="heart" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
            <h3 class="font-bold text-slate-800 text-base">Your wishlist is empty</h3>
            <p class="text-xs text-slate-500 mt-1">Explore our packages and click the heart icon on any tour to save it for later.</p>
        </div>
    @endforelse
</div>
@endsection

