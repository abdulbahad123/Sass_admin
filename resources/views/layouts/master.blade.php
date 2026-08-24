<!DOCTYPE html>
<html lang="en" class="h-full bg-[#F8FAFC] text-slate-800">
<head>
    @php
        $currentAgency = auth()->user()->agency ?? \App\Models\Agency::where('type', 'master')->first();
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Master Agency Dashboard') - {{ $currentAgency->name ?? 'Apex Master Ventures' }}</title>

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
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px 0 rgba(0, 0, 0, 0.02);
        }
        @if(isset($currentAgency->primary_color) && $currentAgency->primary_color)
            .bg-indigo-600 {
                background-color: {{ $currentAgency->primary_color }} !important;
            }
            .hover\:bg-indigo-700:hover {
                background-color: {{ $currentAgency->primary_color }}dd !important;
            }
            .text-indigo-600, .text-indigo-400 {
                color: {{ $currentAgency->primary_color }} !important;
            }
            .border-indigo-500, .border-indigo-600 {
                border-color: {{ $currentAgency->primary_color }} !important;
            }
        @endif
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

    <!-- Left Dark Navigation Sidebar -->
    <aside :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed lg:static inset-y-0 left-0 w-64 sidebar-dark text-slate-300 flex flex-col flex-shrink-0 z-50 transition-transform duration-300 ease-in-out border-r border-slate-800">
        
        <!-- Logo Header -->
        <div class="h-20 px-5 flex items-center justify-between border-b border-slate-800/80">
            <div class="flex items-center space-x-3 min-w-0">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 via-violet-600 to-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-600/30 flex-shrink-0">
                    <i data-lucide="hexagon" class="w-6 h-6 text-white"></i>
                </div>
                <div class="truncate">
                    <span class="font-heading font-bold text-sm text-white tracking-tight block leading-tight truncate">{{ $currentAgency->name ?? 'Apex Master Ventures' }}</span>
                    <span class="inline-block px-1.5 py-0.5 mt-0.5 rounded text-[9px] font-extrabold uppercase tracking-wider bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">MASTER AGENCY PANEL</span>
                </div>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors" title="Switch to Super Admin">
                <i data-lucide="arrow-right-left" class="w-4 h-4"></i>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto custom-scrollbar">
            
            <!-- 1. Dashboard -->
            <a href="{{ route('master.dashboard') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('master.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <div class="flex items-center">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 mr-3"></i>
                    <span>Dashboard</span>
                </div>
            </a>

            <!-- 2. Agencies -->
            <a href="{{ route('master.sub-agencies.index') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('master.sub-agencies.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <div class="flex items-center">
                    <i data-lucide="building-2" class="w-4 h-4 mr-3"></i>
                    <span>Agencies</span>
                </div>
            </a>

            <!-- 3. Products & Access -->
            <a href="{{ route('master.products.index') }}" 
               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('master.products.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i data-lucide="box" class="w-4 h-4 mr-3"></i>
                <span>Products & Access</span>
            </a>

            <!-- 4. Plans & Pricing -->
            <a href="{{ route('master.plans.index') }}" 
               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('master.plans.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i data-lucide="check-square" class="w-4 h-4 mr-3"></i>
                <span>Plans & Pricing</span>
            </a>

            <!-- 5. Subscriptions -->
            <a href="{{ route('master.subscriptions.index') }}" 
               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('master.subscriptions.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i data-lucide="repeat" class="w-4 h-4 mr-3"></i>
                <span>Subscriptions</span>
            </a>

            <!-- 6. Billing & Invoices -->
            <a href="{{ route('master.billing.index') }}" 
               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('master.billing.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i data-lucide="receipt" class="w-4 h-4 mr-3"></i>
                <span>Billing & Invoices</span>
            </a>

            <!-- 7. Reports & Analytics -->
            <a href="{{ route('master.reports.index') }}" 
               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('master.reports.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i data-lucide="bar-chart-2" class="w-4 h-4 mr-3"></i>
                <span>Reports & Analytics</span>
            </a>

            <!-- 8. Custom Branding -->
            <a href="{{ route('master.branding.index') }}" 
               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('master.branding.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i data-lucide="palette" class="w-4 h-4 mr-3"></i>
                <span>Custom Branding</span>
            </a>

            <!-- 9. Team Members -->
            <a href="{{ route('master.team.index') }}" 
               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('master.team.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i data-lucide="user-check" class="w-4 h-4 mr-3"></i>
                <span>Team Members</span>
            </a>

            <!-- 10. Settings -->
            <a href="{{ route('master.settings.index') }}" 
               class="flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('master.settings.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                <i data-lucide="settings" class="w-4 h-4 mr-3"></i>
                <span>Settings</span>
            </a>

        </nav>

        <!-- Master Admin User Profile Card -->
        <div class="p-4 border-t border-slate-800/80 flex items-center justify-between">
            <div class="flex items-center space-x-3 min-w-0">
                <div class="w-9 h-9 rounded-full bg-indigo-600 text-white font-bold text-xs flex items-center justify-center ring-2 ring-indigo-500/40 flex-shrink-0">
                    {{ substr(auth()->user()->name ?? 'RS', 0, 2) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name ?? 'Rahul Sharma' }}</p>
                    <p class="text-[10px] text-slate-400 truncate">Master Admin</p>
                </div>
            </div>
            <div class="flex items-center space-x-1">
                <a href="{{ route('master.settings.index') }}" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors" title="Settings">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-colors" title="Logout">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Master Workspace -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#F8FAFC]">
        <!-- Top Header Navigation -->
        <header class="min-h-[4.5rem] bg-white border-b border-slate-200/80 px-4 sm:px-8 py-3 flex items-center justify-between gap-3 z-20">
            
            <div class="flex items-center space-x-2.5 sm:space-x-4 min-w-0 flex-1">
                <button @click="mobileMenuOpen = true" class="lg:hidden p-1.5 rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-none flex-shrink-0">
                    <i data-lucide="menu" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                </button>
                <div class="min-w-0 flex-1">
                    <h1 class="text-sm sm:text-base md:text-xl font-bold text-slate-900 font-heading leading-snug truncate">
                        Master Agency Dashboard 👋
                    </h1>
                    <p class="text-xs text-slate-500 hidden md:block">Welcome back, {{ auth()->user()->name ?? 'Rahul' }}! Here's what's happening with your network today.</p>
                </div>
            </div>

            <!-- Top Header Controls & Buttons -->
            <div class="flex items-center space-x-2 sm:space-x-3 flex-shrink-0">
                <div class="relative hidden lg:block w-64">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-3 text-slate-400"></i>
                    <input type="text" placeholder="Search agencies, invoices..." 
                           class="w-full bg-slate-100 border border-slate-200 rounded-xl pl-10 pr-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-indigo-500">
                </div>

                <!-- Notification Bell with Badge -->
                <button class="relative p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 transition-colors">
                    <i data-lucide="bell" class="w-4 h-4"></i>
                    <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-indigo-600 text-white font-bold text-[9px] flex items-center justify-center">8</span>
                </button>

                <!-- Date Range Picker Pill -->
                <div class="hidden sm:flex items-center space-x-2 px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700">
                    <i data-lucide="calendar" class="w-4 h-4 text-slate-500"></i>
                    <span>Aug 20 – Aug 26, 2026</span>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400"></i>
                </div>

                <!-- Primary Action Button (Add Agency) -->
                <a href="{{ route('master.sub-agencies.index') }}" 
                   class="px-2.5 py-1.5 sm:px-4 sm:py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 flex items-center space-x-1.5 sm:space-x-2 transition-all whitespace-nowrap">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Add Agency</span>
                </a>

                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="px-2.5 py-1.5 sm:px-3.5 sm:py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 rounded-xl text-xs font-bold shadow-sm flex items-center space-x-1.5 transition-all whitespace-nowrap" title="Logout">
                        <i data-lucide="log-out" class="w-4 h-4 text-rose-600"></i>
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-8">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600"></i>
                        <span class="font-semibold">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
