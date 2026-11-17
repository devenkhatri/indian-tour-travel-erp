<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer Portal | BharatTravel')</title>
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
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Topbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16 gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0">
                <div class="w-8 h-8 rounded-lg bg-teal-600 flex items-center justify-center text-white font-bold shadow-xs">
                    <i data-lucide="compass" class="w-5 h-5"></i>
                </div>
                <span class="text-base font-extrabold text-slate-900 tracking-tight">BHARAT<span class="text-teal-600">TRAVEL</span></span>
            </a>

            <div class="flex items-center gap-3 sm:gap-4 text-xs font-semibold whitespace-nowrap shrink-0">
                <a href="{{ route('packages.index') }}" class="text-slate-600 hover:text-teal-600 hidden sm:inline transition">Explore Packages</a>
                <a href="{{ route('services.custom_tour') }}" class="text-amber-600 hover:text-amber-700 hidden sm:inline font-bold transition">Plan Custom Holiday</a>
                <div class="border-l border-slate-200 h-6 hidden sm:block"></div>
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-xs shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="text-slate-800 font-bold hidden md:inline max-w-[140px] truncate block">{{ Auth::user()->name }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-rose-600 hover:text-rose-700 font-bold transition">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Body Layout with Sidebar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-1">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Customer Navigation Sidebar -->
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs space-y-4">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-full bg-teal-100 text-teal-800 flex items-center justify-center text-base font-extrabold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="truncate">
                            <h3 class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name }}</h3>
                            <p class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</p>
                            <span class="inline-block mt-1 text-[10px] bg-teal-50 text-teal-700 font-bold px-2 py-0.5 rounded-full">Customer ID #{{ Auth::user()->customer ? Auth::user()->customer->customer_code : 'N/A' }}</span>
                        </div>
                    </div>

                    <nav class="space-y-1 text-xs font-semibold">
                        <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.dashboard') ? 'bg-teal-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard Overview
                        </a>
                        <a href="{{ route('customer.bookings') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.bookings*') ? 'bg-teal-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i data-lucide="ticket" class="w-4 h-4"></i> My Bookings & Vouchers
                        </a>
                        <a href="{{ route('customer.inquiries') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.inquiries') ? 'bg-teal-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i data-lucide="help-circle" class="w-4 h-4"></i> My Tour Inquiries
                        </a>
                        <a href="{{ route('customer.visa_passport') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.visa_passport') ? 'bg-teal-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i data-lucide="stamp" class="w-4 h-4"></i> Visa & Passport Status
                        </a>
                        <a href="{{ route('customer.passengers') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.passengers') ? 'bg-teal-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i data-lucide="users" class="w-4 h-4"></i> Saved Travellers & Passports
                        </a>
                        <a href="{{ route('customer.wishlist') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.wishlist') ? 'bg-teal-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i data-lucide="heart" class="w-4 h-4"></i> Saved Wishlist
                        </a>
                        <a href="{{ route('customer.support') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.support*') ? 'bg-teal-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i data-lucide="message-square" class="w-4 h-4"></i> Support Tickets
                        </a>
                        <a href="{{ route('customer.profile') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.profile') ? 'bg-teal-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i data-lucide="settings" class="w-4 h-4"></i> Profile & Contacts
                        </a>
                    </nav>
                </div>

                <!-- Assistance Card -->
                <div class="bg-gradient-to-br from-slate-900 to-teal-950 text-white rounded-2xl p-5 shadow-sm text-xs space-y-2">
                    <div class="font-bold flex items-center gap-2 text-teal-400">
                        <i data-lucide="headset" class="w-4 h-4"></i> Dedicated Helpdesk
                    </div>
                    <p class="text-slate-300 text-[11px] leading-relaxed">Need urgent flight date changes or voucher assistance? Reach our 24x7 guest support directly.</p>
                    <div class="pt-2 border-t border-slate-800 font-bold text-white flex items-center gap-2">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-teal-400"></i> +91 11 4567 8900
                    </div>
                </div>
            </div>

            <!-- Customer Content Column -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Notifications -->
                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2 shadow-xs">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2 shadow-xs">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('customer_content')
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} BharatTravel Holidays ERP. All rights reserved.
    </footer>

    @stack('scripts')
</body>
</html>

