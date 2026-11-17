<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BharatTravel | Premium Holiday Packages & Travel ERP')</title>
    <meta name="description" content="@yield('meta_description', 'Discover handpicked domestic and international tour packages, flight bookings, hotel reservations, and visa assistance with BharatTravel.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- Top Announcement Bar -->
    <div class="bg-slate-900 text-slate-300 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-2">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center gap-1.5 text-amber-400 font-medium whitespace-nowrap">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5 shrink-0"></i>
                    <span>Summer Special: Use code <strong class="bg-amber-400/20 px-1.5 py-0.5 rounded text-amber-300 font-bold">BHARAT5000</strong> for flat ₹5,000 off!</span>
                </span>
            </div>
            <div class="flex items-center gap-4 sm:gap-5 text-slate-400 whitespace-nowrap">
                <a href="tel:+911145678900" class="hover:text-white flex items-center gap-1.5 transition">
                    <i data-lucide="phone" class="w-3.5 h-3.5 text-teal-400 shrink-0"></i> +91 11 4567 8900
                </a>
                <span class="text-slate-700">|</span>
                <a href="mailto:contact@bharattravel.com" class="hover:text-white flex items-center gap-1.5 transition hidden md:flex">
                    <i data-lucide="mail" class="w-3.5 h-3.5 text-teal-400 shrink-0"></i> contact@bharattravel.com
                </a>
                <span class="text-slate-700 hidden md:inline">|</span>
                @if(Auth::check() && Auth::user()->isAgent())
                    <a href="{{ route('agent.dashboard') }}" class="text-teal-400 font-semibold hover:underline">B2B Partner Portal</a>
                @elseif(Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isStaff()))
                    <a href="{{ route('admin.dashboard') }}" class="text-amber-400 font-semibold hover:underline">Admin CRM</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-white">Travel Agent Login</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="bg-white/95 backdrop-blur-md sticky top-0 z-40 border-b border-slate-100 shadow-xs" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4 xl:gap-8">
                
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 mr-2 xl:mr-6 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-teal-600 via-teal-700 to-slate-900 flex items-center justify-center text-white shadow-md shadow-teal-500/20 group-hover:scale-105 transition-transform duration-200">
                        <i data-lucide="compass" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <span class="text-xl font-extrabold tracking-tight text-slate-900 block leading-tight">BHARAT<span class="text-teal-600">TRAVEL</span></span>
                        <span class="text-[9px] tracking-widest text-slate-500 uppercase font-bold block">Tours & Holidays ERP</span>
                    </div>
                </a>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden lg:flex items-center gap-1 xl:gap-1.5 2xl:gap-3 text-[13px] 2xl:text-sm font-semibold text-slate-700 whitespace-nowrap">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-xl transition whitespace-nowrap {{ request()->routeIs('home') ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-700 hover:text-teal-600 hover:bg-slate-50' }}">
                        Home
                    </a>
                    <a href="{{ route('packages.index') }}" class="px-3 py-2 rounded-xl transition whitespace-nowrap {{ request()->routeIs('packages.*') ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-700 hover:text-teal-600 hover:bg-slate-50' }}">
                        Holiday Packages
                    </a>
                    <a href="{{ route('destinations.index') }}" class="px-3 py-2 rounded-xl transition whitespace-nowrap {{ request()->routeIs('destinations.*') ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-700 hover:text-teal-600 hover:bg-slate-50' }}">
                        Destinations
                    </a>
                    
                    <!-- Services Dropdown -->
                    <div class="relative" x-data="{ open: false }" @mouseleave="open = false">
                        <button @mouseover="open = true" @click="open = !open" class="flex items-center gap-1 px-3 py-2 rounded-xl transition whitespace-nowrap {{ request()->routeIs('services.*') ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-700 hover:text-teal-600 hover:bg-slate-50' }}">
                            <span>Travel Services</span>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" x-transition.opacity.duration.200ms class="absolute top-full left-0 w-64 bg-white rounded-2xl shadow-xl border border-slate-100 py-3 z-50">
                            <a href="{{ route('services.visa') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 text-slate-700 hover:text-teal-600 text-xs font-medium">
                                <i data-lucide="stamp" class="w-4 h-4 text-teal-600"></i>
                                <div>
                                    <div class="font-semibold text-slate-900">Visa Assistance</div>
                                    <div class="text-[11px] text-slate-400">Dubai, Schengen, Thailand e-Visa</div>
                                </div>
                            </a>
                            <a href="{{ route('services.passport') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 text-slate-700 hover:text-teal-600 text-xs font-medium">
                                <i data-lucide="file-badge" class="w-4 h-4 text-teal-600"></i>
                                <div>
                                    <div class="font-semibold text-slate-900">Passport Services</div>
                                    <div class="text-[11px] text-slate-400">Fresh, Renewal & Tatkal Help</div>
                                </div>
                            </a>
                            <a href="{{ route('services.flights') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 text-slate-700 hover:text-teal-600 text-xs font-medium">
                                <i data-lucide="plane" class="w-4 h-4 text-teal-600"></i>
                                <div>
                                    <div class="font-semibold text-slate-900">Flight Assistance</div>
                                    <div class="text-[11px] text-slate-400">Domestic & International Fares</div>
                                </div>
                            </a>
                            <a href="{{ route('services.hotels') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 text-slate-700 hover:text-teal-600 text-xs font-medium">
                                <i data-lucide="hotel" class="w-4 h-4 text-teal-600"></i>
                                <div>
                                    <div class="font-semibold text-slate-900">Hotel Bookings</div>
                                    <div class="text-[11px] text-slate-400">Contracted Luxury & Budget Stays</div>
                                </div>
                            </a>
                            <a href="{{ route('services.trains') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 text-slate-700 hover:text-teal-600 text-xs font-medium">
                                <i data-lucide="train" class="w-4 h-4 text-teal-600"></i>
                                <div>
                                    <div class="font-semibold text-slate-900">Train Assistance</div>
                                    <div class="text-[11px] text-slate-400">IRCTC Rail Routing Assistance</div>
                                </div>
                            </a>
                            <a href="{{ route('services.buses') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 text-slate-700 hover:text-teal-600 text-xs font-medium">
                                <i data-lucide="bus" class="w-4 h-4 text-teal-600"></i>
                                <div>
                                    <div class="font-semibold text-slate-900">Bus Tickets</div>
                                    <div class="text-[11px] text-slate-400">Volvo AC Sleeper & Seater</div>
                                </div>
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <a href="{{ route('services.custom_tour') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-teal-50 text-teal-700 text-xs font-bold">
                                <i data-lucide="sparkles" class="w-4 h-4 text-teal-600"></i>
                                <span>Customize My Tour Plan</span>
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('blogs.index') }}" class="px-3 py-2 rounded-xl transition whitespace-nowrap {{ request()->routeIs('blogs.*') ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-700 hover:text-teal-600 hover:bg-slate-50' }}">
                        Travel Blog
                    </a>
                    <a href="{{ route('about') }}" class="px-3 py-2 rounded-xl transition whitespace-nowrap {{ request()->routeIs('about') ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-700 hover:text-teal-600 hover:bg-slate-50' }}">
                        About Us
                    </a>
                    <a href="{{ route('contact') }}" class="px-3 py-2 rounded-xl transition whitespace-nowrap {{ request()->routeIs('contact') ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-700 hover:text-teal-600 hover:bg-slate-50' }}">
                        Contact
                    </a>
                </nav>

                <!-- Auth / Action Buttons -->
                <div class="hidden lg:flex items-center gap-2.5 xl:gap-3 shrink-0 ml-auto lg:ml-2 xl:ml-4">
                    <a href="{{ route('services.custom_tour') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white text-xs font-bold px-3.5 py-2.5 rounded-xl shadow-xs hover:shadow-md transition whitespace-nowrap shrink-0">
                        <i data-lucide="send" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>Plan Custom Trip</span>
                    </a>

                    @if(Auth::check())
                        <div class="relative" x-data="{ openUser: false }" @mouseleave="openUser = false">
                            <button @click="openUser = !openUser" class="flex items-center gap-2 pl-3 pr-1.5 py-1.5 rounded-full border border-slate-200 bg-slate-50 hover:bg-slate-100 transition whitespace-nowrap shrink-0 focus:outline-hidden">
                                <span class="text-xs font-bold text-slate-800 max-w-[110px] xl:max-w-[140px] truncate block">{{ Auth::user()->name }}</span>
                                <div class="w-7 h-7 rounded-full bg-teal-600 text-white flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </button>
                            <div x-show="openUser" x-transition.opacity.duration.150ms class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2.5 z-50 text-xs">
                                <div class="px-4 py-2 border-b border-slate-100 mb-1">
                                    <div class="font-bold text-slate-900 truncate">{{ Auth::user()->name }}</div>
                                    <div class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</div>
                                </div>
                                @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 font-semibold text-amber-600">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4 text-amber-600"></i> Admin ERP Dashboard
                                    </a>
                                @elseif(Auth::user()->isAgent())
                                    <a href="{{ route('agent.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 font-semibold text-teal-600">
                                        <i data-lucide="briefcase" class="w-4 h-4 text-teal-600"></i> B2B Partner Portal
                                    </a>
                                @else
                                    <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 font-semibold text-teal-600">
                                        <i data-lucide="user" class="w-4 h-4 text-teal-600"></i> My Customer Dashboard
                                    </a>
                                    <a href="{{ route('customer.bookings') }}" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 text-slate-700">
                                        <i data-lucide="ticket" class="w-4 h-4 text-slate-500"></i> My Bookings & Vouchers
                                    </a>
                                    <a href="{{ route('customer.wishlist') }}" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 text-slate-700">
                                        <i data-lucide="heart" class="w-4 h-4 text-slate-500"></i> My Saved Wishlist
                                    </a>
                                @endif
                                <div class="border-t border-slate-100 my-1.5"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2 hover:bg-rose-50 text-rose-600 font-semibold">
                                        <i data-lucide="log-out" class="w-4 h-4"></i> Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-bold text-slate-700 hover:text-teal-600 px-3 py-2 rounded-xl transition whitespace-nowrap">Sign In</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-3.5 py-2.5 rounded-xl transition shadow-xs whitespace-nowrap shrink-0">
                            <i data-lucide="user-plus" class="w-3.5 h-3.5 shrink-0"></i>
                            <span>Register</span>
                        </a>
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center gap-2 lg:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-hidden transition">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Navigation Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-transition class="lg:hidden border-t border-slate-100 bg-white px-4 pt-3 pb-6 space-y-3">
            <a href="{{ route('home') }}" class="block font-semibold text-slate-700 py-1.5">Home</a>
            <a href="{{ route('packages.index') }}" class="block font-semibold text-slate-700 py-1.5">Holiday Packages</a>
            <a href="{{ route('destinations.index') }}" class="block font-semibold text-slate-700 py-1.5">Destinations</a>
            <a href="{{ route('services.visa') }}" class="block font-semibold text-slate-700 py-1.5">Visa Assistance</a>
            <a href="{{ route('services.passport') }}" class="block font-semibold text-slate-700 py-1.5">Passport Services</a>
            <a href="{{ route('services.flights') }}" class="block font-semibold text-slate-700 py-1.5">Flight Booking Assistance</a>
            <a href="{{ route('services.custom_tour') }}" class="block font-bold text-amber-600 py-1.5">Plan Custom Tour</a>
            <a href="{{ route('blogs.index') }}" class="block font-semibold text-slate-700 py-1.5">Travel Guides</a>
            <a href="{{ route('contact') }}" class="block font-semibold text-slate-700 py-1.5">Contact Us</a>

            <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                @if(Auth::check())
                    <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : (Auth::user()->isAgent() ? route('agent.dashboard') : route('customer.dashboard')) }}" class="w-full text-center py-2.5 rounded-xl bg-teal-600 text-white font-bold text-sm">Go to Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-center py-2 text-rose-600 font-bold text-sm">Sign Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center py-2.5 rounded-xl border border-slate-300 text-slate-800 font-bold text-sm">Sign In</a>
                    <a href="{{ route('register') }}" class="w-full text-center py-2.5 rounded-xl bg-slate-900 text-white font-bold text-sm">Create Free Account</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Global Toast Alerts -->
    @if(session('success'))
        <div class="max-w-4xl mx-auto px-4 mt-4 w-full">
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-xs flex items-center justify-between text-emerald-800">
                <div class="flex items-center gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-4xl mx-auto px-4 mt-4 w-full">
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-xs flex items-center justify-between text-rose-800">
                <div class="flex items-center gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600"></i>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Floating WhatsApp Help Button -->
    <a href="https://wa.me/919810012345?text=Hi%20BharatTravel,%20I%20am%20interested%20in%20planning%20a%20holiday%20trip." target="_blank" class="fixed bottom-6 right-6 z-50 bg-emerald-500 hover:bg-emerald-600 text-white p-3.5 rounded-full shadow-2xl flex items-center gap-2 transition transform hover:scale-110">
        <i data-lucide="message-circle" class="w-6 h-6"></i>
        <span class="hidden md:inline font-bold text-xs pr-1">Chat on WhatsApp</span>
    </a>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 pt-16 pb-10 border-t border-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-slate-800/80">
                
                <!-- Col 1: About -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-teal-500 flex items-center justify-center text-slate-950 font-bold shadow-md">
                            <i data-lucide="compass" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xl font-extrabold text-white tracking-tight">BHARAT<span class="text-teal-400">TRAVEL</span></span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-sm">
                        Leading Indian travel management firm and authorized holiday architect. Specializing in domestic & international tours, Kashmir houseboats, Dubai desert safaris, Bali villas, flight ticketing, and comprehensive visa assistance.
                    </p>
                    <div class="text-xs space-y-1 text-slate-400">
                        <div class="flex items-center gap-2"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-teal-400"></i> Plot 45, Connaught Place, New Delhi - 110001</div>
                        <div class="flex items-center gap-2"><i data-lucide="phone" class="w-3.5 h-3.5 text-teal-400"></i> +91 11 4567 8900 / +91 98100 12345</div>
                        <div class="flex items-center gap-2"><i data-lucide="shield-check" class="w-3.5 h-3.5 text-teal-400"></i> GSTIN: 07AAACB1234F1Z9 (Govt. Approved)</div>
                    </div>
                </div>

                <!-- Col 2: Top Destinations -->
                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Top Destinations</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('destinations.show', 'kashmir') }}" class="hover:text-teal-400 transition">Kashmir Valley</a></li>
                        <li><a href="{{ route('destinations.show', 'kerala') }}" class="hover:text-teal-400 transition">Kerala Backwaters</a></li>
                        <li><a href="{{ route('destinations.show', 'dubai') }}" class="hover:text-teal-400 transition">Dubai, UAE</a></li>
                        <li><a href="{{ route('destinations.show', 'bali') }}" class="hover:text-teal-400 transition">Bali, Indonesia</a></li>
                        <li><a href="{{ route('destinations.index') }}" class="text-teal-400 hover:underline font-semibold">View All Destinations &rarr;</a></li>
                    </ul>
                </div>

                <!-- Col 3: Travel Services -->
                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Travel Services</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('services.visa') }}" class="hover:text-teal-400 transition">Visa Application Help</a></li>
                        <li><a href="{{ route('services.passport') }}" class="hover:text-teal-400 transition">Passport Assistance</a></li>
                        <li><a href="{{ route('services.flights') }}" class="hover:text-teal-400 transition">Flight Reservations</a></li>
                        <li><a href="{{ route('services.hotels') }}" class="hover:text-teal-400 transition">Hotel Stays & Resorts</a></li>
                        <li><a href="{{ route('services.custom_tour') }}" class="hover:text-teal-400 transition">Customized Holiday Tours</a></li>
                    </ul>
                </div>

                <!-- Col 4: Newsletter -->
                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Secret Deals</h4>
                    <p class="text-xs text-slate-400 mb-3">Subscribe to get secret weekend deals and exclusive holiday promo codes.</p>
                    <form id="newsletter-form" class="space-y-2">
                        @csrf
                        <input type="email" id="newsletter-email" required placeholder="Enter your email" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white placeholder-slate-500 focus:outline-hidden focus:border-teal-500">
                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-500 text-white font-bold text-xs py-2 rounded-xl transition shadow-sm">Subscribe Free</button>
                        <div id="newsletter-feedback" class="text-[11px] hidden"></div>
                    </form>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} BharatTravel Holidays ERP. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('about') }}" class="hover:text-slate-400">About Us</a>
                    <a href="{{ route('contact') }}" class="hover:text-slate-400">Support</a>
                    <a href="{{ route('login') }}" class="hover:text-slate-400">Staff Login</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('newsletter-form');
            if (form) {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const email = document.getElementById('newsletter-email').value;
                    const feedback = document.getElementById('newsletter-feedback');
                    try {
                        const res = await fetch("{{ route('newsletter.subscribe') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
                            },
                            body: JSON.stringify({ email })
                        });
                        const data = await res.json();
                        feedback.textContent = data.message;
                        feedback.className = "text-[11px] text-emerald-400 block";
                        form.reset();
                    } catch (err) {
                        feedback.textContent = "Something went wrong. Please try again.";
                        feedback.className = "text-[11px] text-rose-400 block";
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>

