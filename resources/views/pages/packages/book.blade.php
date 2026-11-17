@extends('layouts.app')

@section('title', 'Book ' . $package->title . ' | BharatTravel')

@section('content')
<div class="bg-slate-900 text-white py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-xs font-bold uppercase tracking-widest text-teal-400 block mb-1">Secure Checkout</span>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Confirm Your Holiday Booking</h1>
        <p class="text-xs text-slate-300 mt-1">{{ $package->title }} &bull; {{ $package->duration_days }} Days / {{ $package->duration_nights }} Nights</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <form action="{{ route('packages.book.submit', $package->slug) }}" method="POST" id="booking-form" class="space-y-8" x-data="{
        adults: 2,
        children: 0,
        adultPrice: {{ $package->discount_price ?: $package->adult_price }},
        childPrice: {{ $package->child_price }},
        couponDiscount: 0,
        couponMessage: '',
        couponApplied: false,
        subtotal() {
            return (this.adults * this.adultPrice) + (this.children * this.childPrice);
        },
        taxable() {
            return Math.max(0, this.subtotal() - this.couponDiscount);
        },
        tax() {
            return Math.round(this.taxable() * 0.05 * 100) / 100;
        },
        total() {
            return Math.round((this.taxable() + this.tax()) * 100) / 100;
        },
        advance() {
            return Math.round(this.total() * 0.25 * 100) / 100;
        }
    }">
        @csrf

        <!-- Guest Contact Information -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-3">
                <i data-lucide="user" class="w-4 h-4 text-teal-600"></i> Primary Guest Contact
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Full Name *</label>
                    <input type="text" name="customer_name" required value="{{ old('customer_name', $user ? $user->name : '') }}" placeholder="e.g. Rahul Sharma" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-hidden focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Mobile Number *</label>
                    <input type="tel" name="customer_phone" required value="{{ old('customer_phone', $user ? $user->phone : '') }}" placeholder="e.g. +91 98765 43210" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-hidden focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Address *</label>
                    <input type="email" name="customer_email" required value="{{ old('customer_email', $user ? $user->email : '') }}" placeholder="e.g. rahul@example.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-hidden focus:border-teal-500">
                </div>
            </div>
        </div>

        <!-- Travel Dates & Passenger Counts -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-3">
                <i data-lucide="calendar" class="w-4 h-4 text-teal-600"></i> Travel Date & Passengers
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Departure Date *</label>
                    <input type="date" name="departure_date" required min="{{ date('Y-m-d') }}" value="{{ $package->departures->first() ? $package->departures->first()->departure_date->format('Y-m-d') : date('Y-m-d', strtotime('+7 days')) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-hidden focus:border-teal-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Number of Adults (12+ yrs) *</label>
                    <select name="adults" x-model.number="adults" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-hidden focus:border-teal-500 font-bold">
                        @for($i=1; $i<=10; $i++)
                            <option value="{{ $i }}">{{ $i }} Adult{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Children (2-11 yrs)</label>
                    <select name="children" x-model.number="children" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-hidden focus:border-teal-500 font-bold">
                        @for($i=0; $i<=6; $i++)
                            <option value="{{ $i }}">{{ $i }} Child{{ $i != 1 ? 'ren' : '' }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Special Preferences or Requests (Optional)</label>
                <textarea name="special_requests" rows="2" placeholder="e.g. Jain food preferences, room with balcony, pickup from railway station..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-900 focus:outline-hidden focus:border-teal-500"></textarea>
            </div>
        </div>

        <!-- Promo Code & Price Calculation -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-3">
                <i data-lucide="tag" class="w-4 h-4 text-teal-600"></i> Promo Code & Fare Breakdown
            </h3>

            <!-- Promo Code Input -->
            <div class="flex flex-col sm:flex-row gap-2 max-w-md">
                <input type="text" id="coupon-input" name="coupon_code" placeholder="Enter coupon (e.g. BHARAT5000)" class="flex-1 uppercase bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-bold focus:outline-hidden focus:border-teal-500">
                <button type="button" @click="
                    const code = document.getElementById('coupon-input').value;
                    if(!code) return;
                    fetch('{{ route('packages.coupon.check') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                        },
                        body: JSON.stringify({ code: code, amount: subtotal() })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.valid) {
                            couponDiscount = data.discount;
                            couponApplied = true;
                            couponMessage = data.message;
                        } else {
                            couponDiscount = 0;
                            couponApplied = false;
                            couponMessage = data.message;
                        }
                    });
                " class="bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs px-4 py-2 rounded-xl transition">
                    Apply Code
                </button>
            </div>

            <div x-show="couponMessage" :class="couponApplied ? 'text-emerald-600' : 'text-rose-600'" class="text-xs font-semibold" x-text="couponMessage"></div>

            <!-- Pricing Summary Table -->
            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/80 space-y-2 text-xs">
                <div class="flex justify-between text-slate-600">
                    <span>Base Package Fare (<span x-text="adults"></span> Adults, <span x-text="children"></span> Children):</span>
                    <span class="font-semibold text-slate-800">₹<span x-text="subtotal().toLocaleString('en-IN')"></span></span>
                </div>
                <div class="flex justify-between text-rose-600 font-semibold" x-show="couponDiscount > 0">
                    <span>Promotional Discount:</span>
                    <span>-₹<span x-text="couponDiscount.toLocaleString('en-IN')"></span></span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>GST (5% on SAC 9985):</span>
                    <span class="font-semibold text-slate-800">₹<span x-text="tax().toLocaleString('en-IN')"></span></span>
                </div>
                <div class="pt-2 border-t border-slate-200 flex justify-between text-sm font-extrabold text-slate-900">
                    <span>Total Package Payable:</span>
                    <span class="text-teal-700">₹<span x-text="total().toLocaleString('en-IN')"></span></span>
                </div>
            </div>
        </div>

        <!-- Payment Option (Advance 25% vs Pay Later) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-3">
                <i data-lucide="credit-card" class="w-4 h-4 text-teal-600"></i> Payment Schedule Option
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="p-4 rounded-2xl border-2 border-teal-500 bg-teal-50/40 cursor-pointer block">
                    <input type="radio" name="advance_payment_method" value="upi" checked class="text-teal-600 focus:ring-teal-500">
                    <span class="font-bold text-xs text-slate-900 block mt-1">Pay 25% Advance Online Now</span>
                    <span class="text-lg font-extrabold text-teal-700 block mt-1">₹<span x-text="advance().toLocaleString('en-IN')"></span></span>
                    <span class="text-[11px] text-slate-500 block mt-0.5">Instant booking confirmation voucher generated. Balance payable before departure.</span>
                </label>

                <label class="p-4 rounded-2xl border border-slate-200 hover:border-slate-300 bg-slate-50 cursor-pointer block">
                    <input type="radio" name="advance_payment_method" value="pay_later" class="text-teal-600 focus:ring-teal-500">
                    <span class="font-bold text-xs text-slate-900 block mt-1">Reserve Booking & Pay Later</span>
                    <span class="text-xs text-slate-500 block mt-1">A travel advisor will verify availability and send an official invoice with payment link.</span>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-sm py-4 rounded-2xl transition shadow-lg flex items-center justify-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i> Complete Reservation
            </button>
            <p class="text-[11px] text-slate-400 text-center mt-2">By clicking Complete Reservation, you agree to BharatTravel's terms and cancellation policy.</p>
        </div>

    </form>
</div>
@endsection

