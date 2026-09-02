@extends('layouts.admin')

@section('title', 'Ticket #' . $ticket->ticket_number)

@section('page_title', 'Support Ticket #' . $ticket->ticket_number)

@section('content')
<div class="space-y-6">

    <!-- Top Navigation & Breadcrumb -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-slate-500 hover:text-indigo-600 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to All Tickets</span>
        </a>

        <div class="flex items-center space-x-2">
            @php $statusBadge = $ticket->status_badge; @endphp
            <span class="px-3 py-1 rounded-full border text-xs font-extrabold inline-flex items-center space-x-1.5 {{ $statusBadge['class'] }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span>{{ $statusBadge['label'] }}</span>
            </span>

            @php $priorityBadge = $ticket->priority_badge; @endphp
            <span class="px-3 py-1 rounded-lg border text-xs font-bold uppercase tracking-wider {{ $priorityBadge['class'] }}">
                {{ $priorityBadge['label'] }} Priority
            </span>
        </div>
    </div>

    <!-- Main Workspace (2 Columns) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Ticket Content, Thread & Reply Form (2 Columns wide) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Ticket Main Info Header Card -->
            <div class="card-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-lg border border-indigo-200/80">{{ $ticket->ticket_number }}</span>
                            @if($ticket->product)
                                <span class="px-2.5 py-0.5 rounded-lg bg-violet-50 text-violet-700 border border-violet-200/80 font-bold text-xs inline-flex items-center space-x-1">
                                    <i data-lucide="box" class="w-3.5 h-3.5"></i>
                                    <span>Product: {{ $ticket->product->name }}</span>
                                </span>
                            @endif
                        </div>
                        <h1 class="text-xl font-extrabold text-slate-900 font-heading mt-2">{{ $ticket->subject }}</h1>
                    </div>

                    <div class="text-xs text-slate-400 font-medium sm:text-right">
                        <p>Raised: <span class="text-slate-700 font-semibold">{{ $ticket->created_at->format('M d, Y h:i A') }}</span></p>
                        <p class="mt-0.5">Last Updated: <span class="text-slate-700 font-semibold">{{ $ticket->updated_at->diffForHumans() }}</span></p>
                    </div>
                </div>

                <!-- Original Message Box -->
                <div class="bg-slate-50/80 rounded-2xl p-5 border border-slate-200/60 text-slate-800 text-xs leading-relaxed space-y-3">
                    <div class="flex items-center justify-between text-[11px] text-slate-400 font-semibold">
                        <span class="text-slate-900 font-bold flex items-center">
                            <i data-lucide="building" class="w-3.5 h-3.5 mr-1.5 text-indigo-600"></i>
                            {{ $ticket->agency->name ?? 'Agency' }} ({{ $ticket->user->name ?? 'Agency User' }})
                        </span>
                        <span>{{ $ticket->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="whitespace-pre-line text-slate-700 font-medium text-xs sm:text-sm">
                        {{ $ticket->message }}
                    </div>

                    <!-- Attachment Preview -->
                    @if(count($ticket->attachments_list) > 0)
                        <div class="pt-3 border-t border-slate-200/60">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Uploaded Attachments / Images ({{ count($ticket->attachments_list) }}):</p>
                            <div class="flex flex-wrap gap-3">
                                @foreach($ticket->attachments_list as $url)
                                    @if(Str::endsWith(strtolower($url), ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                        <a href="{{ $url }}" target="_blank" class="inline-block group">
                                            <img src="{{ $url }}" alt="Attachment" class="h-36 rounded-xl border border-slate-200 shadow-sm group-hover:opacity-90 transition object-cover">
                                            <span class="text-[10px] font-bold text-indigo-600 mt-1 block">
                                                <i data-lucide="external-link" class="w-3 h-3 inline"></i> View Full Image
                                            </span>
                                        </a>
                                    @else
                                        <a href="{{ $url }}" target="_blank" download 
                                           class="inline-flex items-center space-x-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-indigo-600 hover:bg-indigo-50 transition shadow-sm">
                                            <i data-lucide="paperclip" class="w-4 h-4"></i>
                                            <span>Download Attachment ({{ basename($url) }})</span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Conversation Thread -->
            <div class="space-y-4">
                <h3 class="text-base font-extrabold text-slate-900 font-heading flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="message-square" class="w-5 h-5 text-indigo-600"></i>
                        <span>Ticket Conversation Thread ({{ $ticket->replies->count() }})</span>
                    </div>
                    @if($ticket->replies->count() > 0)
                        <span class="text-xs text-slate-400 font-medium">Sorted chronologically</span>
                    @endif
                </h3>

                @foreach($ticket->replies as $reply)
                    @php 
                        $isStaff = $reply->user && ($reply->user->role === 'super_admin');
                        $isLatest = $loop->last;
                    @endphp
                    
                    <div class="card-white p-5 rounded-2xl border transition-all duration-200 
                        {{ $reply->is_internal_note 
                            ? 'border-l-4 border-l-amber-500 border-amber-200/80 bg-amber-50/70 shadow-sm' 
                            : ($isStaff 
                                ? 'border-l-4 border-l-indigo-600 border-indigo-200/80 bg-gradient-to-r from-indigo-50/60 via-purple-50/30 to-white shadow-sm' 
                                : 'border-l-4 border-l-blue-600 border-blue-200/80 bg-blue-50/40 shadow-sm') 
                        }} {{ $isLatest ? 'ring-2 ring-emerald-500/40 shadow-md' : '' }} space-y-3">
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-2xl {{ $reply->is_internal_note ? 'bg-amber-500 text-slate-950 font-black' : ($isStaff ? 'bg-gradient-to-tr from-indigo-600 via-purple-600 to-violet-600 text-white' : 'bg-gradient-to-tr from-blue-600 to-indigo-600 text-white') }} font-bold text-xs flex items-center justify-center shadow-md">
                                    @if($reply->is_internal_note)
                                        <i data-lucide="lock" class="w-4 h-4 text-slate-950"></i>
                                    @elseif($isStaff)
                                        <i data-lucide="shield-check" class="w-4 h-4 text-white"></i>
                                    @else
                                        <i data-lucide="building" class="w-4 h-4 text-white"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center space-x-2 flex-wrap gap-y-1">
                                        <span class="font-extrabold text-slate-900 text-xs font-heading">{{ $reply->user->name ?? 'User' }}</span>
                                        
                                        @if($reply->is_internal_note)
                                            <span class="px-2.5 py-0.5 rounded-full bg-amber-500 text-slate-950 text-[10px] font-black uppercase tracking-wider inline-flex items-center space-x-1 shadow-sm">
                                                <i data-lucide="eye-off" class="w-3 h-3"></i>
                                                <span>Internal Staff Note</span>
                                            </span>
                                        @elseif($isStaff)
                                            <span class="px-2.5 py-0.5 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-[10px] font-extrabold uppercase tracking-wider inline-flex items-center space-x-1 shadow-sm">
                                                <i data-lucide="shield-check" class="w-3 h-3 text-indigo-200"></i>
                                                <span>Super Admin Support</span>
                                            </span>
                                            @if($reply->user && $reply->user->designation)
                                                <span class="px-2 py-0.5 rounded-md bg-indigo-100/80 text-indigo-700 text-[10px] font-bold">
                                                    {{ $reply->user->designation }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 border border-blue-200 text-[10px] font-bold uppercase tracking-wider inline-flex items-center space-x-1">
                                                <i data-lucide="building" class="w-3 h-3 text-blue-600"></i>
                                                <span>Agency Reply</span>
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $reply->created_at->format('M d, Y • h:i A') }} ({{ $reply->created_at->diffForHumans() }})</p>
                                </div>
                            </div>

                            <!-- NEW MESSAGE Highlight Badge for latest reply -->
                            @if($isLatest)
                                <div class="flex items-center space-x-1">
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-500 text-white text-[10px] font-extrabold uppercase tracking-wider shadow-md shadow-emerald-500/30 animate-pulse flex items-center space-x-1">
                                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                                        <span>NEW MESSAGE</span>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="text-xs text-slate-700 leading-relaxed font-medium pl-13 whitespace-pre-line">
                            {{ $reply->message }}
                        </div>

                        <!-- Reply Attachments -->
                        @if(count($reply->attachments_list) > 0)
                            <div class="pl-13 pt-2 flex flex-wrap gap-2">
                                @foreach($reply->attachments_list as $url)
                                    @if(Str::endsWith(strtolower($url), ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                        <a href="{{ $url }}" target="_blank" class="inline-block group">
                                            <img src="{{ $url }}" alt="Attachment" class="h-28 rounded-xl border border-slate-200 shadow-sm group-hover:opacity-90 transition object-cover">
                                        </a>
                                    @else
                                        <a href="{{ $url }}" target="_blank" download 
                                           class="inline-flex items-center space-x-2 px-3 py-1.5 bg-slate-100 border border-slate-200 rounded-lg text-xs font-bold text-indigo-600 hover:bg-indigo-50 transition">
                                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                            <span>{{ basename($url) }}</span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Super Admin Reply Form -->
            <div class="card-white p-6 rounded-3xl border border-slate-200/80 shadow-md space-y-4">
                <h4 class="text-sm font-extrabold text-slate-900 font-heading flex items-center space-x-2">
                    <i data-lucide="corner-down-right" class="w-4 h-4 text-indigo-600"></i>
                    <span>Reply to Agency</span>
                </h4>

                <form action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <textarea name="message" rows="4" required placeholder="Type support response or solution for the whitelabel agency..." 
                                  class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs text-slate-900 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all"></textarea>
                    </div>

                    <!-- Quick Options Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <!-- Update Status during reply -->
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Set Ticket Status</label>
                            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="pending_reply" {{ $ticket->status == 'pending_reply' ? 'selected' : '' }}>Pending Agency Reply</option>
                                <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <!-- Attach File -->
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Attachments</label>
                            <label class="cursor-pointer w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 transition flex items-center justify-between">
                                <span id="adminReplyFileLabel" class="truncate">Choose images or documents</span>
                                <i data-lucide="paperclip" class="w-4 h-4 text-slate-400 shrink-0 ml-1"></i>
                                <input type="file" name="attachments[]" multiple class="hidden" onchange="document.getElementById('adminReplyFileLabel').innerText = this.files.length > 0 ? ('Selected ' + this.files.length + ' files') : 'Choose images or documents'">
                            </label>
                        </div>

                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                        <label class="flex items-center space-x-2 text-xs text-slate-600 font-semibold cursor-pointer">
                            <input type="checkbox" name="is_internal_note" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span>Post as Internal Staff Note (hidden from agency)</span>
                        </label>

                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-lg shadow-indigo-600/30 inline-flex items-center space-x-2 transition">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            <span>Submit Reply</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- Right Column: Sidebar Control Card (Staff Assignment, Status & Agency Context) -->
        <div class="space-y-6">
            
            <!-- Staff & Ticket Controls Card -->
            <div class="card-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-5">
                <h3 class="text-sm font-extrabold text-slate-900 font-heading uppercase tracking-wider flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="sliders" class="w-4 h-4 text-indigo-600"></i>
                        <span>Ticket Management</span>
                    </div>
                    <a href="{{ route('admin.staff.index') }}" class="text-[11px] text-indigo-600 hover:underline font-bold">Manage Staff</a>
                </h3>

                <!-- Active Assigned Staff Highlight -->
                @if($ticket->assignedStaff)
                    <div class="p-3 bg-indigo-50/60 border border-indigo-200/80 rounded-2xl flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shadow-md">
                            {{ substr($ticket->assignedStaff->name, 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">Assigned Support Agent</p>
                            <h4 class="font-extrabold text-slate-900 text-xs font-heading truncate">{{ $ticket->assignedStaff->name }}</h4>
                            <p class="text-[10px] text-slate-500 truncate">{{ $ticket->assignedStaff->designation ?: 'Super Admin Staff' }}</p>
                        </div>
                    </div>
                @else
                    <div class="p-3 bg-amber-50/60 border border-amber-200/80 rounded-2xl flex items-center space-x-2 text-amber-800 text-xs">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-amber-600 shrink-0"></i>
                        <span class="font-bold">Currently Unassigned</span>
                    </div>
                @endif

                <!-- 1. Assign Staff Member -->
                <form action="{{ route('admin.tickets.assign-staff', $ticket->id) }}" method="POST" class="space-y-2">
                    @csrf
                    @method('PATCH')

                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">Change Assigned Staff</label>
                    <div class="flex items-center space-x-2">
                        <select name="assigned_to" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                            <option value="">-- Unassigned --</option>
                            @foreach($staffMembers as $staff)
                                <option value="{{ $staff->id }}" {{ $ticket->assigned_to == $staff->id ? 'selected' : '' }}>
                                    {{ $staff->name }} ({{ $staff->designation ?: 'Staff' }} - {{ $staff->active_tickets_count ?? 0 }} active)
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-3 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-600/20 transition">
                            Assign
                        </button>
                    </div>
                </form>

                <!-- 2. Update Status & Priority -->
                <form action="{{ route('admin.tickets.update-status', $ticket->id) }}" method="POST" class="space-y-4 pt-3 border-t border-slate-100">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Ticket Status</label>
                        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                            <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="pending_reply" {{ $ticket->status == 'pending_reply' ? 'selected' : '' }}>Pending Agency Reply</option>
                            <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Ticket Priority</label>
                        <select name="priority" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:border-indigo-500">
                            <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ $ticket->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-extrabold shadow-md transition">
                        Update Status & Priority
                    </button>
                </form>
            </div>

            <!-- Whitelabel Agency Info Card -->
            <div class="card-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-sm font-extrabold text-slate-900 font-heading uppercase tracking-wider flex items-center space-x-2 border-b border-slate-100 pb-3">
                    <i data-lucide="building" class="w-4 h-4 text-indigo-600"></i>
                    <span>Agency Information</span>
                </h3>

                @if($ticket->agency)
                    <div class="space-y-3 text-xs">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-bold text-xs flex items-center justify-center shadow-md">
                                {{ substr($ticket->agency->name, 0, 2) }}
                            </div>
                            <div>
                                <h4 class="font-extrabold text-slate-900 font-heading text-sm">{{ $ticket->agency->name }}</h4>
                                <p class="text-[11px] text-slate-400">{{ $ticket->agency->type === 'master_agency' ? 'Master Agency' : 'White Label Agency' }}</p>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-slate-100 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Owner Name:</span>
                                <span class="font-bold text-slate-800">{{ $ticket->agency->owner_name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Owner Email:</span>
                                <span class="font-bold text-slate-800">{{ $ticket->agency->email ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Custom Domain:</span>
                                <span class="font-bold text-indigo-600">{{ $ticket->agency->clean_domain }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Status:</span>
                                <span class="font-bold text-emerald-600 uppercase text-[10px]">{{ $ticket->agency->status }}</span>
                            </div>
                        </div>

                        <div class="pt-3">
                            <a href="{{ route('admin.agencies.index') }}" class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center justify-center space-x-1">
                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                <span>View Agency Profile</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Product Context Card (Task 2 Requirement) -->
            @if($ticket->product)
                <div class="card-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                    <h3 class="text-sm font-extrabold text-slate-900 font-heading uppercase tracking-wider flex items-center space-x-2 border-b border-slate-100 pb-3">
                        <i data-lucide="box" class="w-4 h-4 text-indigo-600"></i>
                        <span>Purchased Product Context</span>
                    </h3>

                    <div class="space-y-2 text-xs">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                <i data-lucide="package" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">{{ $ticket->product->name }}</h4>
                                <p class="text-[10px] text-slate-400">Slug: {{ $ticket->product->slug }}</p>
                            </div>
                        </div>
                        @if($ticket->product->tagline)
                            <p class="text-[11px] text-slate-500 italic bg-slate-50 p-2.5 rounded-xl border border-slate-100">{{ $ticket->product->tagline }}</p>
                        @endif
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection
