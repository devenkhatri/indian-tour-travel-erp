@extends('layouts.customer')

@section('title', 'Booking ' . $booking->booking_reference . ' | BharatTravel')

@section('customer_content')
<!-- Header Bar -->
<div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('customer.bookings') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Bookings</a>
            <span class="text-slate-300">•</span>
            <span class="text-xs font-extrabold text-teal-700 bg-teal-50 px-2.5 py-0.5 rounded-full uppercase">Ref #{{ $booking->booking_reference }}</span>
        </div>
        <h1 class="text-2xl font-bold font-serif text-slate-900">{{ $booking->package ? $booking->package->title : 'Custom Vacation Package' }}</h1>
        <p class="text-xs text-slate-500 mt-1">
            Departure: <span class="font-bold text-slate-800">{{ $booking->departure_date ? \Carbon\Carbon::parse($booking->departure_date)->format('l, F d, Y') : 'Open Date' }}</span>
            &bull; {{ $booking->adults_count }} Adults {{ $booking->children_count > 0 ? ', ' . $booking->children_count . ' Children' : '' }}
        </p>
    </div>

    <!-- Quick Voucher & Invoice Download Buttons -->
    <div class="flex flex-wrap items-center gap-2">
        @if($booking->vouchers->count() > 0)
            @foreach($booking->vouchers as $voucher)
                <a href="{{ route('customer.voucher.download', $voucher->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold transition shadow-xs">
                    <i data-lucide="download" class="w-3.5 h-3.5"></i> Download Voucher (PDF)
                </a>
            @endforeach
        @endif

        @if($booking->invoices->count() > 0)
            @foreach($booking->invoices as $invoice)
                <a href="{{ route('customer.invoice.download', $invoice->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold transition shadow-xs">
                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Tax Invoice (PDF)
                </a>
            @endforeach
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Itinerary & Passengers -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Passenger Manifest Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i data-lucide="users" class="w-4 h-4 text-teal-600"></i>
                Passenger Manifest
            </h3>
            <div class="divide-y divide-slate-100 text-xs">
                @forelse($booking->passengers as $idx => $pax)
                    <div class="py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-[10px]">
                                {{ $idx + 1 }}
                            </span>
                            <div>
                                <p class="font-bold text-slate-900">{{ $pax->title }} {{ $pax->first_name }} {{ $pax->last_name }}</p>
                                <p class="text-[11px] text-slate-400">
                                    {{ $pax->gender }} &bull; Age: {{ $pax->age ?? 'N/A' }}
                                    @if($pax->passport_number) &bull; Passport: {{ $pax->passport_number }} @endif
                                </p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 uppercase">
                            {{ $pax->passenger_type }}
                        </span>
                    </div>
                @empty
                    <p class="text-slate-400 py-2">No passengers recorded yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Day-wise Itinerary -->
        @if($booking->package && $booking->package->itineraries->count() > 0)
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
                <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i data-lucide="map" class="w-4 h-4 text-teal-600"></i>
                    Confirmed Day-Wise Tour Itinerary
                </h3>
                <div class="space-y-4">
                    @foreach($booking->package->itineraries->sortBy('day_number') as $day)
                        <div class="p-4 bg-slate-50/70 rounded-xl border border-slate-200/70 text-xs">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 bg-teal-600 text-white font-bold rounded text-[10px]">Day {{ $day->day_number }}</span>
                                <h4 class="font-bold text-slate-900 text-sm">{{ $day->title }}</h4>
                            </div>
                            <p class="text-slate-600 leading-relaxed mt-2">{{ $day->description }}</p>
                            @if($day->meals_included || $day->hotel_stay)
                                <div class="mt-2.5 pt-2 border-t border-slate-200/50 flex flex-wrap gap-3 text-[11px] text-slate-500">
                                    @if($day->meals_included)
                                        <span class="flex items-center gap-1 font-medium text-emerald-700">
                                            <i data-lucide="utensils" class="w-3.5 h-3.5"></i> Meals: {{ $day->meals_included }}
                                        </span>
                                    @endif
                                    @if($day->hotel_stay)
                                        <span class="flex items-center gap-1 font-medium text-indigo-700">
                                            <i data-lucide="bed" class="w-3.5 h-3.5"></i> Stay: {{ $day->hotel_stay }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Right Column: Financial Breakdown & Payments -->
    <div class="space-y-6">
        <!-- Financial Summary -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i data-lucide="receipt" class="w-4 h-4 text-teal-600"></i>
                Billing & Payment Overview
            </h3>

            <div class="space-y-2.5 text-xs text-slate-600">
                <div class="flex justify-between">
                    <span>Base Tour Price</span>
                    <span class="font-semibold text-slate-800">₹{{ number_format($booking->base_price, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>GST (SAC 9985)</span>
                    <span class="font-semibold text-slate-800">₹{{ number_format($booking->tax_amount, 2) }}</span>
                </div>
                @if($booking->discount_amount > 0)
                    <div class="flex justify-between text-emerald-600 font-medium">
                        <span>Coupon Discount</span>
                        <span>- ₹{{ number_format($booking->discount_amount, 2) }}</span>
                    </div>
                @endif
                <div class="pt-2 border-t border-slate-200 flex justify-between font-bold text-slate-900 text-sm">
                    <span>Gross Invoice Total</span>
                    <span>₹{{ number_format($booking->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-emerald-700 font-bold">
                    <span>Total Paid to Date</span>
                    <span>₹{{ number_format($booking->paid_amount, 2) }}</span>
                </div>
                <div class="pt-2 border-t border-slate-200 flex justify-between font-extrabold text-sm {{ $booking->balance_amount > 0 ? 'text-amber-600' : 'text-slate-900' }}">
                    <span>Remaining Balance</span>
                    <span>₹{{ number_format($booking->balance_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Payments Log -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i data-lucide="credit-card" class="w-4 h-4 text-teal-600"></i>
                Payment Receipts
            </h3>
            <div class="space-y-3 text-xs">
                @forelse($booking->payments as $payment)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex justify-between items-center">
                        <div>
                            <p class="font-bold text-slate-900">₹{{ number_format($payment->amount, 2) }}</p>
                            <p class="text-[10px] text-slate-400">{{ $payment->payment_reference }} &bull; {{ $payment->payment_method }}</p>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-slate-400">No payment records yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

