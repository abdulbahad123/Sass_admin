@extends('layouts.admin')

@section('title', 'Website Builder Limits Management')
@section('page_title', 'Website Builder White-Label Limits')

@section('header_actions')
<a href="{{ $wbAdminLaunchUrl }}" target="_blank" 
   class="px-4 py-2.5 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 flex items-center space-x-2 transition-all">
    <i data-lucide="external-link" class="w-4 h-4"></i>
    <span>Launch Website Builder Admin</span>
</a>
@endsection

@section('content')
<div class="space-y-8">

    <!-- Flash message -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center space-x-2 shadow-sm">
            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Top Banner -->
    <div class="p-6 rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border border-slate-800">
        <div class="flex items-start space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-400/30 flex items-center justify-center text-indigo-400 font-bold text-xl flex-shrink-0">
                <i data-lucide="sliders" class="w-6 h-6"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold font-heading">Website Builder Agency Feature Limits</h2>
                <p class="text-xs text-slate-300 mt-1 max-w-2xl">
                    Configure base global defaults (100 Services, 100 Portfolio Projects, 300 Blog Articles) applied to all white-label agencies, and allocate custom limits to any specific white-label partner.
                </p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-3 py-1.5 rounded-full text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 flex items-center space-x-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Active Synchronization</span>
            </span>
        </div>
    </div>

    <!-- Dual Config Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Card 1: Platform Global Defaults -->
        <div class="card-white rounded-3xl p-6 sm:p-8 flex flex-col justify-between border border-slate-200 shadow-sm relative">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                            <i data-lucide="globe" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 font-heading">1. Platform Global Defaults</h3>
                            <p class="text-xs text-slate-500">Base quotas automatically granted to all white-labels</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        Default Baseline
                    </span>
                </div>

                <form action="{{ route('admin.website-builder-limits.update-defaults') }}" method="POST" class="space-y-4 mt-6">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Default Services Limit
                        </label>
                        <div class="relative">
                            <input type="number" name="default_services_limit" min="1" value="{{ $defaultServicesLimit }}" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                            <span class="absolute right-4 top-3.5 text-xs font-semibold text-slate-400">services</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Default number of service items agency clients can list.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Default Portfolio Projects Limit
                        </label>
                        <div class="relative">
                            <input type="number" name="default_portfolio_limit" min="1" value="{{ $defaultPortfolioLimit }}" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                            <span class="absolute right-4 top-3.5 text-xs font-semibold text-slate-400">projects</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Default portfolio showcases agency clients can publish.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Default Blog Articles Limit
                        </label>
                        <div class="relative">
                            <input type="number" name="default_blog_limit" min="1" value="{{ $defaultBlogLimit }}" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                            <span class="absolute right-4 top-3.5 text-xs font-semibold text-slate-400">articles</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Default blog publications included in standard agency plan.</p>
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center space-x-2">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            <span>Save Platform Global Defaults</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card 2: White-Label Agency Selector & Custom Limits -->
        <div class="card-white rounded-3xl p-6 sm:p-8 flex flex-col justify-between border border-slate-200 shadow-sm relative">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-violet-50 border border-violet-100 flex items-center justify-center text-violet-600">
                            <i data-lucide="building-2" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 font-heading">2. White-Label Agency Custom Limits</h3>
                            <p class="text-xs text-slate-500">Assign extra capacity on top of global defaults</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-violet-50 text-violet-700 border border-violet-200">
                        Custom Allocation
                    </span>
                </div>

                <!-- Agency Selector Dropdown -->
                <div class="mb-5">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Select White-Label Agency
                    </label>
                    <select id="agencySelect" onchange="window.location.href = '{{ route('admin.website-builder-limits.index') }}?agency_id=' + this.value"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                        @foreach($agencies as $a)
                            <option value="{{ $a->id }}" {{ $selectedAgencyId == $a->id ? 'selected' : '' }}>
                                {{ $a->name }} ({{ $a->custom_domain ?? ($a->slug . '.saasreselling.com') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($selectedAgency)
                    @php
                        $curLimits = $agencyLimits[$selectedAgency->id] ?? null;
                    @endphp
                    <form action="{{ route('admin.website-builder-limits.update-agency') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="agency_id" value="{{ $selectedAgency->id }}">

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-600">
                                    Extra Services Limit
                                </label>
                                <span class="text-[11px] font-bold text-indigo-600">
                                    Total: {{ $curLimits['total_services'] ?? $defaultServicesLimit }} services
                                </span>
                            </div>
                            <div class="relative">
                                <input type="number" name="extra_services_limit" min="0" value="{{ $curLimits['extra_services'] ?? 0 }}" required
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                                <span class="absolute right-4 top-3.5 text-xs font-semibold text-slate-400">+ extra services</span>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-600">
                                    Extra Portfolio Projects Limit
                                </label>
                                <span class="text-[11px] font-bold text-indigo-600">
                                    Total: {{ $curLimits['total_portfolio'] ?? $defaultPortfolioLimit }} projects
                                </span>
                            </div>
                            <div class="relative">
                                <input type="number" name="extra_portfolio_limit" min="0" value="{{ $curLimits['extra_portfolio'] ?? 0 }}" required
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                                <span class="absolute right-4 top-3.5 text-xs font-semibold text-slate-400">+ extra projects</span>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-semibold uppercase tracking-wider text-slate-600">
                                    Extra Blog Articles Limit
                                </label>
                                <span class="text-[11px] font-bold text-indigo-600">
                                    Total: {{ $curLimits['total_blog'] ?? $defaultBlogLimit }} articles
                                </span>
                            </div>
                            <div class="relative">
                                <input type="number" name="extra_blog_limit" min="0" value="{{ $curLimits['extra_blog'] ?? 0 }}" required
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                                <span class="absolute right-4 top-3.5 text-xs font-semibold text-slate-400">+ extra articles</span>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" 
                                    class="w-full py-3 px-4 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-violet-600/30 transition-all flex items-center justify-center space-x-2">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                <span>Save Limits for {{ $selectedAgency->name }}</span>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Section 3: Agencies Limits Directory Table -->
    <div class="card-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900 font-heading">White-Label Agencies Limit Directory</h3>
                <p class="text-xs text-slate-500 mt-0.5">Overview of base global limits and custom allocated limits per partner</p>
            </div>
            <span class="text-xs font-bold text-slate-400">{{ count($agencies) }} Agencies</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 font-semibold uppercase tracking-wider text-[10px]">
                        <th class="pb-3 pl-2">Agency Partner</th>
                        <th class="pb-3">Type</th>
                        <th class="pb-3">Services Limit</th>
                        <th class="pb-3">Portfolio Limit</th>
                        <th class="pb-3">Blog Articles</th>
                        <th class="pb-3 text-right pr-2">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($agencies as $ag)
                        @php
                            $rowLim = $agencyLimits[$ag->id] ?? null;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $selectedAgencyId == $ag->id ? 'bg-indigo-50/50' : '' }}">
                            <td class="py-3.5 pl-2 font-bold text-slate-900">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center font-bold text-slate-700 text-xs">
                                        {{ strtoupper(substr($ag->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $ag->name }}</div>
                                        <div class="text-[11px] text-slate-400 font-mono">{{ $ag->custom_domain ?? ($ag->slug . '.saasreselling.com') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $ag->type === 'white_label' ? 'bg-indigo-50 text-indigo-700' : 'bg-violet-50 text-violet-700' }}">
                                    {{ $ag->type === 'white_label' ? 'White Label' : 'Master Agency' }}
                                </span>
                            </td>
                            <td class="py-3.5">
                                <span class="font-bold text-slate-900">{{ $rowLim['total_services'] ?? $defaultServicesLimit }}</span>
                                @if(!empty($rowLim['extra_services']))
                                    <span class="text-[10px] text-emerald-600 font-bold ml-1">(+{{ $rowLim['extra_services'] }})</span>
                                @endif
                            </td>
                            <td class="py-3.5">
                                <span class="font-bold text-slate-900">{{ $rowLim['total_portfolio'] ?? $defaultPortfolioLimit }}</span>
                                @if(!empty($rowLim['extra_portfolio']))
                                    <span class="text-[10px] text-emerald-600 font-bold ml-1">(+{{ $rowLim['extra_portfolio'] }})</span>
                                @endif
                            </td>
                            <td class="py-3.5">
                                <span class="font-bold text-slate-900">{{ $rowLim['total_blog'] ?? $defaultBlogLimit }}</span>
                                @if(!empty($rowLim['extra_blog']))
                                    <span class="text-[10px] text-emerald-600 font-bold ml-1">(+{{ $rowLim['extra_blog'] }})</span>
                                @endif
                            </td>
                            <td class="py-3.5 text-right pr-2">
                                <a href="{{ route('admin.website-builder-limits.index', ['agency_id' => $ag->id]) }}" 
                                   class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs transition-colors">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    <span>Configure</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">No agencies registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection