@extends('layouts.app')

@section('title', $package->seo_title ?: $package->title . ' | BharatTravel')
@section('meta_description', $package->meta_description ?: 'Book ' . $package->title . ' with verified hotels, transfers and breakfast.')

@section('content')
<!-- Breadcrumbs & Quick Bar -->
<div class="bg-white border-b border-slate-200 py-3.5 text-xs text-slate-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-teal-600">Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('packages.index') }}" class="hover:text-teal-600">Packages</a>
            <span>&rsaquo;</span>
            <a href="{{ route('packages.index', ['type' => $package->type]) }}" class="hover:text-teal-600 capitalize">{{ $package->type }}</a>
            <span>&rsaquo;</span>
            <span class="text-slate-900 font-bold truncate max-w-[200px] sm:max-w-md">{{ $package->title }}</span>
        </div>
        <div class="hidden sm:flex items-center gap-3">
            <span class="text-slate-400">Package Code: <strong class="text-slate-800">{{ $package->package_code }}</strong></span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Title Header -->
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
        <div>
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <span class="bg-teal-100 text-teal-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase">
                    {{ $package->duration_days }} Days / {{ $package->duration_nights }} Nights
                </span>
                @if($package->category)
                    <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full">
                        {{ $package->category->name }}
                    </span>
                @endif
                <span class="bg-slate-100 text-slate-700 text-[10px] font-semibold px-2.5 py-0.5 rounded-full flex items-center gap-1">
                    <i data-lucide="map-pin" class="w-3 h-3 text-teal-600"></i> {{ $package->destination ? $package->destination->name : 'India' }}
                </span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">{{ $package->title }}</h1>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <!-- Wishlist Button -->
            <button id="wishlist-btn" data-id="{{ $package->id }}" class="flex items-center gap-2 border border-slate-300 hover:border-rose-400 px-4 py-2.5 rounded-2xl text-xs font-bold transition shadow-xs {{ $isWishlisted ? 'text-rose-600 bg-rose-50' : 'text-slate-700 bg-white' }}">
                <i data-lucide="heart" class="w-4 h-4 {{ $isWishlisted ? 'fill-rose-500' : '' }}"></i>
                <span id="wishlist-text">{{ $isWishlisted ? 'Saved to Wishlist' : 'Add to Wishlist' }}</span>
            </button>

            <!-- WhatsApp Share -->
            <a href="https://wa.me/?text={{ urlencode('Check out this tour package: ' . $package->title . ' - ' . url()->current()) }}" target="_blank" class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-2xl text-xs font-bold transition shadow-xs">
                <i data-lucide="share-2" class="w-4 h-4"></i> Share
            </a>
        </div>
    </div>

    <!-- Gallery Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10 rounded-3xl overflow-hidden">
        <div class="md:col-span-2 h-[340px] sm:h-[420px] overflow-hidden">
            <img src="{{ $package->featured_image }}" alt="{{ $package->title }}" class="w-full h-full object-cover">
        </div>
        <div class="hidden md:flex flex-col gap-4 h-[420px]">
            @if(!empty($package->gallery) && count($package->gallery) > 1)
                @foreach(array_slice($package->gallery, 1, 2) as $img)
                    <div class="h-1/2 overflow-hidden rounded-2xl">
                        <img src="{{ $img }}" alt="{{ $package->title }}" class="w-full h-full object-cover">
                    </div>
                @endforeach
            @else
                <div class="h-1/2 overflow-hidden rounded-2xl">
                    <img src="{{ $package->destination ? $package->destination->banner_image : $package->featured_image }}" class="w-full h-full object-cover">
                </div>
                <div class="h-1/2 overflow-hidden rounded-2xl">
                    <img src="https://images.unsplash.com/photo-1566837945700-30057527ade0?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover">
                </div>
            @endif
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        <!-- Left: Tour Details & Itinerary -->
        <div class="lg:col-span-2 space-y-10">
            
            <!-- Key Highlights Box -->
            @if(!empty($package->highlights))
                <div class="bg-teal-50/60 rounded-3xl p-6 sm:p-8 border border-teal-100 shadow-xs">
                    <h3 class="text-base font-extrabold text-teal-950 mb-4 flex items-center gap-2">
                        <i data-lucide="sparkles" class="w-5 h-5 text-teal-600"></i> Tour Highlights & Inclusions
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-slate-700">
                        @foreach($package->highlights as $hl)
                            <div class="flex items-start gap-2 bg-white/80 p-3 rounded-xl border border-teal-200/50">
                                <i data-lucide="check-circle" class="w-4 h-4 text-teal-600 shrink-0 mt-0.5"></i>
                                <span class="font-medium leading-relaxed">{{ $hl }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Day-Wise Itinerary -->
            <div>
                <h3 class="text-xl font-bold text-slate-900 tracking-tight mb-6 flex items-center gap-2">
                    <i data-lucide="clock" class="w-5 h-5 text-teal-600"></i> Detailed Day-Wise Itinerary
                </h3>

                <div class="space-y-4" x-data="{ openDay: 1 }">
                    @forelse($package->itineraries as $iti)
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                            <button @click="openDay = openDay === {{ $iti->day_number }} ? null : {{ $iti->day_number }}" class="w-full text-left p-5 flex items-center justify-between text-xs sm:text-sm font-bold text-slate-900 hover:bg-slate-50 transition">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-full bg-teal-600 text-white flex items-center justify-center font-extrabold text-xs shrink-0">
                                        D{{ $iti->day_number }}
                                    </span>
                                    <span>Day {{ $iti->day_number }}: {{ $iti->title }}</span>
                                </div>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="openDay === {{ $iti->day_number }} ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="openDay === {{ $iti->day_number }}" x-collapse class="px-5 pb-6 pt-2 border-t border-slate-100 text-xs text-slate-600 space-y-3">
                                <p class="leading-relaxed">{{ $iti->description }}</p>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 pt-2 border-t border-slate-100 text-[11px]">
                                    @if($iti->hotel_info)
                                        <div class="flex items-center gap-1.5 text-slate-600">
                                            <i data-lucide="hotel" class="w-3.5 h-3.5 text-teal-600"></i>
                                            <span><strong>Stay:</strong> {{ $iti->hotel_info }}</span>
                                        </div>
                                    @endif
                                    @if($iti->meals_info)
                                        <div class="flex items-center gap-1.5 text-slate-600">
                                            <i data-lucide="utensils" class="w-3.5 h-3.5 text-amber-600"></i>
                                            <span><strong>Meals:</strong> {{ $iti->meals_info }}</span>
                                        </div>
                                    @endif
                                    @if($iti->transport_info)
                                        <div class="flex items-center gap-1.5 text-slate-600">
                                            <i data-lucide="car" class="w-3.5 h-3.5 text-teal-600"></i>
                                            <span><strong>Cab:</strong> {{ $iti->transport_info }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl p-6 text-center text-xs text-slate-500 border border-slate-200">
                            Custom itinerary is being finalized. Call our support desk for the complete PDF program.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Inclusions & Exclusions Accordion/Tabs -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs" x-data="{ tab: 'inclusions' }">
                <div class="flex items-center gap-4 border-b border-slate-200 pb-3 mb-6">
                    <button @click="tab = 'inclusions'" :class="tab === 'inclusions' ? 'border-teal-600 text-teal-700 font-extrabold' : 'border-transparent text-slate-500'" class="border-b-2 py-2 text-xs transition">
                        What's Included
                    </button>
                    <button @click="tab = 'exclusions'" :class="tab === 'exclusions' ? 'border-teal-600 text-teal-700 font-extrabold' : 'border-transparent text-slate-500'" class="border-b-2 py-2 text-xs transition">
                        What's Excluded
                    </button>
                    <button @click="tab = 'policies'" :class="tab === 'policies' ? 'border-teal-600 text-teal-700 font-extrabold' : 'border-transparent text-slate-500'" class="border-b-2 py-2 text-xs transition">
                        Terms & Cancellation
                    </button>
                </div>

                <!-- Inclusions -->
                <div x-show="tab === 'inclusions'">
                    @if(!empty($package->inclusions))
                        <ul class="space-y-2 text-xs text-slate-700">
                            @foreach($package->inclusions as $inc)
                                <li class="flex items-start gap-2">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i>
                                    <span>{{ $inc }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-xs text-slate-500">Accommodation in verified hotels, daily breakfast and dinners, private dedicated AC transfers and sightseeing as per itinerary.</p>
                    @endif
                </div>

                <!-- Exclusions -->
                <div x-show="tab === 'exclusions'">
                    @if(!empty($package->exclusions))
                        <ul class="space-y-2 text-xs text-slate-700">
                            @foreach($package->exclusions as $exc)
                                <li class="flex items-start gap-2">
                                    <i data-lucide="x" class="w-4 h-4 text-rose-500 shrink-0 mt-0.5"></i>
                                    <span>{{ $exc }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-xs text-slate-500">Airfare, monument entry fees, personal laundry, shopping expenses and GST.</p>
                    @endif
                </div>

                <!-- Policies -->
                <div x-show="tab === 'policies'" class="text-xs text-slate-600 space-y-4">
                    <div>
                        <h4 class="font-bold text-slate-900 mb-1">Booking Terms</h4>
                        <p>{{ $package->terms_conditions ?: '25% advance payable upon confirmation. Balance due 14 days before journey. Photo ID required at all hotels.' }}</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 mb-1">Cancellation Policy</h4>
                        <p>{{ $package->cancellation_policy ?: 'Free cancellation up to 30 days prior. 50% refund between 15-29 days. Non-refundable within 14 days of departure.' }}</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Sticky Booking Box -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-xl sticky top-24 space-y-5">
                
                <!-- Price Banner -->
                <div class="pb-5 border-b border-slate-100">
                    <span class="text-[10px] text-slate-400 uppercase tracking-widest font-bold block mb-1">Guaranteed Best Price</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-slate-900">₹{{ number_format($package->discount_price ?: $package->adult_price) }}</span>
                        @if($package->discount_price)
                            <span class="text-xs text-slate-400 line-through">₹{{ number_format($package->starting_price) }}</span>
                        @endif
                        <span class="text-[11px] text-slate-500">/ per adult</span>
                    </div>
                    <div class="mt-2 text-[11px] text-emerald-600 font-bold flex items-center gap-1">
                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> 25% Advance booking enabled (Pay just ₹{{ number_format(($package->discount_price ?: $package->adult_price) * 0.25) }} today)
                    </div>
                </div>

                <!-- Quick Attributes -->
                <div class="space-y-2.5 text-xs text-slate-600">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Duration:</span>
                        <span class="font-bold text-slate-800">{{ $package->duration_days }} Days / {{ $package->duration_nights }} Nights</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Pickup / Drop:</span>
                        <span class="font-bold text-slate-800">{{ $package->departure_city ?: 'Airport / Railway Station' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Child Rate:</span>
                        <span class="font-bold text-slate-800">₹{{ number_format($package->child_price) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">GST:</span>
                        <span class="font-bold text-slate-800">5% Extra (SAC 9985)</span>
                    </div>
                </div>

                <!-- Next Available Departures -->
                @if($package->departures->count())
                    <div class="pt-3 border-t border-slate-100">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Upcoming Departures</label>
                        <div class="space-y-1.5 text-xs">
                            @foreach($package->departures->take(3) as $dep)
                                <div class="flex items-center justify-between bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                                    <span class="font-semibold text-slate-800">{{ $dep->departure_date->format('d M Y') }}</span>
                                    <span class="text-[10px] bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full font-bold">{{ $dep->available_seats }} seats left</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Book Now CTA -->
                <div class="pt-2">
                    <a href="{{ route('packages.book', $package->slug) }}" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-sm py-3.5 rounded-2xl transition shadow-md flex items-center justify-center gap-2">
                        <i data-lucide="calendar" class="w-4 h-4"></i> Book Tour Package Now
                    </a>
                </div>

                <div class="text-center">
                    <span class="text-[11px] text-slate-400 block mb-1">Need customized changes to this package?</span>
                    <a href="{{ route('services.custom_tour') }}" class="text-xs font-bold text-amber-600 hover:underline">
                        Request Custom Itinerary &rarr;
                    </a>
                </div>

            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('wishlist-btn');
        if (btn) {
            btn.addEventListener('click', async () => {
                const packageId = btn.getAttribute('data-id');
                const text = document.getElementById('wishlist-text');
                try {
                    const res = await fetch("{{ route('packages.wishlist.toggle') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
                        },
                        body: JSON.stringify({ package_id: packageId })
                    });
                    const data = await res.json();
                    if (data.success) {
                        if (data.saved) {
                            btn.classList.add('text-rose-600', 'bg-rose-50');
                            btn.classList.remove('text-slate-700', 'bg-white');
                            text.textContent = 'Saved to Wishlist';
                        } else {
                            btn.classList.remove('text-rose-600', 'bg-rose-50');
                            btn.classList.add('text-slate-700', 'bg-white');
                            text.textContent = 'Add to Wishlist';
                        }
                    } else {
                        window.location.href = "{{ route('login') }}";
                    }
                } catch(e) {
                    alert('Please login to manage your wishlist.');
                }
            });
        }
    });
</script>
@endpush

@endsection

