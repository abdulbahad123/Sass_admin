@extends('layouts.admin')

@section('title', 'Whitelabel Copyright Manager')
@section('page_title', 'Whitelabel Copyright & Attribution')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Top Header Card -->
    <div class="card-white p-6 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4 border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center space-x-2">
                <span class="p-2 rounded-xl bg-indigo-50 text-indigo-600">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </span>
                <h2 class="text-2xl font-extrabold text-slate-900 font-heading tracking-tight">Whitelabel Copyright & Attribution Manager</h2>
            </div>
            <p class="text-xs text-slate-500 mt-1 max-w-2xl">
                Manage developer attribution (<strong>"Developed by Nooryak Technologies"</strong>) per whitelabel. If a client purchased the whitelabel license with the extra amount to remove Nooryak branding, switch their toggle to <strong>Hide Attribution</strong> below.
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.agencies.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center space-x-2">
                <i data-lucide="building-2" class="w-4 h-4"></i>
                <span>Manage Agencies</span>
            </a>
            <a href="{{ route('admin.landing-page.edit') }}" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-lg shadow-indigo-600/30 transition-all flex items-center space-x-2">
                <i data-lucide="globe" class="w-4 h-4"></i>
                <span>SaaSReselling Footer</span>
            </a>
        </div>
    </div>

    <!-- 3 Stat Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Total Whitelabels -->
        <div class="card-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Whitelabels</span>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $totalAgencies }}</h3>
                <span class="text-[11px] text-slate-500 mt-0.5 block">Active whitelabel partners</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i data-lucide="building" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Paid / Hidden Attribution -->
        <div class="card-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Attribution Hidden (Paid)</span>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $hiddenCount }}</h3>
                <span class="text-[11px] text-slate-500 mt-0.5 block">Nooryak link removed from footer</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i data-lucide="eye-off" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Visible Attribution -->
        <div class="card-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Attribution Visible</span>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $visibleCount }}</h3>
                <span class="text-[11px] text-slate-500 mt-0.5 block">Includes "Developed by Nooryak Technologies"</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center">
                <i data-lucide="eye" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="card-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Search Input -->
        <form method="GET" action="{{ route('admin.copyright.index') }}" class="w-full sm:w-80 relative">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search agency, domain, or email..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
        </form>

        <!-- Segmented Filter Tabs -->
        <div class="flex items-center space-x-2 bg-slate-100 p-1 rounded-xl text-xs font-bold text-slate-600">
            <a href="{{ route('admin.copyright.index', ['filter' => 'all', 'search' => $search]) }}" class="px-3 py-1.5 rounded-lg transition-all {{ $filter === 'all' ? 'bg-white text-indigo-600 shadow-sm' : 'hover:text-slate-900' }}">
                All ({{ $totalAgencies }})
            </a>
            <a href="{{ route('admin.copyright.index', ['filter' => 'hidden', 'search' => $search]) }}" class="px-3 py-1.5 rounded-lg transition-all {{ $filter === 'hidden' ? 'bg-white text-emerald-600 shadow-sm' : 'hover:text-slate-900' }}">
                Hidden ({{ $hiddenCount }})
            </a>
            <a href="{{ route('admin.copyright.index', ['filter' => 'visible', 'search' => $search]) }}" class="px-3 py-1.5 rounded-lg transition-all {{ $filter === 'visible' ? 'bg-white text-slate-900 shadow-sm' : 'hover:text-slate-900' }}">
                Visible ({{ $visibleCount }})
            </a>
        </div>
    </div>

    <!-- Whitelabel Agencies Table -->
    <div class="card-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">
                        <th class="py-3.5 px-5">Whitelabel Agency</th>
                        <th class="py-3.5 px-5">Domain / Live URL</th>
                        <th class="py-3.5 px-5">Contact Email</th>
                        <th class="py-3.5 px-5 text-center">Footer Attribution Status</th>
                        <th class="py-3.5 px-5 text-right">Hide Nooryak Attribution</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($agencies as $agency)
                    @php
                        $isHidden = !empty($agency->hide_developer_attribution);
                        $domainUrl = !empty($agency->custom_domain) ? (str_starts_with($agency->custom_domain, 'http') ? $agency->custom_domain : 'https://' . $agency->custom_domain) : 'https://' . $agency->slug . '.saasreselling.com';
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors" id="agency-row-{{ $agency->id }}">
                        <!-- Agency Name & Logo -->
                        <td class="py-4 px-5">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center flex-shrink-0">
                                    @if(!empty($agency->logo))
                                        <img src="{{ str_starts_with($agency->logo, 'http') ? $agency->logo : asset($agency->logo) }}" alt="{{ $agency->name }}" class="max-w-full max-height-full object-contain p-1">
                                    @else
                                        <span class="font-bold text-slate-500 uppercase text-xs">{{ substr($agency->name, 0, 2) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-extrabold text-slate-900 block text-sm">{{ $agency->name }}</span>
                                    <span class="text-[11px] text-slate-400 block font-mono">Slug: {{ $agency->slug }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Domain -->
                        <td class="py-4 px-5">
                            <a href="{{ $domainUrl }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-indigo-600 hover:text-indigo-800 flex items-center space-x-1.5 group">
                                <span class="group-hover:underline">{{ !empty($agency->custom_domain) ? $agency->custom_domain : $agency->slug . '.saasreselling.com' }}</span>
                                <i data-lucide="external-link" class="w-3.5 h-3.5 opacity-60 group-hover:opacity-100"></i>
                            </a>
                        </td>

                        <!-- Contact -->
                        <td class="py-4 px-5 text-slate-600">
                            {{ $agency->email ?? 'N/A' }}
                        </td>

                        <!-- Status Badge -->
                        <td class="py-4 px-5 text-center">
                            <span id="badge-{{ $agency->id }}" class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $isHidden ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $isHidden ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                <span id="badge-text-{{ $agency->id }}">{{ $isHidden ? 'Attribution Hidden (Paid)' : 'Attribution Visible' }}</span>
                            </span>
                        </td>

                        <!-- One-Click Toggle Switch -->
                        <td class="py-4 px-5 text-right">
                            <div class="inline-flex items-center space-x-3">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           id="toggle-{{ $agency->id }}"
                                           onchange="toggleAgencyAttribution({{ $agency->id }}, this.checked)"
                                           {{ $isHidden ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                </label>
                                <span id="label-{{ $agency->id }}" class="text-[11px] font-bold {{ $isHidden ? 'text-emerald-600' : 'text-slate-400' }} w-14 text-left">
                                    {{ $isHidden ? 'Hidden' : 'Visible' }}
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            <i data-lucide="shield-alert" class="w-8 h-8 mx-auto text-slate-300 mb-2"></i>
                            <p class="font-semibold text-sm text-slate-600">No whitelabel agencies found</p>
                            <p class="text-xs text-slate-400 mt-1">Try clearing your search query or onboard new agencies.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($agencies->hasPages())
        <div class="p-4 border-t border-slate-200 bg-slate-50/50">
            {{ $agencies->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Floating Toast Notification -->
<div id="toastNotification" class="fixed bottom-6 right-6 z-50 transform translate-y-24 opacity-0 transition-all duration-300 pointer-events-none max-w-md">
    <div class="bg-slate-900 text-white px-4 py-3 rounded-2xl shadow-2xl border border-slate-800 flex items-center space-x-3">
        <div id="toastIcon" class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
        </div>
        <div>
            <p id="toastTitle" class="text-xs font-bold text-white">Attribution Updated</p>
            <p id="toastMessage" class="text-[11px] text-slate-300 mt-0.5"></p>
        </div>
    </div>
</div>

<script>
function showToast(title, message, isSuccess = true) {
    const toast = document.getElementById('toastNotification');
    const titleEl = document.getElementById('toastTitle');
    const msgEl = document.getElementById('toastMessage');
    const iconEl = document.getElementById('toastIcon');

    titleEl.textContent = title;
    msgEl.textContent = message;

    if (isSuccess) {
        iconEl.className = 'w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0';
        iconEl.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4"></i>';
    } else {
        iconEl.className = 'w-8 h-8 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center flex-shrink-0';
        iconEl.innerHTML = '<i data-lucide="alert-triangle" class="w-4 h-4"></i>';
    }
    if (window.lucide) {
        window.lucide.createIcons();
    }

    toast.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
    toast.classList.add('translate-y-0', 'opacity-100');

    setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
    }, 3500);
}

function toggleAgencyAttribution(agencyId, isChecked) {
    const badge = document.getElementById('badge-' + agencyId);
    const badgeText = document.getElementById('badge-text-' + agencyId);
    const label = document.getElementById('label-' + agencyId);
    const toggle = document.getElementById('toggle-' + agencyId);

    // Optimistic UI update
    if (isChecked) {
        badge.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border bg-emerald-50 text-emerald-700 border-emerald-200';
        badgeText.textContent = 'Attribution Hidden (Paid)';
        label.className = 'text-[11px] font-bold text-emerald-600 w-14 text-left';
        label.textContent = 'Hidden';
    } else {
        badge.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border bg-slate-100 text-slate-600 border-slate-200';
        badgeText.textContent = 'Attribution Visible';
        label.className = 'text-[11px] font-bold text-slate-400 w-14 text-left';
        label.textContent = 'Visible';
    }

    fetch("{{ url('/copyright') }}/" + agencyId + "/toggle", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: JSON.stringify({ hide: isChecked })
    })
    .then(response => {
        if (!response.ok) throw new Error('Network error');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('Attribution Updated', data.message || 'Developer attribution setting saved successfully.');
        } else {
            throw new Error(data.message || 'Failed to update setting');
        }
    })
    .catch(error => {
        // Revert UI on failure
        toggle.checked = !isChecked;
        if (!isChecked) {
            badge.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border bg-emerald-50 text-emerald-700 border-emerald-200';
            badgeText.textContent = 'Attribution Hidden (Paid)';
            label.className = 'text-[11px] font-bold text-emerald-600 w-14 text-left';
            label.textContent = 'Hidden';
        } else {
            badge.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border bg-slate-100 text-slate-600 border-slate-200';
            badgeText.textContent = 'Attribution Visible';
            label.className = 'text-[11px] font-bold text-slate-400 w-14 text-left';
            label.textContent = 'Visible';
        }
        showToast('Update Failed', error.message || 'Could not update attribution setting.', false);
    });
}
</script>
@endsection
