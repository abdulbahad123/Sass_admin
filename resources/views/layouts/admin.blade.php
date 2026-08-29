<!DOCTYPE html>
<html lang="en" class="h-full bg-[#F8FAFC] text-slate-800">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin') - Multi-SaaS Platform</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        h1, h2, h3, .font-heading { font-family: 'Outfit', sans-serif; }
        .sidebar-dark { background-color: #080E24; }
        .card-white {
            background-color: #FFFFFF;
            border: 1px solid #F1F5F9;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.03);
        }
    </style>
</head>
<body class="h-full flex overflow-hidden text-slate-800" x-data="{ mobileMenuOpen: false }">

    <!-- Mobile Drawer Overlay Backdrop -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileMenuOpen = false" 
         class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-40 lg:hidden" 
         style="display: none;"></div>

    <!-- Left Dark Sidebar Navigation -->
    <aside :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed lg:static inset-y-0 left-0 w-64 sidebar-dark text-slate-300 flex flex-col flex-shrink-0 z-50 transition-transform duration-300 ease-in-out border-r border-slate-800">
        
        <!-- Logo Header -->
        <div class="h-20 px-6 flex items-center justify-between border-b border-slate-800/80">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 via-violet-600 to-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-600/30">
                    <i data-lucide="layers" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <span class="font-heading font-bold text-lg text-white tracking-tight block leading-tight">Super Admin</span>
                    <span class="block text-[11px] font-medium text-slate-400">Multi-SaaS Platform</span>
                </div>
            </a>
            <button @click="mobileMenuOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4 mr-3"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.agencies.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.agencies.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i data-lucide="building-2" class="w-4 h-4 mr-3"></i>
                Agencies
            </a>

            <a href="{{ route('admin.products.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i data-lucide="box" class="w-4 h-4 mr-3"></i>
                Products Catalog
            </a>

            <a href="{{ route('admin.plans.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.plans.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i data-lucide="tags" class="w-4 h-4 mr-3"></i>
                Plans & Pricing
            </a>

            <a href="{{ route('admin.subscriptions.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.subscriptions.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i data-lucide="receipt" class="w-4 h-4 mr-3"></i>
                Subscriptions & Billing
            </a>

            <a href="{{ route('admin.profile.edit') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.profile.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i data-lucide="settings" class="w-4 h-4 mr-3"></i>
                Profile & Settings
            </a>

            <a href="{{ route('admin.audit-logs.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.audit-logs.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i data-lucide="shield-check" class="w-4 h-4 mr-3"></i>
                Audit Logs
            </a>

            <a href="{{ route('admin.tickets.index') }}" 
               class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.tickets.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <div class="flex items-center">
                    <i data-lucide="life-buoy" class="w-4 h-4 mr-3"></i>
                    <span>Support Tickets</span>
                </div>
                @php $openTicketCount = \App\Models\Ticket::whereIn('status', ['open', 'pending_reply'])->count(); @endphp
                @if($openTicketCount > 0)
                    <span class="px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-amber-500 text-slate-950">
                        {{ $openTicketCount }}
                    </span>
                @endif
            </a>
        </nav>

        <!-- Platform Health Box -->
        <div class="p-4 mx-3 mb-3 rounded-2xl bg-[#121B3B] border border-indigo-900/40 text-slate-300">
            <div class="flex items-center justify-between text-xs font-semibold text-white mb-1">
                <span class="flex items-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-pulse"></span>
                    Platform Health
                </span>
            </div>
            <p class="text-[10px] text-emerald-400 font-medium">All Systems Operational</p>
            
            <div class="mt-3 grid grid-cols-2 gap-2 text-[10px] border-t border-indigo-900/40 pt-2 text-slate-400">
                <div>
                    <span class="block">Uptime</span>
                    <span class="font-bold text-white">99.98%</span>
                </div>
                <div>
                    <span class="block">Response Time</span>
                    <span class="font-bold text-white">320ms</span>
                </div>
            </div>
        </div>

        <!-- Super Admin User Profile Footer -->
        <div class="p-4 border-t border-slate-800/80 flex items-center justify-between">
            <a href="{{ route('admin.profile.edit') }}" class="flex items-center space-x-3 min-w-0">
                @if(auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover ring-2 ring-indigo-500/40">
                @else
                    <div class="w-9 h-9 rounded-full bg-indigo-600 text-white font-bold text-xs flex items-center justify-center ring-2 ring-indigo-500/40">
                        {{ substr(auth()->user()->name ?? 'SA', 0, 2) }}
                    </div>
                @endif
                <div class="truncate">
                    <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name ?? 'Super Admin' }}</p>
                    <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->email ?? 'admin@platform.com' }}</p>
                </div>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-colors" title="Logout">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Workspace -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#F8FAFC]">
        <!-- Top Navbar Header (Responsive Mobile Fix) -->
        <header class="min-h-[4.5rem] bg-white border-b border-slate-200/80 px-4 sm:px-8 py-3 flex items-center justify-between gap-3 z-20">
            
            <!-- Left Side Title & Mobile Hamburger -->
            <div class="flex items-center space-x-2.5 sm:space-x-4 min-w-0 flex-1">
                <button @click="mobileMenuOpen = true" class="lg:hidden p-1.5 rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-none flex-shrink-0">
                    <i data-lucide="menu" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                </button>
                <div class="min-w-0 flex-1">
                    <h1 class="text-sm sm:text-base md:text-xl font-bold text-slate-900 font-heading leading-snug truncate sm:whitespace-normal">
                        @yield('page_title', 'Welcome back, Super Admin! 👋')
                    </h1>
                    <p class="text-xs text-slate-500 hidden md:block">Here's what's happening on your platform today.</p>
                </div>
            </div>

            <!-- Right Side Controls & Action Buttons -->
            <div class="flex items-center space-x-2 sm:space-x-3 flex-shrink-0">
                <div class="relative hidden lg:block w-64">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-3 text-slate-400"></i>
                    <input type="text" placeholder="Search agencies, clients..." 
                           class="w-full bg-slate-100 border border-slate-200 rounded-xl pl-10 pr-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-indigo-500">
                </div>

                <div class="hidden md:flex items-center space-x-2 px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600">
                    <i data-lucide="calendar" class="w-4 h-4 text-slate-500"></i>
                    <span>{{ date('M d, Y') }}</span>
                </div>

                <a href="{{ route('admin.profile.edit') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 transition-colors flex items-center space-x-1 text-xs font-bold" title="Currency">
                    <span class="text-indigo-600 font-bold">{{ \App\Models\Setting::getCurrencySymbol() }}</span>
                </a>

                @yield('header_actions')

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2 px-3 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 transition-colors flex items-center space-x-1.5 text-xs font-bold shadow-sm" title="Logout">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mx-4 sm:mx-8 mt-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-2">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-4 sm:mx-8 mt-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-2">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Main Body Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-8">
            @yield('content')
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
