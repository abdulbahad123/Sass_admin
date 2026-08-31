<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'White Label Dashboard') - {{ $agency->name ?? 'Apex Digital Agency' }}</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Google Fonts: Plus Jakarta Sans & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        .card-white {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02), 0 1px 2px -1px rgba(0, 0, 0, 0.02);
        }
        /* Custom Scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-[#f8fafc] text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" onclick="toggleMobileSidebar()" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm hidden lg:hidden transition-opacity"></div>

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Navigation (Fixed width 270px, Dark Black Background) -->
        <aside id="mainSidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-[270px] bg-slate-950 border-r border-slate-800/80 text-slate-300 flex flex-col justify-between transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shrink-0 overflow-y-auto">
            
            <div class="p-5 space-y-6">
                
                <!-- Brand Header -->
                <div class="flex items-center justify-between pb-2 border-b border-slate-800/60">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white font-bold flex items-center justify-center shadow-lg shadow-blue-600/30">
                            <i data-lucide="layers" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="font-extrabold text-white font-heading text-base leading-snug tracking-tight">
                                {{ $agency->name ?? 'Apex Digital Agency' }}
                            </h2>
                            <div class="flex items-center text-[11px] text-slate-400 font-semibold cursor-pointer hover:text-slate-200 transition">
                                <span>White Label Dashboard</span>
                                <i data-lucide="chevron-down" class="w-3 h-3 ml-1"></i>
                            </div>
                        </div>
                    </div>
                    <button onclick="toggleMobileSidebar()" class="lg:hidden text-slate-400 hover:text-white p-1">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <!-- Main Active Dashboard Button (Blue background when active) -->
                <div>
                    <a href="{{ route('whitelabel.dashboard') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-2xl font-bold text-xs transition-all duration-200 shadow-md {{ request()->routeIs('whitelabel.dashboard') ? 'bg-blue-600 text-white shadow-blue-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <!-- Nav Group 1: MANAGE -->
                <div class="space-y-1">
                    <p class="px-4 text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-2">Manage</p>
                    
                    <a href="{{ route('whitelabel.clients.index') }}" 
                       class="flex items-center justify-between px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('whitelabel.clients.*') ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            <span>Clients</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ request()->routeIs('whitelabel.clients.*') ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-300' }}">
                            {{ \App\Models\User::where('agency_id', $agency->id ?? 0)->where('role', 'client')->count() }}
                        </span>
                    </a>

                    <a href="{{ route('whitelabel.products.index') }}" 
                       class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('whitelabel.products.*') ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="box" class="w-4 h-4"></i>
                        <span>Products & Access</span>
                    </a>

                    <a href="{{ route('whitelabel.subscriptions.index') }}" 
                       class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('whitelabel.subscriptions.*') ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="repeat" class="w-4 h-4"></i>
                        <span>Subscriptions</span>
                    </a>

                    <a href="{{ route('whitelabel.team.index') }}" 
                       class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('whitelabel.team.*') ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="user-check" class="w-4 h-4"></i>
                        <span>Team Members</span>
                    </a>

                    <a href="{{ route('whitelabel.activity-logs.index') }}" 
                       class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('whitelabel.activity-logs.*') ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="activity" class="w-4 h-4"></i>
                        <span>Activity Logs</span>
                    </a>
                </div>

                <!-- Nav Group 2: BILLING & SUPPORT -->
                <div class="space-y-1 pt-3 border-t border-slate-800/80">
                    <p class="px-4 text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-2">Billing & Support</p>

                    <a href="{{ route('whitelabel.billing.index') }}" 
                       class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('whitelabel.billing.*') ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="receipt" class="w-4 h-4"></i>
                        <span>Billing & Invoices</span>
                    </a>

                    <a href="{{ route('whitelabel.tickets.index') }}" 
                       class="flex items-center justify-between px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('whitelabel.tickets.*') ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="help-circle" class="w-4 h-4"></i>
                            <span>Support Tickets</span>
                        </div>
                        @php 
                            $agencyId = auth()->user()->agency_id ?? 0;
                            $openAgencyCount = \App\Models\Ticket::where('agency_id', $agencyId)->whereIn('status', ['open', 'in_progress', 'pending_reply'])->count(); 
                        @endphp
                        @if($openAgencyCount > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-500/20 text-blue-400">
                                {{ $openAgencyCount }}
                            </span>
                        @endif
                    </a>
                </div>

                <!-- Nav Group 3: WEBSITE & LANDING PAGE -->
                <div class="space-y-1 pt-3 border-t border-slate-800/80">
                    <p class="px-4 text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-2">Website Management</p>

                    <!-- Website Dropdown Header -->
                    <div x-data="{ open: {{ request()->routeIs('whitelabel.website.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('whitelabel.website.*') ? 'bg-slate-900 text-white font-bold' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <i data-lucide="globe" class="w-4 h-4 text-blue-400"></i>
                                <span>Website</span>
                            </div>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>

                        <!-- Sub-navigation Items -->
                        <div x-show="open" x-cloak class="mt-1 pl-4 space-y-1 border-l-2 border-slate-800 ml-5 py-1">
                            <a href="{{ route('whitelabel.website.landing') }}" 
                               class="flex items-center space-x-2 px-3 py-1.5 rounded-lg text-[11px] font-medium transition-all {{ request()->routeIs('whitelabel.website.landing') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <i data-lucide="layout" class="w-3.5 h-3.5"></i>
                                <span>Landing Page</span>
                            </a>
                            <a href="{{ route('whitelabel.website.about') }}" 
                               class="flex items-center space-x-2 px-3 py-1.5 rounded-lg text-[11px] font-medium transition-all {{ request()->routeIs('whitelabel.website.about') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <i data-lucide="info" class="w-3.5 h-3.5"></i>
                                <span>About</span>
                            </a>
                            <a href="{{ route('whitelabel.website.services') }}" 
                               class="flex items-center space-x-2 px-3 py-1.5 rounded-lg text-[11px] font-medium transition-all {{ request()->routeIs('whitelabel.website.services') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <i data-lucide="grid" class="w-3.5 h-3.5"></i>
                                <span>Services</span>
                            </a>
                            <a href="{{ route('whitelabel.website.testimonials') }}" 
                               class="flex items-center space-x-2 px-3 py-1.5 rounded-lg text-[11px] font-medium transition-all {{ request()->routeIs('whitelabel.website.testimonials') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <i data-lucide="message-square" class="w-3.5 h-3.5"></i>
                                <span>Testimonials</span>
                            </a>
                            <a href="{{ route('whitelabel.website.faq') }}" 
                               class="flex items-center space-x-2 px-3 py-1.5 rounded-lg text-[11px] font-medium transition-all {{ request()->routeIs('whitelabel.website.faq') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <i data-lucide="help-circle" class="w-3.5 h-3.5"></i>
                                <span>FAQ</span>
                            </a>
                            <a href="{{ route('whitelabel.website.contact') }}" 
                               class="flex items-center space-x-2 px-3 py-1.5 rounded-lg text-[11px] font-medium transition-all {{ request()->routeIs('whitelabel.website.contact') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                <span>Contact</span>
                            </a>
                            <a href="{{ route('whitelabel.website.privacy') }}" 
                               class="flex items-center space-x-2 px-3 py-1.5 rounded-lg text-[11px] font-medium transition-all {{ request()->routeIs('whitelabel.website.privacy') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                                <span>Privacy Policy</span>
                            </a>
                            <a href="{{ route('whitelabel.website.terms') }}" 
                               class="flex items-center space-x-2 px-3 py-1.5 rounded-lg text-[11px] font-medium transition-all {{ request()->routeIs('whitelabel.website.terms') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                <span>Terms & Conditions</span>
                            </a>
                            <a href="{{ route('whitelabel.website.shipping') }}" 
                               class="flex items-center space-x-2 px-3 py-1.5 rounded-lg text-[11px] font-medium transition-all {{ request()->routeIs('whitelabel.website.shipping') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                                <span>Shipping Policy</span>
                            </a>
                            <a href="{{ route('whitelabel.website.refund') }}" 
                               class="flex items-center space-x-2 px-3 py-1.5 rounded-lg text-[11px] font-medium transition-all {{ request()->routeIs('whitelabel.website.refund') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                                <span>Refund & Cancellation</span>
                            </a>
                            <a href="{{ route('whitelabel.website.cookies') }}" 
                               class="flex items-center space-x-2 px-3 py-1.5 rounded-lg text-[11px] font-medium transition-all {{ request()->routeIs('whitelabel.website.cookies') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <i data-lucide="cookie" class="w-3.5 h-3.5"></i>
                                <span>Cookie Policy</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Nav Group 4: CONFIGURATION -->
                <div class="space-y-1 pt-3 border-t border-slate-800/80">
                    <p class="px-4 text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-2">Configuration</p>

                    <a href="{{ route('whitelabel.branding.index') }}" 
                       class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('whitelabel.branding.*') ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                        <span>Branding & Settings</span>
                    </a>

                    <a href="{{ route('whitelabel.ai-settings.index') }}" 
                       class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('whitelabel.ai-settings.*') ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }}">
                        <i data-lucide="cpu" class="w-4 h-4 text-purple-400"></i>
                        <span>AI Engine & API Keys</span>
                    </a>
                </div>

            </div>

            <!-- Sidebar User Profile Footer with Logout -->
            <div class="p-4 border-t border-slate-800/80 flex items-center justify-between">
                <div class="flex items-center space-x-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold flex items-center justify-center text-xs shadow-md shrink-0">
                        {{ substr(auth()->user()->name ?? ($user->name ?? 'Rahul Sharma'), 0, 2) }}
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name ?? ($user->name ?? 'Rahul Sharma') }}</p>
                        <p class="text-[10px] text-slate-400 truncate">Agency Owner</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-colors" title="Logout">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>

        </aside>

        <!-- Main Content View Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top Navbar -->
            <header class="h-16 bg-white border-b border-slate-200/80 px-4 sm:px-8 flex items-center justify-between shrink-0 z-10">
                
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <button onclick="toggleMobileSidebar()" class="lg:hidden text-slate-500 hover:text-slate-800 p-2 rounded-xl bg-slate-100">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>

                    <!-- Client Filter Dropdown -->
                    <div class="hidden sm:block">
                        <select class="bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-100 transition focus:outline-none cursor-pointer">
                            <option>All Clients</option>
                            <option>TechNova Solutions</option>
                            <option>PixelCraft Studio</option>
                            <option>GreenLeaf Retail</option>
                            <option>NextGen Creators</option>
                        </select>
                    </div>

                    <!-- Search Input -->
                    <div class="relative w-48 sm:w-80">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-3 text-slate-400"></i>
                        <input type="text" placeholder="Search clients, invoices, etc..." 
                               class="w-full bg-slate-50 border border-slate-200/90 rounded-2xl pl-10 pr-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div class="flex items-center space-x-3 sm:space-x-4">
                    
                    <!-- Notification Bell -->
                    <button class="relative p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-rose-500 text-white text-[9px] font-extrabold flex items-center justify-center shadow-md">8</span>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div class="flex items-center space-x-3 pl-2 border-l border-slate-200">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold flex items-center justify-center text-xs shadow-md border-2 border-white">
                            {{ substr(auth()->user()->name ?? ($user->name ?? 'Rahul Sharma'), 0, 2) }}
                        </div>
                        <div class="hidden md:block text-left">
                            <h4 class="text-xs font-bold text-slate-900 font-heading leading-tight">{{ auth()->user()->name ?? ($user->name ?? 'Rahul Sharma') }}</h4>
                            <p class="text-[10px] text-slate-400 font-medium">Agency Owner</p>
                        </div>
                    </div>

                    @yield('header_actions')

                    <!-- Header Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 rounded-xl text-xs font-bold shadow-sm flex items-center space-x-1.5 transition-all whitespace-nowrap" title="Logout">
                            <i data-lucide="log-out" class="w-4 h-4 text-rose-600"></i>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>

                </div>

            </header>

            <!-- Scrollable Page Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-8 space-y-8">
                
                @if(session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800"><i data-lucide="x" class="w-4 h-4"></i></button>
                    </div>
                @endif

                @yield('content')

                <!-- Footer (Matching Reference Image) -->
                <footer class="pt-8 pb-4 text-center text-xs text-slate-400 font-medium border-t border-slate-200/60 mt-12">
                    © 2026 Apex Digital Agency. All rights reserved.
                </footer>
            </main>
        </div>

    </div>

    @stack('modals')

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
