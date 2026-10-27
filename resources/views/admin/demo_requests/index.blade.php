@extends('layouts.admin')

@section('title', 'Demo Requests & Leads')
@section('page_title', 'Demo Requests Management')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Top Header & Summary -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 font-heading tracking-tight flex items-center gap-2.5">
                <i data-lucide="calendar" class="w-7 h-7 text-[#ff3d00]"></i>
                <span>Live Demo Requests</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Leads captured from the "Book Free Live Demo" form on saasreselling.com
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-50 border border-orange-200/80 text-xs font-bold text-[#ff3d00]">
                <span class="w-2 h-2 rounded-full bg-[#ff3d00] animate-ping"></span>
                <span>Live Inquiries</span>
            </span>
        </div>
    </div>

    <!-- Metric Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Leads</p>
                <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading mt-1">{{ number_format($totalLeads) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="card-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Today Leads</p>
                <p class="text-2xl sm:text-3xl font-extrabold text-[#ff3d00] font-heading mt-1">{{ number_format($todayLeads) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-[#ff3d00] flex items-center justify-center text-xl">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="card-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">New (Uncontacted)</p>
                <p class="text-2xl sm:text-3xl font-extrabold text-emerald-600 font-heading mt-1">{{ number_format($newLeads) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i data-lucide="sparkles" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="card-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Contacted / Scheduled</p>
                <p class="text-2xl sm:text-3xl font-extrabold text-blue-600 font-heading mt-1">{{ number_format($contactedLeads) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i data-lucide="check-circle-2" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="card-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        
        <!-- Date Quick Filter Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-bold">
            <span class="text-slate-500 mr-2 flex items-center gap-1">
                <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                <span>Filter:</span>
            </span>
            <a href="{{ route('admin.demo-requests.index', array_merge(request()->except('date_filter', 'page'), ['date_filter' => 'all'])) }}" 
               class="px-3.5 py-1.5 rounded-xl transition-all {{ $dateFilter === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                All Time
            </a>
            <a href="{{ route('admin.demo-requests.index', array_merge(request()->except('date_filter', 'page'), ['date_filter' => 'today'])) }}" 
               class="px-3.5 py-1.5 rounded-xl transition-all {{ $dateFilter === 'today' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Today
            </a>
            <a href="{{ route('admin.demo-requests.index', array_merge(request()->except('date_filter', 'page'), ['date_filter' => 'yesterday'])) }}" 
               class="px-3.5 py-1.5 rounded-xl transition-all {{ $dateFilter === 'yesterday' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Yesterday
            </a>
            <a href="{{ route('admin.demo-requests.index', array_merge(request()->except('date_filter', 'page'), ['date_filter' => 'this_week'])) }}" 
               class="px-3.5 py-1.5 rounded-xl transition-all {{ $dateFilter === 'this_week' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                This Week
            </a>
            <a href="{{ route('admin.demo-requests.index', array_merge(request()->except('date_filter', 'page'), ['date_filter' => 'last_7_days'])) }}" 
               class="px-3.5 py-1.5 rounded-xl transition-all {{ $dateFilter === 'last_7_days' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Last 7 Days
            </a>
            <a href="{{ route('admin.demo-requests.index', array_merge(request()->except('date_filter', 'page'), ['date_filter' => 'this_month'])) }}" 
               class="px-3.5 py-1.5 rounded-xl transition-all {{ $dateFilter === 'this_month' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                This Month
            </a>
        </div>

        <!-- Custom Date Range & Search Form -->
        <form method="GET" action="{{ route('admin.demo-requests.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-2 border-t border-slate-100">
            <input type="hidden" name="date_filter" value="custom">

            <div class="sm:col-span-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, whatsapp, email..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="sm:col-span-2">
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Statuses</option>
                    <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                    <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
            </div>

            <div class="sm:col-span-2">
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full py-2 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-1.5">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Apply</span>
                </button>
                <a href="{{ route('admin.demo-requests.index') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs transition-colors" title="Reset Filters">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Leads Table -->
    <div class="card-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4">#</th>
                        <th class="py-3.5 px-4">Lead Details</th>
                        <th class="py-3.5 px-4">Profession</th>
                        <th class="py-3.5 px-4">Contact & WhatsApp</th>
                        <th class="py-3.5 px-4">Received At</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($requests as $req)
                    @php
                        $cleanPhone = preg_replace('/[^0-9]/', '', $req->whatsapp);
                        if(strlen($cleanPhone) === 10) { $cleanPhone = '91' . $cleanPhone; }
                        $waText = urlencode("Hi {$req->name}, thank you for requesting a Free Live Demo of the SaaSReselling All-in-One White-Label SaaS Platform. Are you available for a quick chat?");
                        $waUrl = "https://wa.me/{$cleanPhone}?text={$waText}";
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4 font-bold text-slate-400">#{{ $req->id }}</td>
                        
                        <td class="py-3.5 px-4">
                            <span class="block font-bold text-slate-900 text-sm">{{ $req->name }}</span>
                            <span class="block text-slate-400 text-[11px]">{{ $req->email }}</span>
                        </td>

                        <td class="py-3.5 px-4">
                            <span class="inline-block px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-semibold text-[11px]">
                                {{ $req->profession ?? 'Entrepreneur' }}
                            </span>
                        </td>

                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800">{{ $req->whatsapp }}</span>
                                <a href="{{ $waUrl }}" target="_blank" class="w-6 h-6 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white flex items-center justify-center text-[10px] shadow-sm transition-transform hover:scale-110" title="Chat on WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </td>

                        <td class="py-3.5 px-4 text-slate-500">
                            <span class="block font-semibold text-slate-700">{{ $req->created_at->format('M d, Y h:i A') }}</span>
                            <span class="block text-[10px] text-slate-400">{{ $req->created_at->diffForHumans() }}</span>
                        </td>

                        <td class="py-3.5 px-4">
                            <form action="{{ route('admin.demo-requests.update-status', $req->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="text-[11px] font-bold rounded-lg px-2 py-1 border transition-colors cursor-pointer 
                                    {{ $req->status === 'new' ? 'bg-orange-50 border-orange-200 text-[#ff3d00]' : '' }}
                                    {{ $req->status === 'contacted' ? 'bg-blue-50 border-blue-200 text-blue-600' : '' }}
                                    {{ $req->status === 'scheduled' ? 'bg-purple-50 border-purple-200 text-purple-600' : '' }}
                                    {{ $req->status === 'completed' ? 'bg-emerald-50 border-emerald-200 text-emerald-600' : '' }}
                                    {{ $req->status === 'cancelled' ? 'bg-rose-50 border-rose-200 text-rose-600' : '' }}
                                ">
                                    <option value="new" {{ $req->status === 'new' ? 'selected' : '' }}>● New</option>
                                    <option value="contacted" {{ $req->status === 'contacted' ? 'selected' : '' }}>● Contacted</option>
                                    <option value="scheduled" {{ $req->status === 'scheduled' ? 'selected' : '' }}>● Scheduled</option>
                                    <option value="completed" {{ $req->status === 'completed' ? 'selected' : '' }}>● Completed</option>
                                    <option value="cancelled" {{ $req->status === 'cancelled' ? 'selected' : '' }}>● Cancelled</option>
                                </select>
                            </form>
                        </td>

                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ $waUrl }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold text-xs flex items-center gap-1 border border-emerald-200 transition-colors">
                                    <i class="fab fa-whatsapp"></i>
                                    <span>WhatsApp</span>
                                </a>
                                <form action="{{ route('admin.demo-requests.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Delete this lead inquiry?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition-colors" title="Delete Lead">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <i data-lucide="inbox" class="w-8 h-8 text-slate-300"></i>
                                <span class="font-bold text-slate-500 text-sm">No live demo requests found</span>
                                <span class="text-xs text-slate-400">New leads submitted on saasreselling.com will show up here immediately.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($requests->hasPages())
        <div class="p-4 border-t border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="text-xs text-slate-500">
                Showing {{ $requests->firstItem() }} to {{ $requests->lastItem() }} of {{ $requests->total() }} demo requests
            </div>
            <div>
                {{ $requests->links() }}
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
