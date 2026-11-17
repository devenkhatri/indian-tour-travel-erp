@extends('layouts.app')

@section('title', 'Sign In - BharatTravel CRM & Travel Marketplace')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-slate-100">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl mb-4">
                <i data-lucide="shield-check" class="w-8 h-8"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 font-serif">Welcome Back</h2>
            <p class="mt-2 text-sm text-slate-600">Access your bookings, itinerary vouchers, or agency portal</p>
        </div>

        @if(session('error'))
            <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 text-sm rounded">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 text-sm rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               value="{{ old('email') }}"
                               class="appearance-none block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 sm:text-sm transition-all"
                               placeholder="you@domain.com">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Password</label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="appearance-none block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 sm:text-sm transition-all"
                               placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-slate-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-slate-700">Remember me</label>
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all">
                    Sign In
                </button>
            </div>
        </form>

        <!-- Fast Demo Switcher -->
        <div class="mt-6 pt-6 border-t border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider text-center mb-3">Quick Demo Logins</p>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <button type="button" onclick="fillDemo('admin@bharattravel.com', 'password')" class="p-2 bg-slate-100 hover:bg-amber-50 hover:text-amber-700 text-slate-700 rounded-lg text-left transition font-medium border border-slate-200">
                    <span class="block font-bold">👑 Super Admin</span>
                    <span class="text-[10px] text-slate-500">Full ERP/CRM Access</span>
                </button>
                <button type="button" onclick="fillDemo('agent@shreetravels.com', 'password')" class="p-2 bg-slate-100 hover:bg-amber-50 hover:text-amber-700 text-slate-700 rounded-lg text-left transition font-medium border border-slate-200">
                    <span class="block font-bold">🏢 B2B Agent</span>
                    <span class="text-[10px] text-slate-500">B2B Portal & Rates</span>
                </button>
                <button type="button" onclick="fillDemo('sales@bharattravel.com', 'password')" class="p-2 bg-slate-100 hover:bg-amber-50 hover:text-amber-700 text-slate-700 rounded-lg text-left transition font-medium border border-slate-200">
                    <span class="block font-bold">💼 Sales Ops</span>
                    <span class="text-[10px] text-slate-500">Leads & Follow-ups</span>
                </button>
                <button type="button" onclick="fillDemo('rahul.sharma@gmail.com', 'password')" class="p-2 bg-slate-100 hover:bg-amber-50 hover:text-amber-700 text-slate-700 rounded-lg text-left transition font-medium border border-slate-200">
                    <span class="block font-bold">👤 Customer</span>
                    <span class="text-[10px] text-slate-500">Self-Service Hub</span>
                </button>
            </div>
        </div>

        <div class="text-center text-sm text-slate-600 mt-4">
            Don't have an account yet? 
            <a href="{{ route('register') }}" class="font-bold text-amber-600 hover:text-amber-500">Register as Traveller</a>
        </div>
    </div>
</div>

<script>
    function fillDemo(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endsection

