@extends('layouts.whitelabel')

@section('title', 'Support Tickets')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 font-heading tracking-tight">Help & Support Desk</h1>
            <p class="text-xs text-slate-500 mt-1">Raise support tickets directly to the Super Admin team, select affected products, and upload documentation or screenshots.</p>
        </div>

        <button onclick="document.getElementById('createTicketModal').classList.remove('hidden')" 
                class="px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs rounded-2xl shadow-lg shadow-blue-600/30 flex items-center justify-center space-x-2 transition-all shrink-0">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Raise Support Ticket</span>
        </button>
    </div>

    <!-- Overview Metrics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="card-white p-5 rounded-2xl border border-slate-100 flex items-center space-x-4 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i data-lucide="ticket" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Tickets</p>
                <h3 class="text-xl font-extrabold text-slate-900 font-heading">{{ $stats['total'] }}</h3>
            </div>
        </div>

        <div class="card-white p-5 rounded-2xl border border-slate-100 flex items-center space-x-4 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Open Tickets</p>
                <h3 class="text-xl font-extrabold text-slate-900 font-heading">{{ $stats['open'] }}</h3>
            </div>
        </div>

        <div class="card-white p-5 rounded-2xl border border-slate-100 flex items-center space-x-4 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <i data-lucide="loader" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">In Progress</p>
                <h3 class="text-xl font-extrabold text-slate-900 font-heading">{{ $stats['in_progress'] }}</h3>
            </div>
        </div>

        <div class="card-white p-5 rounded-2xl border border-slate-100 flex items-center space-x-4 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <i data-lucide="check-circle-2" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Resolved</p>
                <h3 class="text-xl font-extrabold text-slate-900 font-heading">{{ $stats['resolved'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="card-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('whitelabel.tickets.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            
            <!-- Search -->
            <div class="relative flex-1">
                <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-3.5 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search by ticket #, subject or keyword..." 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
            </div>

            <!-- Status Filter -->
            <div class="w-full md:w-44">
                <select name="status" onchange="this.form.submit()" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-700 focus:outline-none focus:border-blue-500 cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="pending_reply" {{ request('status') == 'pending_reply' ? 'selected' : '' }}>Pending Reply</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <!-- Priority Filter -->
            <div class="w-full md:w-40">
                <select name="priority" onchange="this.form.submit()" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-700 focus:outline-none focus:border-blue-500 cursor-pointer">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>

            <!-- Product Filter -->
            <div class="w-full md:w-48">
                <select name="product_id" onchange="this.form.submit()" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-700 focus:outline-none focus:border-blue-500 cursor-pointer">
                    <option value="">All Products</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>{{ $prod->name }}</option>
                    @endforeach
                </select>
            </div>

            @if(request()->anyFilled(['search', 'status', 'priority', 'product_id']))
                <a href="{{ route('whitelabel.tickets.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition flex items-center justify-center space-x-1">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                    <span>Reset</span>
                </a>
            @endif

        </form>
    </div>

    <!-- Tickets Table -->
    <div class="card-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        @if($tickets->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            <th class="py-3.5 px-5">Ticket ID & Subject</th>
                            <th class="py-3.5 px-4">Product Context</th>
                            <th class="py-3.5 px-4">Priority</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Assigned Agent</th>
                            <th class="py-3.5 px-4">Last Response</th>
                            <th class="py-3.5 px-5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @foreach($tickets as $ticket)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                
                                <!-- Ticket ID & Subject -->
                                <td class="py-4 px-5">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                                            <i data-lucide="life-buoy" class="w-4 h-4"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('whitelabel.tickets.show', $ticket->id) }}" class="font-extrabold text-slate-900 hover:text-blue-600 font-heading text-sm transition">
                                                {{ $ticket->subject }}
                                            </a>
                                            <div class="flex items-center space-x-2 text-[11px] text-slate-400 mt-0.5">
                                                <span class="font-mono font-semibold text-slate-500">{{ $ticket->ticket_number }}</span>
                                                <span>•</span>
                                                <span>{{ $ticket->created_at->format('M d, Y h:i A') }}</span>
                                                @if($ticket->attachment)
                                                    <span>•</span>
                                                    <span class="text-indigo-600 font-medium flex items-center">
                                                        <i data-lucide="paperclip" class="w-3 h-3 mr-0.5"></i> Attachment
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
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

                                <!-- Assigned Agent -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if($ticket->assignedStaff)
                                        <div class="flex items-center space-x-2">
                                            <div class="w-6 h-6 rounded-full bg-indigo-600 text-white text-[10px] font-bold flex items-center justify-center">
                                                {{ substr($ticket->assignedStaff->name, 0, 2) }}
                                            </div>
                                            <span class="font-semibold text-slate-700 text-xs">{{ $ticket->assignedStaff->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-amber-600 bg-amber-50 px-2 py-0.5 rounded text-[10px] font-bold">Unassigned</span>
                                    @endif
                                </td>

                                <!-- Last Response -->
                                <td class="py-4 px-4 whitespace-nowrap text-[11px] text-slate-500 font-medium">
                                    {{ $ticket->last_replied_at ? $ticket->last_replied_at->diffForHumans() : $ticket->created_at->diffForHumans() }}
                                </td>

                                <!-- Action -->
                                <td class="py-4 px-5 text-right whitespace-nowrap">
                                    <a href="{{ route('whitelabel.tickets.show', $ticket->id) }}" 
                                       class="px-3 py-1.5 bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-700 font-bold rounded-xl text-xs transition inline-flex items-center space-x-1">
                                        <span>View Thread</span>
                                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                    </a>
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
            <div class="p-12 text-center space-y-4">
                <div class="w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto shadow-inner">
                    <i data-lucide="life-buoy" class="w-8 h-8"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 font-heading">No Support Tickets Raised Yet</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Need assistance with your agency account or products? Click below to create your first support ticket.</p>
                </div>
                <button onclick="document.getElementById('createTicketModal').classList.remove('hidden')" 
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-600/30 inline-flex items-center space-x-2 transition">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Raise Support Ticket</span>
                </button>
            </div>
        @endif
    </div>

</div>

@push('modals')
<!-- Modal: Raise Support Ticket -->
<div id="createTicketModal" class="fixed inset-0 z-[100] hidden bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-2xl w-full shadow-2xl space-y-6 my-8">
        
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-blue-600/30">
                    <i data-lucide="headphones" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900 font-heading">Raise New Support Ticket</h3>
                    <p class="text-xs text-slate-500">Submit your issue directly to the Super Admin team.</p>
                </div>
            </div>
            <button onclick="document.getElementById('createTicketModal').classList.add('hidden')" class="p-2 rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Form -->
        <form action="{{ route('whitelabel.tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Subject -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Ticket Subject / Title <span class="text-rose-500">*</span></label>
                <input type="text" name="subject" required placeholder="e.g. Issue with Launchshop domain provisioning or SSL error" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs text-slate-900 focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
            </div>

            <!-- Grid: Select Product & Priority -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                <!-- Product Selection -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Select Purchased Product</label>
                    <select name="product_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-900 focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                        <option value="">General / Agency Account Issue</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->slug }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Priority Level <span class="text-rose-500">*</span></label>
                    <select name="priority" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold text-slate-900 focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                        <option value="low">Low - Minor question or feedback</option>
                        <option value="medium" selected>Medium - Normal support query</option>
                        <option value="high">High - Feature broken or client blocked</option>
                        <option value="urgent">Urgent - Critical outage / system down</option>
                    </select>
                </div>

            </div>

            <!-- Message / Detailed Description -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Detailed Message & Description <span class="text-rose-500">*</span></label>
                <textarea name="message" rows="5" required placeholder="Describe the issue, step to reproduce, expected behavior, or error logs..." 
                          class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs text-slate-900 focus:outline-none focus:border-blue-500 focus:bg-white transition-all"></textarea>
            </div>

            <!-- File / Image Upload -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Attach Images / Screenshots / Files (Optional)</label>
                <div class="border-2 border-dashed border-slate-200 hover:border-blue-500 rounded-2xl p-4 bg-slate-50/60 transition-colors text-center cursor-pointer relative">
                    <input type="file" name="attachments[]" id="ticketAttachment" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="updateFileName(this)">
                    <div class="space-y-1">
                        <i data-lucide="upload-cloud" class="w-7 h-7 mx-auto text-slate-400"></i>
                        <p class="text-xs font-bold text-slate-700" id="fileLabel">Click or drag images, PDFs, doc, or zip files (Multiple files allowed)</p>
                        <p class="text-[10px] text-slate-400">Supports JPG, PNG, GIF, WEBP, PDF, DOCX, ZIP (Select multiple files)</p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('createTicketModal').classList.add('hidden')" 
                        class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-extrabold shadow-lg shadow-blue-600/30 flex items-center space-x-2 transition">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Submit Ticket</span>
                </button>
            </div>

        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function updateFileName(input) {
        const label = document.getElementById('fileLabel');
        if (input.files && input.files.length > 0) {
            if (input.files.length === 1) {
                label.innerText = "Selected file: " + input.files[0].name;
            } else {
                label.innerText = "Selected " + input.files.length + " files for upload";
            }
            label.classList.add("text-blue-600");
        }
    }
</script>
@endpush
@endsection
