<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'B2B Partner Portal | BharatTravel')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Topbar -->
    <header class="bg-slate-900 text-white border-b border-slate-800 sticky top-0 z-30 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16 gap-4">
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('agent.dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-teal-500 flex items-center justify-center text-slate-950 font-bold shadow-xs">
                        <i data-lucide="briefcase" class="w-5 h-5"></i>
                    </div>
                    <span class="text-base font-extrabold text-white tracking-tight">BHARAT<span class="text-teal-400">B2B</span></span>
                </a>
                <span class="bg-slate-800 text-slate-400 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-slate-700 hidden sm:inline whitespace-nowrap">Partner Network</span>
            </div>

            <!-- Agent Financial Bar -->
            @php $currentAgent = Auth::user()->agent; @endphp
            @if($currentAgent)
            <div class="hidden md:flex items-center gap-6 text-xs whitespace-nowrap shrink-0">
                <div class="flex items-center gap-2">
                    <span class="text-slate-400">Wallet:</span>
                    <span class="font-bold text-emerald-400">₹{{ number_format($currentAgent->wallet_balance, 2) }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-slate-400">Credit Limit:</span>
                    <span class="font-bold text-amber-400">₹{{ number_format($currentAgent->credit_limit, 2) }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-slate-400">Commission Rate:</span>
                    <span class="font-bold text-teal-400">{{ $currentAgent->commission_rate }}%</span>
                </div>
            </div>
            @endif

            <div class="flex items-center gap-4 text-xs font-semibold whitespace-nowrap shrink-0">
                <a href="{{ route('home') }}" target="_blank" class="text-slate-400 hover:text-white hidden sm:inline transition">Public Site &rarr;</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-rose-400 hover:text-rose-300 font-bold transition">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <!-- Subnav -->
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-6 overflow-x-auto h-12 text-xs font-bold">
            <a href="{{ route('agent.dashboard') }}" class="py-3 border-b-2 transition {{ request()->routeIs('agent.dashboard') ? 'border-teal-600 text-teal-600' : 'border-transparent text-slate-600 hover:text-slate-900' }}">Dashboard</a>
            <a href="{{ route('agent.packages') }}" class="py-3 border-b-2 transition {{ request()->routeIs('agent.packages*') ? 'border-teal-600 text-teal-600' : 'border-transparent text-slate-600 hover:text-slate-900' }}">B2B Package Tariffs</a>
            <a href="{{ route('agent.bookings') }}" class="py-3 border-b-2 transition {{ request()->routeIs('agent.bookings*') ? 'border-teal-600 text-teal-600' : 'border-transparent text-slate-600 hover:text-slate-900' }}">My Client Bookings</a>
            <a href="{{ route('agent.wallet') }}" class="py-3 border-b-2 transition {{ request()->routeIs('agent.wallet') ? 'border-teal-600 text-teal-600' : 'border-transparent text-slate-600 hover:text-slate-900' }}">Wallet & Ledger</a>
            <a href="{{ route('agent.commissions') }}" class="py-3 border-b-2 transition {{ request()->routeIs('agent.commissions') ? 'border-teal-600 text-teal-600' : 'border-transparent text-slate-600 hover:text-slate-900' }}">Commission Statements</a>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-1">
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2 shadow-xs">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2 shadow-xs">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} BharatTravel B2B Agency Network. All rights reserved.
    </footer>

    @stack('scripts')
</body>
</html>

