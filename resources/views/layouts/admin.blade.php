<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin ERP & CRM | BharatTravel')</title>
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
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden" @click="sidebarOpen = false"></div>

    <!-- Admin Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-slate-950 text-slate-300 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 border-r border-slate-900 shrink-0">
        
        <!-- Sidebar Brand -->
        <div class="h-20 flex items-center gap-3 px-6 border-b border-slate-900 bg-slate-950/80">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-teal-500 to-teal-700 flex items-center justify-center text-white font-bold shadow-md shadow-teal-500/20">
                <i data-lucide="compass" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-base font-extrabold tracking-tight text-white block leading-tight">BHARAT<span class="text-teal-400">ERP</span></span>
                <span class="text-[9px] tracking-widest text-slate-500 uppercase font-semibold">Travel Agency CRM</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 overflow-y-auto px-4 py-4 space-y-6 text-xs">
            
            <!-- Group: Main -->
            <div>
                <div class="text-[10px] uppercase font-bold tracking-wider text-slate-500 px-3 mb-2">Main</div>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-teal-600 text-white font-semibold shadow-xs' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                    </a>
                </div>
            </div>

            <!-- Group: CRM & Sales -->
            <div>
                <div class="text-[10px] uppercase font-bold tracking-wider text-slate-500 px-3 mb-2">CRM & Sales</div>
                <div class="space-y-1">
                    <a href="{{ route('admin.leads.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.leads.*') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="user-plus" class="w-4 h-4"></i> Leads & Inquiries
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.customers.*') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="users" class="w-4 h-4"></i> Customers Directory
                    </a>
                    <a href="{{ route('admin.quotations.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.quotations.*') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="file-text" class="w-4 h-4"></i> Quotations
                    </a>
                </div>
            </div>

            <!-- Group: Travel & Bookings -->
            <div>
                <div class="text-[10px] uppercase font-bold tracking-wider text-slate-500 px-3 mb-2">Tours & Bookings</div>
                <div class="space-y-1">
                    <a href="{{ route('admin.packages.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.packages.*') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="palmtree" class="w-4 h-4"></i> Tour Packages
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.bookings.*') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="ticket" class="w-4 h-4"></i> Central Bookings
                    </a>
                    <a href="{{ route('admin.operations.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.operations.*') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="map" class="w-4 h-4"></i> Tour Operations / Trips
                    </a>
                </div>
            </div>

            <!-- Group: Consular Services -->
            <div>
                <div class="text-[10px] uppercase font-bold tracking-wider text-slate-500 px-3 mb-2">Visas & Passports</div>
                <div class="space-y-1">
                    <a href="{{ route('admin.visa.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.visa.*') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="stamp" class="w-4 h-4"></i> Visa Applications
                    </a>
                    <a href="{{ route('admin.passport.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.passport.*') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="file-badge" class="w-4 h-4"></i> Passport Desk
                    </a>
                </div>
            </div>

            <!-- Group: Accounts & Finance -->
            <div>
                <div class="text-[10px] uppercase font-bold tracking-wider text-slate-500 px-3 mb-2">Finance & GST</div>
                <div class="space-y-1">
                    <a href="{{ route('admin.accounts.payments') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.accounts.payments') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="credit-card" class="w-4 h-4"></i> Payments & Receipts
                    </a>
                    <a href="{{ route('admin.accounts.invoices') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.accounts.invoices*') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="receipt" class="w-4 h-4"></i> Tax Invoices
                    </a>
                    <a href="{{ route('admin.accounts.expenses') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.accounts.expenses*') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="arrow-down-circle" class="w-4 h-4"></i> Expenses Ledger
                    </a>
                    <a href="{{ route('admin.accounts.gst') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.accounts.gst') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="calculator" class="w-4 h-4"></i> Indian GST / TCS Report
                    </a>
                </div>
            </div>

            <!-- Group: Partners & Operations -->
            <div>
                <div class="text-[10px] uppercase font-bold tracking-wider text-slate-500 px-3 mb-2">Vendors & Partners</div>
                <div class="space-y-1">
                    <a href="{{ route('admin.suppliers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.suppliers.*') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="truck" class="w-4 h-4"></i> Suppliers & Vendors
                    </a>
                    <a href="{{ route('admin.agents.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.agents.*') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="briefcase" class="w-4 h-4"></i> B2B Travel Agents
                    </a>
                    <a href="{{ route('admin.employees.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.employees.*') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="user-check" class="w-4 h-4"></i> Staff & Employees
                    </a>
                </div>
            </div>

            <!-- Group: System & Content -->
            <div>
                <div class="text-[10px] uppercase font-bold tracking-wider text-slate-500 px-3 mb-2">Management</div>
                <div class="space-y-1">
                    <a href="{{ route('admin.cms.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.cms.*') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="globe" class="w-4 h-4"></i> Website CMS & Blogs
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.reports.*') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="bar-chart-3" class="w-4 h-4"></i> Reports & Analytics
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('admin.settings.*') ? 'bg-teal-600 text-white font-semibold' : 'hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="settings" class="w-4 h-4"></i> Agency Settings
                    </a>
                </div>
            </div>

        </div>

        <!-- Sidebar User Footer -->
        <div class="p-4 border-t border-slate-900 bg-slate-950 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-teal-600 text-white flex items-center justify-center font-bold text-xs">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="truncate max-w-[120px]">
                    <div class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</div>
                    <div class="text-[10px] text-teal-400 capitalize">{{ Auth::user()->role }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" title="Logout" class="p-1.5 text-slate-400 hover:text-rose-400 transition">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>

    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Topbar -->
        <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6 z-10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg lg:hidden">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Environment: <strong class="text-slate-800">Production (MySQL 8)</strong></span>
                    <span class="text-slate-300">|</span>
                    <span>Branch: <strong class="text-slate-800">New Delhi HQ</strong></span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-teal-600 transition px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i> View Public Website
                </a>
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="text-xs font-bold text-slate-700 hidden md:inline">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </header>

        <!-- Toast Notifications -->
        @if(session('success'))
            <div class="mx-6 mt-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2 shadow-xs">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 mt-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2 shadow-xs">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mx-6 mt-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs shadow-xs">
                <div class="font-bold mb-1">Please fix the following validation errors:</div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>

    </div>

    @stack('scripts')
</body>
</html>

