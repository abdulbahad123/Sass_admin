@extends('layouts.master')

@section('title', 'Master Agency Dashboard')

@section('content')
<div class="space-y-8">

    <!-- 1. Top 5 Stat Cards (Exact Match to Reference Image) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
        
        <!-- Stat 1: Total Agencies -->
        <div class="card-white rounded-2xl p-5 hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-2xl bg-violet-100 text-violet-600 flex items-center justify-center font-bold">
                    <i data-lucide="building-2" class="w-5 h-5"></i>
                </div>
                <div class="text-right">
                    <p class="text-[11px] font-semibold text-slate-500">Total Agencies</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 font-heading mt-0.5">{{ number_format($totalSubAgencies) }}</h3>
                </div>
            </div>
            <div class="flex items-center justify-between mt-3 text-[11px]">
                <span class="text-emerald-600 font-bold flex items-center">
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-0.5"></i> ↑ 20%
                </span>
                <span class="text-slate-400">vs last 7 days</span>
            </div>
            <div class="mt-2 h-6">
                <svg class="w-full h-full text-violet-500" viewBox="0 0 100 20" fill="none">
                    <path d="M0,15 L20,12 L40,16 L60,8 L80,14 L100,5" stroke="currentColor" stroke-width="2" fill="none" />
                </svg>
            </div>
        </div>

        <!-- Stat 2: Total Clients -->
        <div class="card-white rounded-2xl p-5 hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-600 flex items-center justify-center font-bold">
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
                <svg class="w-full h-full text-sky-500" viewBox="0 0 100 20" fill="none">
                    <path d="M0,18 L20,14 L40,15 L60,9 L80,12 L100,4" stroke="currentColor" stroke-width="2" fill="none" />
                </svg>
            </div>
        </div>

        <!-- Stat 3: Monthly Recurring Revenue -->
        <div class="card-white rounded-2xl p-5 hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-base">
                    ₹
                </div>
                <div class="text-right">
                    <p class="text-[11px] font-semibold text-slate-500">Monthly Recurring Revenue</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 font-heading mt-0.5">₹{{ number_format($mrr) }}</h3>
                </div>
            </div>
            <div class="flex items-center justify-between mt-3 text-[11px]">
                <span class="text-emerald-600 font-bold flex items-center">
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-0.5"></i> ↑ 25.6%
                </span>
                <span class="text-slate-400">vs last 7 days</span>
            </div>
            <div class="mt-2 h-6">
                <svg class="w-full h-full text-emerald-500" viewBox="0 0 100 20" fill="none">
                    <path d="M0,16 L20,12 L40,14 L60,6 L80,10 L100,3" stroke="currentColor" stroke-width="2" fill="none" />
                </svg>
            </div>
        </div>

        <!-- Stat 4: Active Subscriptions -->
        <div class="card-white rounded-2xl p-5 hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
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
                <svg class="w-full h-full text-amber-500" viewBox="0 0 100 20" fill="none">
                    <path d="M0,17 L20,15 L40,11 L60,13 L80,8 L100,4" stroke="currentColor" stroke-width="2" fill="none" />
                </svg>
            </div>
        </div>

        <!-- Stat 5: Products in Use -->
        <div class="card-white rounded-2xl p-5 hover:shadow-md transition-shadow relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-2xl bg-pink-100 text-pink-600 flex items-center justify-center font-bold">
                    <i data-lucide="box" class="w-5 h-5"></i>
                </div>
                <div class="text-right">
                    <p class="text-[11px] font-semibold text-slate-500">Products in Use</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 font-heading mt-0.5">{{ $productsInUseCount }} / {{ $totalProductsCount }}</h3>
                </div>
            </div>
            <div class="flex items-center justify-between mt-3 text-[11px]">
                <span class="text-emerald-600 font-bold flex items-center">
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-0.5"></i> ↑ 80% Adoption
                </span>
            </div>
            <div class="mt-4 w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                <div class="bg-gradient-to-r from-violet-600 to-pink-500 h-full w-[80%] rounded-full"></div>
            </div>
        </div>

    </div>

    <!-- 2. Middle Row: Revenue Overview + Sub-Agencies by Plan + Top Performing Products -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Revenue Overview Line Chart (5 Cols) -->
        <div class="lg:col-span-5 card-white rounded-2xl p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-base font-bold text-slate-900 font-heading">Revenue Overview</h3>
                    <select class="bg-slate-100 border border-slate-200 rounded-xl px-3 py-1 text-xs text-slate-700 font-semibold focus:outline-none">
                        <option>This Month</option>
                        <option>This Year</option>
                    </select>
                </div>
                <div class="flex items-baseline space-x-2">
                    <span class="text-2xl font-extrabold text-slate-900 font-heading">₹{{ number_format($mrr) }}</span>
                    <span class="text-xs font-bold text-emerald-600 flex items-center">
                        <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-0.5"></i> ↑ {{ $mrr > 0 ? '10%' : '0%' }} <span class="text-slate-400 font-normal ml-1">vs last 7 days</span>
                    </span>
                </div>
            </div>

            <div class="h-60 mt-4">
                <canvas id="masterRevenueChart"></canvas>
            </div>
        </div>

        <!-- Agencies by Plan Donut (4 Cols) -->
        <div class="lg:col-span-4 card-white rounded-2xl p-6 flex flex-col justify-between">
            <h3 class="text-base font-bold text-slate-900 font-heading mb-4">Agencies by Plan</h3>
            
            <div class="h-44 relative flex items-center justify-center">
                <canvas id="planDonutChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-3xl font-extrabold text-slate-900 font-heading">{{ $totalSubAgencies }}</span>
                    <span class="text-[10px] uppercase font-bold text-slate-400">Total</span>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 space-y-2 text-xs">
                @php
                    $denom = $totalSubAgencies > 0 ? $totalSubAgencies : 1;
                @endphp
                <div class="flex items-center justify-between">
                    <span class="flex items-center text-slate-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 mr-2"></span> Enterprise
                    </span>
                    <span class="font-bold text-slate-900">{{ $planCounts['Enterprise'] }} <span class="text-slate-400 font-normal">({{ round(($planCounts['Enterprise'] / $denom) * 100, 1) }}%)</span></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="flex items-center text-slate-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-sky-500 mr-2"></span> Growth
                    </span>
                    <span class="font-bold text-slate-900">{{ $planCounts['Growth'] }} <span class="text-slate-400 font-normal">({{ round(($planCounts['Growth'] / $denom) * 100, 1) }}%)</span></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="flex items-center text-slate-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 mr-2"></span> Starter
                    </span>
                    <span class="font-bold text-slate-900">{{ $planCounts['Starter'] }} <span class="text-slate-400 font-normal">({{ round(($planCounts['Starter'] / $denom) * 100, 1) }}%)</span></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="flex items-center text-slate-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 mr-2"></span> Trial
                    </span>
                    <span class="font-bold text-slate-900">{{ $planCounts['Trial'] }} <span class="text-slate-400 font-normal">({{ round(($planCounts['Trial'] / $denom) * 100, 1) }}%)</span></span>
                </div>
            </div>
        </div>

        <!-- Top Performing Products Progress List (3 Cols) -->
        <div class="lg:col-span-3 card-white rounded-2xl p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900 font-heading">Top Performing Products</h3>
                <select class="bg-slate-100 border border-slate-200 rounded-xl px-2 py-1 text-[11px] text-slate-700 font-semibold">
                    <option>This Month</option>
                </select>
            </div>

            <div class="space-y-4">
                @foreach($productUsage as $pu)
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <div class="flex items-center space-x-2">
                                <div class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                    <i data-lucide="box" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="font-bold text-slate-900 block leading-tight">{{ $pu['name'] }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $pu['clients'] }} Clients</span>
                                </div>
                            </div>
                            <span class="font-bold text-slate-900">{{ $pu['percentage'] }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-indigo-600 h-full rounded-full" style="width: {{ $pu['percentage'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- 3. Bottom Row: Recent Sub-Agencies Table + Recent Activity Stream -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Recent Agencies Table (8 Cols) -->
        <div class="lg:col-span-8 card-white rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-900 font-heading">Recent Agencies</h3>
                    <p class="text-xs text-slate-500">Network reseller agencies onboarded under {{ $agency->name ?? 'your Master Agency' }}</p>
                </div>
                <a href="{{ route('master.sub-agencies.index') }}" class="px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-xs font-bold transition-colors">
                    View All
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            <th class="pb-3">Agency Name</th>
                            <th class="pb-3">Owner</th>
                            <th class="pb-3">Plan</th>
                            <th class="pb-3">Clients</th>
                            <th class="pb-3">MRR</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($subAgenciesList as $agencyItem)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 font-bold text-slate-900">
                                    <div class="flex items-center space-x-2.5">
                                        <div class="w-7 h-7 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-[10px]">
                                            {{ substr($agencyItem['name'], 0, 1) }}
                                        </div>
                                        <span>{{ $agencyItem['name'] }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 text-slate-600 font-medium">{{ $agencyItem['owner'] }}</td>
                                <td class="py-3.5">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-{{ $agencyItem['plan_color'] }}-100 text-{{ $agencyItem['plan_color'] }}-700">
                                        {{ $agencyItem['plan'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 font-bold text-slate-900">{{ $agencyItem['clients'] }}</td>
                                <td class="py-3.5 font-extrabold text-slate-900">₹{{ number_format($agencyItem['mrr']) }}</td>
                                <td class="py-3.5">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-{{ $agencyItem['status_color'] }}-100 text-{{ $agencyItem['status_color'] }}-700">
                                        {{ $agencyItem['status'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 text-right text-slate-400">
                                    <button class="p-1 hover:text-slate-700">
                                        <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400 text-xs font-medium">
                                    No sub-agencies onboarded yet under {{ $agency->name ?? 'this master agency' }}. 
                                    <a href="{{ route('master.sub-agencies.index') }}" class="text-indigo-600 font-bold underline ml-1">+ Onboard Sub-Agency</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activity Feed (4 Cols) -->
        <div class="lg:col-span-4 card-white rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-slate-900 font-heading">Recent Activity</h3>
                <a href="#" class="px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-xs font-bold transition-colors">
                    View All
                </a>
            </div>

            <div class="space-y-4">
                @forelse($activities as $act)
                    <div class="flex items-start space-x-3 text-xs">
                        <div class="w-8 h-8 rounded-xl bg-{{ $act['color'] }}-100 text-{{ $act['color'] }}-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i data-lucide="{{ $act['icon'] }}" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-slate-800 leading-tight">{{ $act['title'] }}</p>
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

    <!-- 4. Very Bottom Section: Product Usage Across Network + Subscription Status -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Product Usage Across Network (6 Cols) -->
        <div class="lg:col-span-6 card-white rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900 font-heading">Product Usage Across Network</h3>
                <select class="bg-slate-100 border border-slate-200 rounded-xl px-2.5 py-1 text-xs text-slate-700 font-semibold">
                    <option>This Month</option>
                </select>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach($productUsage as $pu)
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                        <div class="w-8 h-8 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center mx-auto mb-2">
                            <i data-lucide="box" class="w-4 h-4"></i>
                        </div>
                        <span class="block text-xs font-bold text-slate-900">{{ $pu['name'] }}</span>
                        <span class="block text-xs text-slate-500 font-medium mt-0.5">{{ $pu['clients'] }} <span class="text-[10px] text-slate-400">Clients</span></span>
                        <span class="inline-block mt-1 text-[10px] font-bold text-emerald-600">{{ $pu['percentage'] }}%</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Subscription Status Grid (6 Cols) -->
        <div class="lg:col-span-6 card-white rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900 font-heading">Subscription Status</h3>
                <select class="bg-slate-100 border border-slate-200 rounded-xl px-2.5 py-1 text-xs text-slate-700 font-semibold">
                    <option>This Month</option>
                </select>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                    </div>
                    <span class="block text-[11px] font-bold text-slate-600">Active</span>
                    <span class="block text-xl font-extrabold text-slate-900 font-heading mt-0.5">{{ $subscriptionStats['active'] }}</span>
                    <span class="inline-block text-[10px] font-bold text-emerald-600 mt-1">Active</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                    <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-2">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                    <span class="block text-[11px] font-bold text-slate-600">Expiring Soon</span>
                    <span class="block text-xl font-extrabold text-slate-900 font-heading mt-0.5">{{ $subscriptionStats['expiring'] }}</span>
                    <span class="inline-block text-[10px] font-bold text-amber-600 mt-1">Pending</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                    <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-2">
                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                    </div>
                    <span class="block text-[11px] font-bold text-slate-600">Cancelled</span>
                    <span class="block text-xl font-extrabold text-slate-900 font-heading mt-0.5">{{ $subscriptionStats['cancelled'] }}</span>
                    <span class="inline-block text-[10px] font-bold text-rose-600 mt-1">Cancelled</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                    <div class="w-8 h-8 rounded-full bg-violet-100 text-violet-600 flex items-center justify-center mx-auto mb-2">
                        <i data-lucide="flask-conical" class="w-4 h-4"></i>
                    </div>
                    <span class="block text-[11px] font-bold text-slate-600">Trial</span>
                    <span class="block text-xl font-extrabold text-slate-900 font-heading mt-0.5">{{ $subscriptionStats['trial'] }}</span>
                    <span class="inline-block text-[10px] font-bold text-violet-600 mt-1">Trial</span>
                </div>
            </div>
        </div>
                    <span class="inline-block text-[10px] font-bold text-emerald-600 mt-1">↑ 10%</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Master Revenue Line Chart
        const ctxRev = document.getElementById('masterRevenueChart').getContext('2d');
        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: ['Aug 20', 'Aug 21', 'Aug 22', 'Aug 23', 'Aug 24', 'Aug 25', 'Aug 26'],
                datasets: [{
                    label: 'Revenue',
                    data: {{ $mrr > 0 ? "[0, 0, 0, 0, $mrr]" : "[0, 0, 0, 0, 0]" }},
                    borderColor: '#6366F1',
                    backgroundColor: 'rgba(99, 102, 241, 0.12)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94A3B8' } },
                    y: { grid: { color: '#F1F5F9' }, ticks: { color: '#94A3B8', callback: value => '₹' + value } }
                }
            }
        });

        // Plan Donut Chart
        const ctxDonut = document.getElementById('planDonutChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Enterprise', 'Growth', 'Starter', 'Trial'],
                datasets: [{
                    data: {{ $totalSubAgencies > 0 ? "[" . implode(',', $planCounts) . "]" : "[0, 0, 0, 1]" }},
                    backgroundColor: ['#6366F1', '#0EA5E9', '#10B981', '#E2E8F0'],
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
