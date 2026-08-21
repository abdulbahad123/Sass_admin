@extends('layouts.whitelabel')

@section('title', 'White Label Agency Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Header Greeting -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 font-heading flex items-center">
            Welcome back, {{ $user->name ?? ($agency->owner_name ?? 'Rahul') }}! 👋
        </h1>
        <p class="text-xs text-slate-500 mt-1">Here's what's happening with {{ $agency->name ?? 'your agency' }} today.</p>
    </div>

    <!-- 1. Top 5 Stat Cards (Exact Match to Reference Image) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
        
        <!-- Stat 1: Total Clients -->
        <div class="card-white rounded-2xl p-5 hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-2xl bg-violet-100 text-violet-600 flex items-center justify-center font-bold">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <div class="text-right">
                    <p class="text-[11px] font-semibold text-slate-500">Total Clients</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 font-heading mt-0.5">{{ number_format($totalClients) }}</h3>
                </div>
            </div>
            <div class="flex items-center justify-between mt-3 text-[11px]">
                <span class="text-emerald-600 font-bold flex items-center">
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-0.5"></i> ↑ 18.5%
                </span>
                <span class="text-slate-400">vs last 7 days</span>
            </div>
            <div class="mt-2 h-6">
                <svg class="w-full h-full text-violet-500" viewBox="0 0 100 20" fill="none">
                    <path d="M0,15 L20,12 L40,16 L60,8 L80,14 L100,5" stroke="currentColor" stroke-width="2" fill="none" />
                </svg>
            </div>
        </div>

        <!-- Stat 2: Active Clients -->
        <div class="card-white rounded-2xl p-5 hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                </div>
                <div class="text-right">
                    <p class="text-[11px] font-semibold text-slate-500">Active Clients</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 font-heading mt-0.5">{{ number_format($activeClients) }}</h3>
                </div>
            </div>
            <div class="flex items-center justify-between mt-3 text-[11px]">
                <span class="text-emerald-600 font-bold flex items-center">
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-0.5"></i> ↑ 22.1%
                </span>
                <span class="text-slate-400">vs last 7 days</span>
            </div>
            <div class="mt-2 h-6">
                <svg class="w-full h-full text-emerald-500" viewBox="0 0 100 20" fill="none">
                    <path d="M0,18 L20,14 L40,15 L60,9 L80,12 L100,4" stroke="currentColor" stroke-width="2" fill="none" />
                </svg>
            </div>
        </div>

        <!-- Stat 3: Monthly Revenue -->
        <div class="card-white rounded-2xl p-5 hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-base">
                    ₹
                </div>
                <div class="text-right">
                    <p class="text-[11px] font-semibold text-slate-500">Monthly Revenue</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 font-heading mt-0.5">₹{{ number_format($mrr) }}</h3>
                </div>
            </div>
            <div class="flex items-center justify-between mt-3 text-[11px]">
                <span class="text-emerald-600 font-bold flex items-center">
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-0.5"></i> ↑ 24.6%
                </span>
                <span class="text-slate-400">vs last 7 days</span>
            </div>
            <div class="mt-2 h-6">
                <svg class="w-full h-full text-amber-500" viewBox="0 0 100 20" fill="none">
                    <path d="M0,16 L20,12 L40,14 L60,6 L80,10 L100,3" stroke="currentColor" stroke-width="2" fill="none" />
                </svg>
            </div>
        </div>

        <!-- Stat 4: Active Subscriptions -->
        <div class="card-white rounded-2xl p-5 hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-600 flex items-center justify-center font-bold">
                    <i data-lucide="layers" class="w-5 h-5"></i>
                </div>
                <div class="text-right">
                    <p class="text-[11px] font-semibold text-slate-500">Active Subscriptions</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 font-heading mt-0.5">{{ number_format($activeSubscriptions) }}</h3>
                </div>
            </div>
            <div class="flex items-center justify-between mt-3 text-[11px]">
                <span class="text-emerald-600 font-bold flex items-center">
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-0.5"></i> ↑ 15.3%
                </span>
                <span class="text-slate-400">vs last 7 days</span>
            </div>
            <div class="mt-2 h-6">
                <svg class="w-full h-full text-sky-500" viewBox="0 0 100 20" fill="none">
                    <path d="M0,17 L20,15 L40,11 L60,13 L80,8 L100,4" stroke="currentColor" stroke-width="2" fill="none" />
                </svg>
            </div>
        </div>

        <!-- Stat 5: Products In Use -->
        <div class="card-white rounded-2xl p-5 hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-2xl bg-pink-100 text-pink-600 flex items-center justify-center font-bold">
                    <i data-lucide="box" class="w-5 h-5"></i>
                </div>
                <div class="text-right">
                    <p class="text-[11px] font-semibold text-slate-500">Products In Use</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 font-heading mt-0.5">{{ $productsInUseCount }} / {{ $totalProductsCount }}</h3>
                </div>
            </div>
            <div class="flex items-center justify-between mt-3 text-[11px]">
                <span class="text-slate-600 font-bold">80% Utilization</span>
            </div>
            <div class="mt-3 w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                <div class="bg-gradient-to-r from-pink-500 to-rose-500 h-full w-[80%] rounded-full"></div>
            </div>
        </div>

    </div>

    <!-- 2. Middle Row 1: Revenue Overview + Product Usage Donut + Top Performing Products -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Revenue Overview Line Chart (5 Cols) -->
        <div class="lg:col-span-5 card-white rounded-2xl p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-base font-bold text-slate-900 font-heading">Revenue Overview</h3>
                    <select class="bg-slate-100 border border-slate-200 rounded-xl px-3 py-1 text-xs text-slate-700 font-semibold focus:outline-none cursor-pointer">
                        <option>This Month</option>
                        <option>This Year</option>
                    </select>
                </div>
                <div class="flex items-baseline space-x-2">
                    <span class="text-2xl font-extrabold text-slate-900 font-heading">₹3,42,800</span>
                    <span class="text-xs font-bold text-emerald-600 flex items-center">
                        <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-0.5"></i> ↑ 24.6% <span class="text-slate-400 font-normal ml-1">vs last month</span>
                    </span>
                </div>
            </div>

            <!-- Purple Smooth Revenue Line Chart -->
            <div class="h-60 mt-4 relative">
                <canvas id="wlRevenueChart"></canvas>
            </div>
        </div>

        <!-- Product Usage Donut Chart (4 Cols) -->
        <div class="lg:col-span-4 card-white rounded-2xl p-6 flex flex-col justify-between">
            <h3 class="text-base font-bold text-slate-900 font-heading mb-4">Product Usage</h3>
            
            <div class="h-44 relative flex items-center justify-center">
                <canvas id="wlProductDonutChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Total</span>
                    <span class="text-2xl font-extrabold text-slate-900 font-heading">{{ $totalClients }}</span>
                    <span class="text-[10px] text-slate-400 font-medium">Clients</span>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 space-y-2.5 text-xs">
                @foreach($productUsage as $pu)
                    <div class="flex items-center justify-between">
                        <span class="flex items-center text-slate-700 font-medium">
                            <span class="w-2.5 h-2.5 rounded-full mr-2" style="background-color: {{ $pu['color'] }}"></span> 
                            {{ $pu['name'] }}
                        </span>
                        <span class="font-bold text-slate-900">{{ $pu['clients'] }} <span class="text-slate-400 font-normal">({{ $pu['percentage'] }}%)</span></span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Performing Products Progress Bar List (3 Cols) -->
        <div class="lg:col-span-3 card-white rounded-2xl p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900 font-heading">Top Performing Products</h3>
                <select class="bg-slate-100 border border-slate-200 rounded-xl px-2 py-1 text-[11px] text-slate-700 font-semibold focus:outline-none">
                    <option>This Month</option>
                </select>
            </div>

            <div class="space-y-4">
                @foreach($productUsage as $pu)
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <div class="flex items-center space-x-2">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                    <i data-lucide="{{ $pu['icon'] }}" class="w-3.5 h-3.5"></i>
                                </div>
                                <div>
                                    <span class="font-bold text-slate-900 block leading-tight text-xs">{{ $pu['name'] }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $pu['clients'] }} Clients</span>
                                </div>
                            </div>
                            <span class="font-bold text-slate-900 text-xs">{{ $pu['progress'] }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                            <div class="h-full rounded-full" style="width: {{ $pu['progress'] }}%; background-color: {{ $pu['color'] }};"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- 3. Middle Row 2: Recent Clients Table + Recent Activity Stream -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Recent Clients Table (7 Cols) -->
        <div class="lg:col-span-7 card-white rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-slate-900 font-heading">Recent Clients</h3>
                <a href="{{ route('whitelabel.clients.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-xl transition">
                    View All Clients
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                            <th class="pb-3">Client Name</th>
                            <th class="pb-3">Products</th>
                            <th class="pb-3">Plan</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3">Joined On</th>
                            <th class="pb-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($recentClients as $client)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 font-bold text-slate-900">
                                    <div class="flex items-center space-x-2.5">
                                        <div class="w-7 h-7 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold flex items-center justify-center text-[10px] shadow-sm">
                                            <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                                        </div>
                                        <span>{{ $client['name'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5">
                                    <div class="flex items-center space-x-1.5">
                                        @foreach($client['products'] as $icon)
                                            <div class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                                <i data-lucide="{{ $icon }}" class="w-3 h-3"></i>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-3.5 text-slate-600 font-medium">{{ $client['plan'] }}</td>
                                <td class="py-3.5">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-{{ $client['status_color'] }}-100 text-{{ $client['status_color'] }}-700">
                                        {{ $client['status'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 text-slate-500 font-medium text-[11px]">{{ $client['joined'] }}</td>
                                <td class="py-3.5 text-right text-slate-400">
                                    <button class="p-1 hover:text-slate-700">
                                        <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 text-xs font-medium">
                                    No end-clients onboarded yet. Click <strong>+ Add New Client</strong> to add your first client!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activity Feed (5 Cols) -->
        <div class="lg:col-span-5 card-white rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-slate-900 font-heading">Recent Activity</h3>
                <a href="{{ route('whitelabel.activity-logs.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-xl transition">
                    View All Activity
                </a>
            </div>

            <div class="space-y-4">
                @forelse($recentActivities as $act)
                    <div class="flex items-start space-x-3 text-xs p-1">
                        <div class="w-8 h-8 rounded-xl {{ $act['bg_color'] }} {{ $act['text_color'] }} flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i data-lucide="{{ $act['icon'] }}" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-slate-800 leading-tight">
                                {{ $act['title'] }}
                                @if($act['amount'])
                                    <span class="font-extrabold text-emerald-600 ml-1">{{ $act['amount'] }}</span>
                                @endif
                            </p>
                        </div>
                        <span class="text-[10px] text-slate-400 whitespace-nowrap">{{ $act['time'] }}</span>
                    </div>
                @empty
                    <div class="py-6 text-center text-slate-400 text-xs font-medium">
                        No recent activity recorded yet.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- 4. Bottom Row: Subscription Status + Client Distribution Map + Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Subscription Status (4 Cols) -->
        <div class="lg:col-span-4 card-white rounded-2xl p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900 font-heading">Subscription Status</h3>
                <select class="bg-slate-100 border border-slate-200 rounded-xl px-2.5 py-1 text-xs text-slate-700 font-semibold focus:outline-none">
                    <option>This Month</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <!-- Active -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                    </div>
                    <span class="block text-[11px] font-bold text-slate-500">Active</span>
                    <span class="block text-xl font-extrabold text-slate-900 font-heading mt-0.5">{{ $subscriptionStatus['active'] }}</span>
                    <span class="inline-block text-[10px] font-bold text-emerald-600 mt-1">↑ {{ $subscriptionStatus['active_change'] }}</span>
                </div>

                <!-- Expiring Soon -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                    <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-2">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                    <span class="block text-[11px] font-bold text-slate-500">Expiring Soon</span>
                    <span class="block text-xl font-extrabold text-slate-900 font-heading mt-0.5">{{ $subscriptionStatus['expiring'] }}</span>
                    <span class="inline-block text-[10px] font-bold text-rose-600 mt-1">↓ {{ $subscriptionStatus['expiring_change'] }}</span>
                </div>

                <!-- Cancelled -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                    <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-2">
                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                    </div>
                    <span class="block text-[11px] font-bold text-slate-500">Cancelled</span>
                    <span class="block text-xl font-extrabold text-slate-900 font-heading mt-0.5">{{ $subscriptionStatus['cancelled'] }}</span>
                    <span class="inline-block text-[10px] font-bold text-rose-600 mt-1">↓ {{ $subscriptionStatus['cancelled_change'] }}</span>
                </div>

                <!-- Trial -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                    <div class="w-8 h-8 rounded-full bg-violet-100 text-violet-600 flex items-center justify-center mx-auto mb-2">
                        <i data-lucide="flask-conical" class="w-4 h-4"></i>
                    </div>
                    <span class="block text-[11px] font-bold text-slate-500">Trial</span>
                    <span class="block text-xl font-extrabold text-slate-900 font-heading mt-0.5">{{ $subscriptionStatus['trial'] }}</span>
                    <span class="inline-block text-[10px] font-bold text-emerald-600 mt-1">↑ {{ $subscriptionStatus['trial_change'] }}</span>
                </div>
            </div>
        </div>

        <!-- Client Distribution Map Graphic (4 Cols) -->
        <div class="lg:col-span-4 card-white rounded-2xl p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-base font-bold text-slate-900 font-heading">Client Distribution</h3>
                <select class="bg-slate-100 border border-slate-200 rounded-xl px-2.5 py-1 text-xs text-slate-700 font-semibold focus:outline-none">
                    <option>This Month</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                <!-- SVG World Map graphic -->
                <div class="sm:col-span-7 h-32 flex items-center justify-center">
                    <svg class="w-full h-full text-indigo-200 opacity-80" viewBox="0 0 200 100" fill="currentColor">
                        <!-- Simplified World Map Polygons -->
                        <path d="M20 30 Q30 20 45 35 T70 40 T50 70 T20 50 Z" class="text-indigo-300" />
                        <path d="M90 25 Q110 15 130 30 T150 45 T120 75 T90 50 Z" class="text-indigo-400" />
                        <path d="M140 60 Q160 50 185 65 T170 85 Z" class="text-indigo-300" />
                        <!-- Pin Markers -->
                        <circle cx="110" cy="45" r="3.5" class="fill-indigo-600 animate-ping" />
                        <circle cx="110" cy="45" r="3" class="fill-indigo-600" />
                        <circle cx="45" cy="35" r="3" class="fill-violet-600" />
                        <circle cx="130" cy="30" r="3" class="fill-purple-600" />
                        <circle cx="165" cy="70" r="3" class="fill-indigo-500" />
                    </svg>
                </div>

                <!-- Right Legend Breakdown -->
                <div class="sm:col-span-5 space-y-1.5 text-xs">
                    @foreach($clientDistribution as $dist)
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="flex items-center text-slate-600 font-medium">
                                <span class="w-2 h-2 rounded-full mr-1.5" style="background-color: {{ $dist['color'] }}"></span>
                                {{ $dist['country'] }}
                            </span>
                            <span class="font-bold text-slate-900">{{ $dist['count'] }} <span class="text-slate-400 font-normal text-[10px]">({{ $dist['percentage'] }}%)</span></span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Quick Actions Grid (4 Cols) -->
        <div class="lg:col-span-4 card-white rounded-2xl p-6 flex flex-col justify-between">
            <h3 class="text-base font-bold text-slate-900 font-heading mb-4">Quick Actions</h3>

            <div class="grid grid-cols-3 gap-3">
                <button onclick="document.getElementById('addClientModal').classList.remove('hidden')" 
                        class="p-3 rounded-2xl bg-slate-50 hover:bg-indigo-50 border border-slate-100 hover:border-indigo-200 transition-all text-left group flex flex-col items-center justify-center text-center space-y-1.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-100 group-hover:bg-indigo-600 text-indigo-600 group-hover:text-white flex items-center justify-center transition-colors">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-700 group-hover:text-indigo-700 leading-tight">Add New Client</span>
                </button>

                <a href="{{ route('whitelabel.products.index') }}" 
                   class="p-3 rounded-2xl bg-slate-50 hover:bg-sky-50 border border-slate-100 hover:border-sky-200 transition-all text-left group flex flex-col items-center justify-center text-center space-y-1.5">
                    <div class="w-8 h-8 rounded-xl bg-sky-100 group-hover:bg-sky-600 text-sky-600 group-hover:text-white flex items-center justify-center transition-colors">
                        <i data-lucide="box" class="w-4 h-4"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-700 group-hover:text-sky-700 leading-tight">Assign Products</span>
                </a>

                <a href="{{ route('whitelabel.billing.index') }}" 
                   class="p-3 rounded-2xl bg-slate-50 hover:bg-emerald-50 border border-slate-100 hover:border-emerald-200 transition-all text-left group flex flex-col items-center justify-center text-center space-y-1.5">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 group-hover:bg-emerald-600 text-emerald-600 group-hover:text-white flex items-center justify-center transition-colors">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-700 group-hover:text-emerald-700 leading-tight">Create Invoice</span>
                </a>

                <a href="{{ route('whitelabel.activity-logs.index') }}" 
                   class="p-3 rounded-2xl bg-slate-50 hover:bg-purple-50 border border-slate-100 hover:border-purple-200 transition-all text-left group flex flex-col items-center justify-center text-center space-y-1.5">
                    <div class="w-8 h-8 rounded-xl bg-purple-100 group-hover:bg-purple-600 text-purple-600 group-hover:text-white flex items-center justify-center transition-colors">
                        <i data-lucide="activity" class="w-4 h-4"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-700 group-hover:text-purple-700 leading-tight">Activity Logs</span>
                </a>

                <a href="{{ route('whitelabel.team.index') }}" 
                   class="p-3 rounded-2xl bg-slate-50 hover:bg-pink-50 border border-slate-100 hover:border-pink-200 transition-all text-left group flex flex-col items-center justify-center text-center space-y-1.5">
                    <div class="w-8 h-8 rounded-xl bg-pink-100 group-hover:bg-pink-600 text-pink-600 group-hover:text-white flex items-center justify-center transition-colors">
                        <i data-lucide="user-check" class="w-4 h-4"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-700 group-hover:text-pink-700 leading-tight">Add Team Member</span>
                </a>

                <a href="{{ route('whitelabel.branding.index') }}" 
                   class="p-3 rounded-2xl bg-slate-50 hover:bg-amber-50 border border-slate-100 hover:border-amber-200 transition-all text-left group flex flex-col items-center justify-center text-center space-y-1.5">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 group-hover:bg-amber-600 text-amber-600 group-hover:text-white flex items-center justify-center transition-colors">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-700 group-hover:text-amber-700 leading-tight">Agency Settings</span>
                </a>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Revenue Line Chart matching reference image
        const ctxRev = document.getElementById('wlRevenueChart').getContext('2d');
        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: ['Aug 1', 'Aug 6', 'Aug 11', 'Aug 16', 'Aug 21', 'Aug 26', 'Aug 31'],
                datasets: [{
                    label: 'Revenue',
                    data: [15000, 10000, 18000, 14000, 32000, 24000, 342800],
                    borderColor: '#6366F1',
                    backgroundColor: 'rgba(99, 102, 241, 0.12)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: [4, 4, 4, 4, 6, 4, 5],
                    pointBackgroundColor: '#6366F1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94A3B8', font: { size: 10 } } },
                    y: { grid: { color: '#F1F5F9' }, ticks: { color: '#94A3B8', font: { size: 10 }, callback: value => '₹' + (value >= 1000 ? (value/1000) + 'K' : value) } }
                }
            }
        });

        // Product Usage Donut Chart matching reference image
        const ctxDonut = document.getElementById('wlProductDonutChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Launchshop', 'Smart CRM', 'WebBuilder Pro', 'AppConnect'],
                datasets: [{
                    data: [128, 96, 72, 28],
                    backgroundColor: ['#6366F1', '#0EA5E9', '#10B981', '#F59E0B'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '76%'
            }
        });
    });
</script>
@endpush
