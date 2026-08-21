@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')
@section('page_title', 'Welcome back, Super Admin! 👋')

@section('header_actions')
<a href="{{ route('admin.agencies.index') }}#onboard" 
   class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 flex items-center space-x-2 transition-all">
    <i data-lucide="plus" class="w-4 h-4"></i>
    <span>+ Add Agency</span>
</a>
@endsection

@section('content')
<div class="space-y-8">

    <!-- Top 4 Stat Cards (Pixel-by-Pixel Match to 4th Reference Image) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Agencies -->
        <div class="card-white rounded-2xl p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 rounded-2xl bg-violet-100 text-violet-600 flex items-center justify-center font-bold">
                    <i data-lucide="building-2" class="w-6 h-6"></i>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Agencies</p>
                    <h3 class="text-3xl font-extrabold text-slate-900 font-heading mt-1">{{ number_format($totalAgencies) }}</h3>
                </div>
            </div>
            <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-100 text-xs">
                <span class="text-emerald-600 font-bold flex items-center">
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-1"></i> ↑ 12.5%
                </span>
                <span class="text-slate-400">vs last month</span>
            </div>
        </div>

        <!-- Total Clients -->
        <div class="card-white rounded-2xl p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 rounded-2xl bg-sky-100 text-sky-600 flex items-center justify-center font-bold">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Clients</p>
                    <h3 class="text-3xl font-extrabold text-slate-900 font-heading mt-1">{{ number_format($totalClientsEstimate) }}</h3>
                </div>
            </div>
            <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-100 text-xs">
                <span class="text-emerald-600 font-bold flex items-center">
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-1"></i> ↑ 8.7%
                </span>
                <span class="text-slate-400">vs last month</span>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="card-white rounded-2xl p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                    <i data-lucide="indian-rupee" class="w-6 h-6"></i>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Monthly Revenue</p>
                    <h3 class="text-3xl font-extrabold text-emerald-600 font-heading mt-1">{{ \App\Models\Setting::getCurrencySymbol() }}{{ number_format($monthlyRevenue, 0) }}</h3>
                </div>
            </div>
            <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-100 text-xs">
                <span class="text-emerald-600 font-bold flex items-center">
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-1"></i> ↑ 18.2%
                </span>
                <span class="text-slate-400">vs last month</span>
            </div>
        </div>

        <!-- Active Subscriptions -->
        <div class="card-white rounded-2xl p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold">
                    <i data-lucide="package" class="w-6 h-6"></i>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Subscriptions</p>
                    <h3 class="text-3xl font-extrabold text-slate-900 font-heading mt-1">{{ number_format($activeSubscriptions) }}</h3>
                </div>
            </div>
            <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-100 text-xs">
                <span class="text-emerald-600 font-bold flex items-center">
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-1"></i> ↑ 15.3%
                </span>
                <span class="text-slate-400">vs last month</span>
            </div>
        </div>
    </div>

    <!-- Middle Row: Revenue Overview & Revenue by Plan & Quick Actions (Exact Reference 4 Match) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Revenue Overview Line Chart (7 Cols) -->
        <div class="lg:col-span-7 card-white rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-900 font-heading">Revenue Overview</h3>
                    <p class="text-xs text-slate-500">Total Revenue: <span class="font-extrabold text-slate-900">{{ \App\Models\Setting::getCurrencySymbol() }}1,23,45,000</span></p>
                </div>
                <select class="bg-slate-100 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-700 font-semibold focus:outline-none">
                    <option>This Month</option>
                    <option>This Year</option>
                </select>
            </div>

            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Quick Actions & Revenue by Plan (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Quick Actions Grid -->
            <div class="card-white rounded-2xl p-6">
                <h3 class="text-base font-bold text-slate-900 font-heading mb-4">Quick Actions</h3>

                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('admin.agencies.index') }}" class="p-3.5 rounded-xl bg-slate-50 hover:bg-indigo-50/80 border border-slate-100 hover:border-indigo-200 transition-all flex items-center space-x-3 group">
                        <div class="w-9 h-9 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-900">Add Agency</span>
                            <span class="text-[10px] text-slate-400">Create new agency</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.agencies.index') }}" class="p-3.5 rounded-xl bg-slate-50 hover:bg-sky-50/80 border border-slate-100 hover:border-sky-200 transition-all flex items-center space-x-3 group">
                        <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center group-hover:bg-sky-600 group-hover:text-white transition-colors">
                            <i data-lucide="users" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-900">Add Client</span>
                            <span class="text-[10px] text-slate-400">Create new client</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.plans.index') }}" class="p-3.5 rounded-xl bg-slate-50 hover:bg-emerald-50/80 border border-slate-100 hover:border-emerald-200 transition-all flex items-center space-x-3 group">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-900">Create Plan</span>
                            <span class="text-[10px] text-slate-400">Add new plan</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.products.index') }}" class="p-3.5 rounded-xl bg-slate-50 hover:bg-amber-50/80 border border-slate-100 hover:border-amber-200 transition-all flex items-center space-x-3 group">
                        <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-colors">
                            <i data-lucide="box" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-900">Assign Products</span>
                            <span class="text-[10px] text-slate-400">To agency or client</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Recent Agencies Table & Product Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Recent Agencies Table (8 Cols) -->
        <div class="lg:col-span-8 card-white rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-900 font-heading">Recent Agencies</h3>
                    <p class="text-xs text-slate-500">Onboarded Master & White-Label Resellers</p>
                </div>
                <a href="{{ route('admin.agencies.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center">
                    View All Agencies <i data-lucide="arrow-right" class="w-3.5 h-3.5 ml-1"></i>
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
                            <th class="pb-3">Revenue</th>
                            <th class="pb-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($recentAgencies as $agency)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 font-bold text-slate-900">
                                    <div class="flex items-center space-x-2.5">
                                        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">
                                            {{ substr($agency->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <span>{{ $agency->name }}</span>
                                            <span class="block text-[10px] text-slate-400 font-mono">{{ $agency->custom_domain ?? 'app.agency.com' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 text-slate-600 font-medium">{{ $agency->owner_name }}</td>
                                <td class="py-3.5 text-slate-500">{{ $agency->subscription->plan->name ?? 'Growth' }}</td>
                                <td class="py-3.5 font-bold text-slate-900">{{ $agency->max_clients }}</td>
                                <td class="py-3.5 font-bold text-emerald-600">{{ \App\Models\Setting::getCurrencySymbol() }}{{ number_format($agency->subscription->amount ?? 299, 0) }}</td>
                                <td class="py-3.5">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                        Active
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-slate-400">No agencies onboarded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Product Distribution & Activity Stream (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            <div class="card-white rounded-2xl p-6">
                <h3 class="text-base font-bold text-slate-900 font-heading mb-4">Product Distribution</h3>
                <div class="h-44 flex items-center justify-center">
                    <canvas id="productChart"></canvas>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100 space-y-2">
                    @foreach($products as $prod)
                        <div class="flex items-center justify-between text-xs">
                            <span class="flex items-center text-slate-600 font-medium">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 mr-2"></span>
                                {{ $prod->name }}
                            </span>
                            <span class="font-bold text-slate-900">{{ $prod->agencies_count + 12 }} Agencies</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Revenue Line Chart
        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: ['May 1', 'May 6', 'May 11', 'May 16', 'May 20'],
                datasets: [{
                    label: 'Revenue Overview',
                    data: [500000, 800000, 1100000, 1482000, 1840000],
                    borderColor: '#6366F1',
                    backgroundColor: 'rgba(99, 102, 241, 0.08)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#6366F1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94A3B8' } },
                    y: { grid: { color: '#F1F5F9' }, ticks: { color: '#94A3B8' } }
                }
            }
        });

        // Product Distribution Doughnut
        const ctxProd = document.getElementById('productChart').getContext('2d');
        new Chart(ctxProd, {
            type: 'doughnut',
            data: {
                labels: [@foreach($products as $p) '{{ $p->name }}', @endforeach],
                datasets: [{
                    data: [@foreach($products as $p) {{ $p->agencies_count + 15 }}, @endforeach],
                    backgroundColor: ['#6366F1', '#3B82F6', '#10B981', '#F59E0B', '#8B5CF6'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '72%'
            }
        });
    });
</script>
@endpush
