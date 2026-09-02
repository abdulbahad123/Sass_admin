@extends('layouts.admin')

@section('title', 'Support Tickets Management')

@section('page_title', 'Support Tickets & Helpdesk')

@section('content')
<div class="space-y-6">

    <!-- Page Header Description -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 font-heading tracking-tight">Whitelabel Support Desk</h1>
            <p class="text-xs text-slate-500 mt-1">Manage agency tickets, assign support staff members, filter by products & priority, and reply to agencies.</p>
        </div>
    </div>

    <!-- Overview Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="card-white p-5 rounded-2xl border border-slate-100 flex items-center space-x-4 shadow-sm">
            <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <i data-lucide="layers" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Tickets</p>
                <h3 class="text-lg font-extrabold text-slate-900 font-heading">{{ $stats['total'] }}</h3>
            </div>
        </div>

        <div class="card-white p-5 rounded-2xl border border-slate-100 flex items-center space-x-4 shadow-sm">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Open Tickets</p>
                <h3 class="text-lg font-extrabold text-slate-900 font-heading">{{ $stats['open'] }}</h3>
            </div>
        </div>

        <div class="card-white p-5 rounded-2xl border border-slate-100 flex items-center space-x-4 shadow-sm">
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i data-lucide="loader" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">In Progress</p>
                <h3 class="text-lg font-extrabold text-slate-900 font-heading">{{ $stats['in_progress'] }}</h3>
            </div>
        </div>

        <div class="card-white p-5 rounded-2xl border border-slate-100 flex items-center space-x-4 shadow-sm">
            <div class="w-11 h-11 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <i data-lucide="message-square" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pending Agency</p>
                <h3 class="text-lg font-extrabold text-slate-900 font-heading">{{ $stats['pending_reply'] }}</h3>
            </div>
        </div>

        <div class="card-white p-5 rounded-2xl border border-slate-100 flex items-center space-x-4 shadow-sm">
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <i data-lucide="check-circle-2" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Resolved</p>
                <h3 class="text-lg font-extrabold text-slate-900 font-heading">{{ $stats['resolved'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Filters Toolbar -->
    <div class="card-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.tickets.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            
            <!-- Search -->
            <div class="relative lg:col-span-2">
                <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-3.5 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search ticket #, agency name, subject..." 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
            </div>

            <!-- Agency Filter -->
            <div>
                <select name="agency_id" onchange="this.form.submit()" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 focus:outline-none focus:border-indigo-500 cursor-pointer">
                    <option value="">All Agencies</option>
                    @foreach($agencies as $agency)
                        <option value="{{ $agency->id }}" {{ request('agency_id') == $agency->id ? 'selected' : '' }}>{{ $agency->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Product Filter -->
            <div>
                <select name="product_id" onchange="this.form.submit()" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 focus:outline-none focus:border-indigo-500 cursor-pointer">
                    <option value="">All Products</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>{{ $prod->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <select name="status" onchange="this.form.submit()" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 focus:outline-none focus:border-indigo-500 cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="pending_reply" {{ request('status') == 'pending_reply' ? 'selected' : '' }}>Pending Reply</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <!-- Assigned Staff Filter -->
            <div>
                <select name="assigned_to" onchange="this.form.submit()" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 focus:outline-none focus:border-indigo-500 cursor-pointer">
                    <option value="">All Staff</option>
                    <option value="unassigned" {{ request('assigned_to') == 'unassigned' ? 'selected' : '' }}>-- Unassigned --</option>
                    @foreach($staffMembers as $staff)
                        <option value="{{ $staff->id }}" {{ request('assigned_to') == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                    @endforeach
                </select>
            </div>

        </form>
    </div>

    <!-- Tickets Master Table -->
    <div class="card-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        @if($tickets->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            <th class="py-3.5 px-5">Ticket & Subject</th>
                            <th class="py-3.5 px-4">Agency</th>
                            <th class="py-3.5 px-4">Product Context</th>
                            <th class="py-3.5 px-4">Priority</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Assigned Staff</th>
                            <th class="py-3.5 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @foreach($tickets as $ticket)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                
                                <!-- Ticket & Subject -->
                                <td class="py-4 px-5">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 mt-0.5">
                                            <i data-lucide="life-buoy" class="w-4 h-4"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="font-extrabold text-slate-900 hover:text-indigo-600 font-heading text-sm transition">
                                                {{ $ticket->subject }}
                                            </a>
                                            <div class="flex items-center space-x-2 text-[11px] text-slate-400 mt-0.5">
                                                <span class="font-mono font-semibold text-slate-500">{{ $ticket->ticket_number }}</span>
                                                <span>•</span>
                                                <span>{{ $ticket->created_at->format('M d, Y h:i A') }}</span>
                                                @if($ticket->attachment)
                                                    <span>•</span>
                                                    <span class="text-indigo-600 font-medium flex items-center">
                                                        <i data-lucide="paperclip" class="w-3 h-3 mr-0.5"></i> File Attached
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Agency -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if($ticket->agency)
                                        <div class="flex items-center space-x-2">
                                            <div class="w-6 h-6 rounded-full bg-slate-900 text-white font-bold text-[10px] flex items-center justify-center">
                                                {{ substr($ticket->agency->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 text-xs">{{ $ticket->agency->name }}</p>
                                                <p class="text-[10px] text-slate-400">{{ $ticket->agency->owner_name ?? ($ticket->user->name ?? 'Owner') }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-slate-400">N/A</span>
                                    @endif
                                </td>

                                <!-- Product Context -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if($ticket->product)
                                        <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200/80 font-bold text-[11px] inline-flex items-center space-x-1">
                                            <i data-lucide="box" class="w-3 h-3"></i>
                                            <span>{{ $ticket->product->name }}</span>
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-[11px] italic">General Agency</span>
                                    @endif
                                </td>

                                <!-- Priority -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @php $priorityBadge = $ticket->priority_badge; @endphp
                                    <span class="px-2.5 py-1 rounded-lg border text-[11px] font-bold uppercase tracking-wider {{ $priorityBadge['class'] }}">
                                        {{ $priorityBadge['label'] }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @php $statusBadge = $ticket->status_badge; @endphp
                                    <span class="px-3 py-1 rounded-full border text-[11px] font-extrabold inline-flex items-center space-x-1.5 {{ $statusBadge['class'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        <span>{{ $statusBadge['label'] }}</span>
                                    </span>
                                </td>

                                <!-- Assigned Staff (Inline Quick Assignment) -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <form action="{{ route('admin.tickets.assign-staff', $ticket->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <select name="assigned_to" onchange="this.form.submit()" 
                                                class="bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs font-semibold text-slate-800 focus:outline-none focus:border-indigo-500 cursor-pointer">
                                            <option value="">-- Unassigned --</option>
                                            @foreach($staffMembers as $staff)
                                                <option value="{{ $staff->id }}" {{ $ticket->assigned_to == $staff->id ? 'selected' : '' }}>
                                                    {{ $staff->name }} ({{ $staff->designation ?: 'Staff' }} - {{ $staff->active_tickets_count ?? 0 }} active)
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-5 text-right whitespace-nowrap space-x-1">
                                    <a href="{{ route('admin.tickets.show', $ticket->id) }}" 
                                       class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-md shadow-indigo-600/20 inline-flex items-center space-x-1 transition">
                                        <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                                        <span>Manage & Reply</span>
                                    </a>

                                    <form action="{{ route('admin.tickets.destroy', $ticket->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete Ticket">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100">
                {{ $tickets->withQueryString()->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="p-12 text-center space-y-3">
                <div class="w-14 h-14 rounded-3xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto">
                    <i data-lucide="life-buoy" class="w-7 h-7"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 font-heading">No Support Tickets Found</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">There are no tickets matching your filter criteria.</p>
            </div>
        @endif
    </div>

</div>
@endsection
