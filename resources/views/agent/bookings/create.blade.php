@extends('layouts.agent')

@section('title', 'Book Tour for Client | ' . $package->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('agent.packages') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Tariffs</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-teal-600 uppercase">{{ $package->destination?->name }}</span>
            </div>
            <h1 class="text-xl font-bold font-serif text-slate-900">{{ $package->title }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">{{ $package->duration_days }} Days / {{ $package->duration_nights }} Nights &bull; Base Adult Price: ₹{{ number_format($package->discount_price ?: $package->adult_price, 2) }}</p>
        </div>
        <div class="text-right">
            <span class="text-[11px] text-slate-400 block">Your Commission</span>
            <span class="text-base font-extrabold text-teal-600">{{ $agent->commission_rate }}% Flat</span>
        </div>
    </div>

    <!-- Booking Form Card -->
    <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-xs">
        <form action="{{ route('agent.bookings.store', $package->id) }}" method="POST" class="space-y-6" id="b2bBookingForm">
            @csrf

            <!-- Client Information Section -->
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4 text-teal-600"></i> Client / Lead Passenger Details
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Client Full Name *</label>
                        <input type="text" name="client_name" required value="{{ old('client_name') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs"
                               placeholder="e.g. Sunil Verma">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Client Email Address *</label>
                        <input type="email" name="client_email" required value="{{ old('client_email') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs"
                               placeholder="sunil@example.com">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Client Phone (WhatsApp) *</label>
                        <input type="tel" name="client_phone" required value="{{ old('client_phone') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs"
                               placeholder="+91 98765 43210">
                    </div>
                </div>
            </div>

            <!-- Trip Specs -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4 text-teal-600"></i> Departure & Passenger Count
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Departure Date *</label>
                        <input type="date" name="departure_date" id="depDate" required min="{{ date('Y-m-d') }}" value="{{ old('departure_date') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Adults (12+ Yrs) *</label>
                        <select name="adults" id="adultCount" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs">
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ old('adults') == $i ? 'selected' : '' }}>{{ $i }} Adult{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Children (2-12 Yrs)</label>
                        <select name="children" id="childCount" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs">
                            @for($i = 0; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('children') == $i ? 'selected' : '' }}>{{ $i }} Child{{ $i > 1 ? 'ren' : '' }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <!-- Payment Settlement Method -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="wallet" class="w-4 h-4 text-teal-600"></i> B2B Settlement Choice
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Agency Wallet -->
                    <label class="p-4 rounded-xl border-2 cursor-pointer transition flex flex-col justify-between hover:border-teal-500 border-teal-600 bg-teal-50/50">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-900 text-xs">Agency Wallet</span>
                            <input type="radio" name="payment_choice" value="wallet" checked class="text-teal-600 focus:ring-teal-500">
                        </div>
                        <p class="text-[11px] text-slate-500">Instant deduction from your account balance.</p>
                        <p class="text-xs font-bold text-emerald-600 mt-2">Balance: ₹{{ number_format($agent->wallet_balance, 2) }}</p>
                    </label>

                    <!-- Agency Credit Line -->
                    <label class="p-4 rounded-xl border-2 cursor-pointer transition flex flex-col justify-between hover:border-teal-500 border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-900 text-xs">Agency Credit Line</span>
                            <input type="radio" name="payment_choice" value="credit" class="text-teal-600 focus:ring-teal-500">
                        </div>
                        <p class="text-[11px] text-slate-500">Bill against monthly credit line facility.</p>
                        <p class="text-xs font-bold text-amber-600 mt-2">Available: ₹{{ number_format($availableCredit, 2) }}</p>
                    </label>

                    <!-- Pay Later -->
                    <label class="p-4 rounded-xl border-2 cursor-pointer transition flex flex-col justify-between hover:border-teal-500 border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-900 text-xs">Hold & Pay Later</span>
                            <input type="radio" name="payment_choice" value="pay_later" class="text-teal-600 focus:ring-teal-500">
                        </div>
                        <p class="text-[11px] text-slate-500">Hold seats for 24 hours without instant debit.</p>
                        <p class="text-xs font-bold text-slate-500 mt-2">Temporary Hold</p>
                    </label>
                </div>
            </div>

            <!-- Cost Summary Box -->
            @php
                $baseAdult = (float) ($package->discount_price ?: $package->adult_price);
                $baseChild = (float) $package->child_price;
            @endphp
            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-2 text-xs">
                <div class="flex justify-between text-slate-600">
                    <span>Adult Base (<span id="dispAdults">1</span> &times; ₹{{ number_format($baseAdult, 2) }}):</span>
                    <span class="font-semibold text-slate-800" id="dispAdultTotal">₹{{ number_format($baseAdult, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>GST (SAC 9985 - 5%):</span>
                    <span class="font-semibold text-slate-800" id="dispTax">₹{{ number_format($baseAdult * 0.05, 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-teal-700">
                    <span>Agency Commission ({{ $agent->commission_rate }}% Credited Instantly):</span>
                    <span id="dispComm">+ ₹{{ number_format(($baseAdult * (float) $agent->commission_rate) / 100, 2) }}</span>
                </div>
                <div class="pt-2 border-t border-slate-200 flex justify-between font-extrabold text-sm text-slate-900">
                    <span>Total Client Gross Amount:</span>
                    <span class="text-teal-700 text-base" id="dispGross">₹{{ number_format($baseAdult * 1.05, 2) }}</span>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-teal-600 to-slate-900 hover:from-teal-700 hover:to-black text-white font-bold rounded-xl text-xs transition shadow-lg flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i> Confirm Booking & Credit Commission to Wallet
            </button>
        </form>
    </div>
</div>

<script>
    const adultPrice = {{ $baseAdult }};
    const childPrice = {{ $baseChild }};
    const commRate = {{ (float) $agent->commission_rate }};

    function recalculate() {
        const adults = parseInt(document.getElementById('adultCount').value) || 1;
        const children = parseInt(document.getElementById('childCount').value) || 0;

        const selling = (adults * adultPrice) + (children * childPrice);
        const tax = selling * 0.05;
        const total = selling + tax;
        const comm = (selling * commRate) / 100;

        document.getElementById('dispAdults').innerText = adults;
        document.getElementById('dispAdultTotal').innerText = '₹' + selling.toLocaleString('en-IN', {minimumFractionDigits: 2});
        document.getElementById('dispTax').innerText = '₹' + tax.toLocaleString('en-IN', {minimumFractionDigits: 2});
        document.getElementById('dispComm').innerText = '+ ₹' + comm.toLocaleString('en-IN', {minimumFractionDigits: 2});
        document.getElementById('dispGross').innerText = '₹' + total.toLocaleString('en-IN', {minimumFractionDigits: 2});
    }

    document.getElementById('adultCount').addEventListener('change', recalculate);
    document.getElementById('childCount').addEventListener('change', recalculate);
</script>
@endsection

