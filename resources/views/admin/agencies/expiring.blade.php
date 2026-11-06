@extends('layouts.admin')

@section('title', 'Whitelabel Expiry & Storage')
@section('page_title', 'Whitelabel Subscriptions Expiry & Storage Management')

@section('header_actions')
<div class="flex items-center space-x-2">
    <button type="button" 
            onclick="triggerBatchDailyAlerts()" 
            id="btn-batch-alerts"
            class="px-3 py-2 sm:px-4 sm:py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-amber-500/25 flex items-center space-x-1.5 sm:space-x-2 transition-all whitespace-nowrap">
        <i data-lucide="bell-ring" class="w-4 h-4"></i>
        <span>Trigger Daily Alerts Now</span>
    </button>

    <a href="{{ route('admin.agencies.expiring') }}" 
       class="px-3 py-2 sm:px-4 sm:py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold flex items-center space-x-1.5 transition-all">
        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
        <span class="hidden sm:inline">Refresh</span>
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Overview Stats Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <!-- 1. Total White Labels -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                <i data-lucide="building-2" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-500 block">Total Whitelabels</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 leading-tight">{{ $metrics['total_whitelabels'] }}</span>
            </div>
        </div>

        <!-- 2. Expiring Soon -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-amber-200 shadow-sm flex items-center space-x-4 bg-amber-50/20">
            <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-amber-700 block">Expiring Soon</span>
                <span class="text-xl sm:text-2xl font-black text-amber-900 leading-tight">{{ $metrics['expiring_soon_count'] }}</span>
            </div>
        </div>

        <!-- 3. Already Expired -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-rose-200 shadow-sm flex items-center space-x-4 bg-rose-50/20">
            <div class="w-11 h-11 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center flex-shrink-0">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-rose-700 block">Expired</span>
                <span class="text-xl sm:text-2xl font-black text-rose-900 leading-tight">{{ $metrics['expired_count'] }}</span>
            </div>
        </div>

        <!-- 4. Alerts Due Today -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-blue-200 shadow-sm flex items-center space-x-4 bg-blue-50/20">
            <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0">
                <i data-lucide="send" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-blue-700 block">Alerts Due Today</span>
                <span class="text-xl sm:text-2xl font-black text-blue-900 leading-tight">{{ $metrics['alerts_due_count'] }}</span>
            </div>
        </div>

        <!-- 5. Total Storage -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-200 shadow-sm flex items-center space-x-4 bg-emerald-50/20 col-span-2 md:col-span-1">
            <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
                <i data-lucide="hard-drive" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-emerald-700 block">Total Storage Used</span>
                <span class="text-lg sm:text-xl font-black text-emerald-900 leading-tight">{{ $metrics['total_storage_formatted'] }}</span>
            </div>
        </div>
    </div>

    <!-- Alert Schedule Rules Banner -->
    <div class="bg-gradient-to-r from-indigo-900 via-slate-900 to-indigo-950 text-white rounded-2xl p-4 sm:p-5 shadow-sm border border-indigo-800/50">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-500/20 border border-indigo-400/30 flex items-center justify-center text-amber-300 flex-shrink-0">
                    <i data-lucide="calendar-clock" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-white">Daily Recurring Expiry Alert Policy</h4>
                    <p class="text-xs text-slate-300">Automated multi-channel alerts are dispatched daily via WhatsApp and Email based on the white-label plan tier:</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2 text-[11px] font-bold">
                <span class="px-2.5 py-1 rounded-lg bg-indigo-500/20 border border-indigo-400/40 text-indigo-200">
                    <i data-lucide="check" class="w-3 h-3 inline mr-1 text-emerald-400"></i> 1-Month Plan: Daily alerts 7 days before end
                </span>
                <span class="px-2.5 py-1 rounded-lg bg-indigo-500/20 border border-indigo-400/40 text-indigo-200">
                    <i data-lucide="check" class="w-3 h-3 inline mr-1 text-emerald-400"></i> 6-Month Plan: Daily alerts 15 days before end
                </span>
                <span class="px-2.5 py-1 rounded-lg bg-indigo-500/20 border border-indigo-400/40 text-indigo-200">
                    <i data-lucide="check" class="w-3 h-3 inline mr-1 text-emerald-400"></i> Yearly Plan: Daily alerts 30 days (1 mo) before end
                </span>
            </div>
        </div>
    </div>

    <!-- Filter Tabs & Search Bar -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
        <div class="flex items-center space-x-1.5 overflow-x-auto pb-1 sm:pb-0">
            <a href="{{ route('admin.agencies.expiring') }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $filter === 'all' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200' }}">
                All Whitelabels ({{ $metrics['total_whitelabels'] }})
            </a>
            <a href="{{ route('admin.agencies.expiring', ['filter' => 'alert_due']) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $filter === 'alert_due' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200' }}">
                Due Today ({{ $metrics['alerts_due_count'] }})
            </a>
            <a href="{{ route('admin.agencies.expiring', ['filter' => 'expiring_soon']) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $filter === 'expiring_soon' ? 'bg-amber-600 text-white shadow-md shadow-amber-600/30' : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200' }}">
                Expiring Soon ({{ $metrics['expiring_soon_count'] }})
            </a>
            <a href="{{ route('admin.agencies.expiring', ['filter' => 'expired']) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $filter === 'expired' ? 'bg-rose-600 text-white shadow-md shadow-rose-600/30' : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200' }}">
                Expired ({{ $metrics['expired_count'] }})
            </a>
            <a href="{{ route('admin.agencies.expiring', ['filter' => 'high_storage']) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $filter === 'high_storage' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200' }}">
                High Storage (&ge;75%)
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.agencies.expiring') }}" class="relative w-full sm:w-72">
            @if($filter !== 'all')
                <input type="hidden" name="filter" value="{{ $filter }}">
            @endif
            <input type="text" 
                   name="search" 
                   value="{{ $search }}" 
                   placeholder="Search agency, domain, phone..." 
                   class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none shadow-sm">
            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-2.5 pointer-events-none"></i>
            @if($search)
                <a href="{{ route('admin.agencies.expiring', ['filter' => $filter]) }}" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 text-xs font-bold">&times;</a>
            @endif
        </form>
    </div>

    <!-- Whitelabel Expiry & Storage Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4">Whitelabel Agency</th>
                        <th class="py-3.5 px-4">Owner & Contact</th>
                        <th class="py-3.5 px-4">Plan & Term</th>
                        <th class="py-3.5 px-4">Expiry & Countdown</th>
                        <th class="py-3.5 px-4">Storage Consumption</th>
                        <th class="py-3.5 px-4">Alert Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($enrichedAgencies as $item)
                        @php
                            $agency = $item->agency;
                            $exp = $item->expiry;
                            $stg = $item->storage;

                            $cleanPhone = preg_replace('/[^0-9]/', '', $agency->phone ?? '');
                            if (str_starts_with($cleanPhone, '0') && strlen($cleanPhone) === 11) {
                                $cleanPhone = '91' . substr($cleanPhone, 1);
                            } elseif (strlen($cleanPhone) === 10) {
                                $cleanPhone = '91' . $cleanPhone;
                            }
                            $waLink = !empty($cleanPhone) ? "https://wa.me/{$cleanPhone}" : null;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $exp['is_alert_due'] ? 'bg-amber-50/15' : '' }}">
                            
                            <!-- Agency Name & Domain -->
                            <td class="py-4 px-4 align-top">
                                <div class="flex items-start space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-700 font-extrabold flex items-center justify-center flex-shrink-0 text-sm">
                                        {{ substr($agency->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block text-sm leading-tight">{{ $agency->name }}</span>
                                        <span class="inline-block text-[10px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 mt-1">
                                            White Label Reseller
                                        </span>
                                        @if(!empty($agency->custom_domain))
                                            <a href="https://{{ $agency->clean_domain }}" target="_blank" class="block text-indigo-600 hover:text-indigo-800 text-[11px] font-semibold mt-1 flex items-center gap-1">
                                                <span>{{ $agency->clean_domain }}</span>
                                                <i data-lucide="external-link" class="w-3 h-3"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Owner & Contact -->
                            <td class="py-4 px-4 align-top">
                                <div class="space-y-1">
                                    <div class="font-bold text-slate-800">{{ $agency->owner_name ?: $agency->name }}</div>
                                    
                                    @if(!empty($agency->email))
                                        <div class="text-slate-500 flex items-center gap-1 text-[11px]">
                                            <i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400"></i>
                                            <span class="truncate max-w-[170px]">{{ $agency->email }}</span>
                                        </div>
                                    @endif

                                    @if(!empty($agency->phone))
                                        <div class="flex items-center gap-1.5 mt-1">
                                            @if($waLink)
                                                <a href="{{ $waLink }}" target="_blank" class="inline-flex items-center gap-1 text-emerald-700 font-bold text-[11px] hover:underline bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                                    <i data-lucide="message-circle" class="w-3 h-3 text-emerald-600"></i>
                                                    <span>{{ $agency->phone }}</span>
                                                </a>
                                            @else
                                                <span class="text-slate-600 text-[11px]">{{ $agency->phone }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Plan & Cycle -->
                            <td class="py-4 px-4 align-top">
                                <div class="space-y-1">
                                    <div class="font-bold text-slate-900">{{ $exp['plan_name'] }}</div>
                                    <div>
                                        @if($exp['billing_cycle'] === 'yearly')
                                            <span class="inline-flex items-center gap-1 text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 border border-purple-200">
                                                <i data-lucide="sparkles" class="w-3 h-3"></i> Yearly Plan
                                            </span>
                                        @elseif($exp['billing_cycle'] === '6_months')
                                            <span class="inline-flex items-center gap-1 text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 border border-blue-200">
                                                <i data-lucide="calendar" class="w-3 h-3"></i> 6-Month Plan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 border border-indigo-200">
                                                <i data-lucide="clock" class="w-3 h-3"></i> 1-Month Plan
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-slate-400">
                                        Alert window: <strong class="text-slate-600">&le; {{ $exp['threshold_days'] }} days</strong>
                                    </div>
                                </div>
                            </td>

                            <!-- Expiry & Countdown -->
                            <td class="py-4 px-4 align-top">
                                <div class="space-y-1">
                                    <div class="text-[11px] font-semibold text-slate-500">
                                        Ends: <strong class="text-slate-800">{{ $exp['ends_at']->format('d M, Y') }}</strong>
                                    </div>

                                    @if($exp['is_expired'])
                                        <span class="inline-flex items-center gap-1 text-[11px] font-black px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 border border-rose-300">
                                            <i data-lucide="alert-circle" class="w-3 h-3 text-rose-600"></i>
                                            {{ $exp['status_label'] }}
                                        </span>
                                    @elseif($exp['is_expiring_soon'])
                                        <span class="inline-flex items-center gap-1 text-[11px] font-black px-2.5 py-1 rounded-full bg-amber-100 text-amber-900 border border-amber-300 animate-pulse">
                                            <i data-lucide="bell" class="w-3 h-3 text-amber-600"></i>
                                            {{ $exp['status_label'] }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <i data-lucide="shield-check" class="w-3 h-3 text-emerald-600"></i>
                                            {{ $exp['status_label'] }}
                                        </span>
                                    @endif

                                    <div class="text-[10px] text-slate-400">
                                        Started: {{ $exp['starts_at']->format('d M, Y') }}
                                    </div>
                                </div>
                            </td>

                            <!-- Storage Consumption -->
                            <td class="py-4 px-4 align-top">
                                <div class="space-y-1.5 min-w-[190px]">
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="font-bold text-slate-800">{{ $stg['total_formatted'] }}</span>
                                        <span class="font-black text-{{ $stg['progress_color'] }}-600">{{ $stg['percent_used'] }}%</span>
                                    </div>

                                    <!-- Progress bar -->
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden border border-slate-200">
                                        <div class="h-2 rounded-full transition-all duration-300 
                                            {{ $stg['percent_used'] >= 90 ? 'bg-rose-500' : ($stg['percent_used'] >= 70 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                             style="width: {{ max(4, $stg['percent_used']) }}%;"></div>
                                    </div>

                                    <div class="flex items-center justify-between text-[10px] text-slate-500">
                                        <span title="Database Size"><i data-lucide="database" class="w-3 h-3 inline text-slate-400"></i> DB: {{ $stg['db_formatted'] }} ({{ $stg['db_percent'] ?? 0 }}%)</span>
                                        <span title="Files / Uploads"><i data-lucide="folder" class="w-3 h-3 inline text-slate-400"></i> Files: {{ $stg['files_formatted'] }} ({{ $stg['files_percent'] ?? 0 }}%)</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Alert Status -->
                            <td class="py-4 px-4 align-top">
                                <div class="space-y-1">
                                    @if($exp['is_alert_due'])
                                        <span class="inline-flex items-center gap-1 text-[11px] font-extrabold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                            <i data-lucide="alert-circle" class="w-3 h-3"></i> Daily Alert Due
                                        </span>
                                    @elseif($exp['sent_today'])
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <i data-lucide="check-circle" class="w-3 h-3"></i> Sent Today
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                            Not Due Yet
                                        </span>
                                    @endif

                                    @if($exp['last_alert_at'])
                                        <div class="text-[10px] text-slate-400">
                                            Last: {{ $exp['last_alert_at']->diffForHumans() }}
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-4 align-top text-right whitespace-nowrap">
                                <div class="flex items-center justify-end space-x-1.5">
                                    <!-- Send Alert Button -->
                                    <button type="button" 
                                            onclick="sendSingleAlert({{ $agency->id }}, '{{ addslashes($agency->name) }}')"
                                            id="btn-alert-{{ $agency->id }}"
                                            class="px-2.5 py-1.5 rounded-xl font-bold text-xs flex items-center space-x-1 transition shadow-sm
                                                {{ $exp['is_alert_due'] ? 'bg-amber-600 hover:bg-amber-700 text-white' : 'bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 border border-slate-200' }}">
                                        <i data-lucide="send" class="w-3.5 h-3.5"></i>
                                        <span>Send Alert</span>
                                    </button>

                                    <!-- One-Click Admin Access -->
                                    <a href="{{ route('admin.agencies.admin-login', $agency->id) }}" 
                                       target="_blank"
                                       title="Login as Whitelabel Admin"
                                       class="p-1.5 rounded-xl bg-slate-100 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 border border-slate-200 transition">
                                        <i data-lucide="key" class="w-3.5 h-3.5"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i data-lucide="inbox" class="w-10 h-10 mx-auto text-slate-300 mb-2"></i>
                                <p class="text-sm font-semibold">No white-label agencies match the selected filter criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($enrichedAgencies instanceof \Illuminate\Pagination\LengthAwarePaginator && $enrichedAgencies->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $enrichedAgencies->links() }}
            </div>
        @endif
    </div>
</div>

<!-- JavaScript for Interactive Dispatch & Feedback -->
<script type="text/javascript">
const csrfToken = '{{ csrf_token() }}';

// 1. Send Single Alert via AJAX
function sendSingleAlert(agencyId, agencyName) {
    if (!confirm('Send instant White-Label Expiry Alert via WhatsApp and Email to ' + agencyName + '?')) {
        return;
    }

    const btn = document.getElementById('btn-alert-' + agencyId);
    let originalHtml = '';
    if (btn) {
        btn.disabled = true;
        originalHtml = btn.innerHTML;
        btn.innerHTML = '<i data-lucide="loader" class="w-3.5 h-3.5 animate-spin mr-1"></i> Sending...';
        if (window.lucide) window.lucide.createIcons();
    }

    const baseUrlTemplate = '{{ route("admin.agencies.send-expiry-alert", ["agency" => "___AGENCY_ID___"]) }}';
    const url = baseUrlTemplate.replace('___AGENCY_ID___', agencyId);

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ _token: csrfToken })
    })
    .then(res => res.json())
    .then(data => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            if (window.lucide) window.lucide.createIcons();
        }

        if (data.success) {
            alert('âœ… ' + data.message);
            window.location.reload();
        } else {
            alert('âš ï¸ ' + (data.message || data.error || 'Failed to dispatch alert.'));
        }
    })
    .catch(err => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            if (window.lucide) window.lucide.createIcons();
        }
        alert('Network error while dispatching alert: ' + err.message);
    });
}

// 2. Trigger Batch Daily Alerts
function triggerBatchDailyAlerts() {
    if (!confirm('Run the daily recurring alert check now?\n\nThis will evaluate all active White-Label plans and send WhatsApp + Email alerts to all due agencies today.')) {
        return;
    }

    const btn = document.getElementById('btn-batch-alerts');
    let originalHtml = '';
    if (btn) {
        btn.disabled = true;
        originalHtml = btn.innerHTML;
        btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin mr-1"></i> Evaluating & Dispatching...';
        if (window.lucide) window.lucide.createIcons();
    }

    const url = '{{ route("admin.agencies.trigger-daily-alerts") }}';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ _token: csrfToken })
    })
    .then(res => res.json())
    .then(data => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            if (window.lucide) window.lucide.createIcons();
        }

        if (data.success) {
            alert('âœ… ' + data.message);
            window.location.reload();
        } else {
            alert('âš ï¸ ' + (data.message || data.error || 'Batch alert process failed.'));
        }
    })
    .catch(err => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            if (window.lucide) window.lucide.createIcons();
        }
        alert('Batch process encountered a network error: ' + err.message);
    });
}
</script>
@endsection
