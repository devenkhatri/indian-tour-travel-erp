@extends('layouts.agent')

@section('title', 'B2B Wholesale Tour Tariffs | BharatTravel')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">B2B Wholesale Tour Packages & Net Tariffs</h1>
            <p class="text-xs text-slate-500 mt-1">Exclusive rates for registered travel trade partners. Your contracted commission rate: <span class="font-bold text-teal-600">{{ $agent->commission_rate }}%</span></p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl bg-teal-50 border border-teal-200 text-teal-800 text-xs font-bold">
                Auto-Credit Commission on Confirmation
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($packages as $pkg)
            @php
                $retailPrice = (float) ($pkg->discount_price ?: $pkg->adult_price);
                $commAmount = round(($retailPrice * (float) $agent->commission_rate) / 100, 2);
                $netRate = $retailPrice - $commAmount;
            @endphp
            <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-xs flex flex-col justify-between group hover:shadow-lg transition-all">
                <div>
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ $pkg->featured_image ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80' }}"
                             alt="{{ $pkg->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold bg-black/60 backdrop-blur-md text-white">
                            {{ $pkg->destination?->name }}
                        </span>
                        <span class="absolute bottom-3 right-3 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-600 text-white">
                            {{ $pkg->duration_days }}D / {{ $pkg->duration_nights }}N
                        </span>
                    </div>

                    <div class="p-5">
                        <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">{{ $pkg->category?->name ?? 'Tour' }}</span>
                        <h3 class="font-bold text-slate-900 text-base mt-1 line-clamp-2">{{ $pkg->title }}</h3>

                        <!-- Pricing Breakdown -->
                        <div class="mt-4 p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-1.5 text-xs">
                            <div class="flex justify-between text-slate-500">
                                <span>Published MRP:</span>
                                <span class="line-through">₹{{ number_format($retailPrice, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-teal-700">
                                <span>Your Commission ({{ $agent->commission_rate }}%):</span>
                                <span>+ ₹{{ number_format($commAmount, 2) }}</span>
                            </div>
                            <div class="pt-1.5 border-t border-slate-200 flex justify-between font-extrabold text-slate-900 text-sm">
                                <span>Effective Net Cost:</span>
                                <span class="text-emerald-700">₹{{ number_format($netRate, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-5 pt-0">
                    <a href="{{ route('agent.bookings.create', $pkg->id) }}" class="block w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs text-center rounded-xl transition shadow-xs">
                        Book For Client &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-12 text-center border border-slate-200">
                <p class="text-slate-400">No active packages in catalogue.</p>
            </div>
        @endforelse
    </div>

    @if($packages->hasPages())
        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs">
            {{ $packages->links() }}
        </div>
    @endif
</div>
@endsection

