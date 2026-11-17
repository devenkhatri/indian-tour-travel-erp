@extends('layouts.app')

@section('title', 'Register - BharatTravel Club')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-xl w-full space-y-8 bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-slate-100">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl mb-4">
                <i data-lucide="user-plus" class="w-8 h-8"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 font-serif">Create Your Account</h2>
            <p class="mt-2 text-sm text-slate-600">Join BharatTravel for exclusive vacation discounts, saved vouchers & instant booking</p>
        </div>

        @if($errors->any())
            <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 text-sm rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-8 space-y-5" action="{{ route('register.submit') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Full Legal Name *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}"
                               class="appearance-none block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                               placeholder="e.g. Vikramaditya Rathore">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Address *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}"
                               class="appearance-none block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                               placeholder="vikram@example.com">
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Mobile Number (WhatsApp) *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="phone" class="w-5 h-5"></i>
                        </div>
                        <input id="phone" name="phone" type="tel" required value="{{ old('phone') }}"
                               class="appearance-none block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                               placeholder="+91 98765 43210">
                    </div>
                </div>

                <div>
                    <label for="city" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">City</label>
                    <input id="city" name="city" type="text" value="{{ old('city') }}"
                           class="appearance-none block w-full px-3 py-3 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                           placeholder="New Delhi">
                </div>

                <div>
                    <label for="state" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">State / Province</label>
                    <input id="state" name="state" type="text" value="{{ old('state') }}"
                           class="appearance-none block w-full px-3 py-3 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                           placeholder="Delhi NCR">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Password *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                               class="appearance-none block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                               placeholder="Min 8 characters">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Confirm Password *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="appearance-none block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                               placeholder="Re-enter password">
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all">
                    Complete Registration & Get ₹5,000 Coupon
                </button>
            </div>
        </form>

        <div class="text-center text-sm text-slate-600 mt-4">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-bold text-amber-600 hover:text-amber-500">Sign in instead</a>
        </div>
    </div>
</div>
@endsection

