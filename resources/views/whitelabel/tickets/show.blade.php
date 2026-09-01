@extends('layouts.whitelabel')

@section('title', 'Ticket #' . $ticket->ticket_number)

@section('content')
<div class="space-y-6">

    <!-- Top Navigation & Breadcrumb -->
    <div class="flex items-center justify-between">
        <a href="{{ route('whitelabel.tickets.index') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-slate-500 hover:text-blue-600 transition">
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

    <!-- Ticket Summary Card -->
    <div class="card-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="font-mono text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-lg border border-blue-200/80">{{ $ticket->ticket_number }}</span>
                    @if($ticket->product)
                        <span class="px-2.5 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200/80 font-bold text-xs inline-flex items-center space-x-1">
                            <i data-lucide="box" class="w-3.5 h-3.5"></i>
                            <span>Product: {{ $ticket->product->name }}</span>
                        </span>
                    @endif
                </div>
                <h1 class="text-xl font-extrabold text-slate-900 font-heading mt-2">{{ $ticket->subject }}</h1>
            </div>

            <div class="text-xs text-slate-400 font-medium md:text-right">
                <p>Created: <span class="text-slate-700 font-semibold">{{ $ticket->created_at->format('M d, Y h:i A') }}</span></p>
                <p class="mt-0.5">Assigned Agent: 
                    @if($ticket->assignedStaff)
                        <span class="text-indigo-600 font-bold">{{ $ticket->assignedStaff->name }}</span>
                    @else
                        <span class="text-amber-600 font-semibold">Super Admin Support</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Initial Message Content -->
        <div class="bg-slate-50/80 rounded-2xl p-5 border border-slate-200/60 text-slate-800 text-xs leading-relaxed space-y-3">
            <div class="flex items-center justify-between text-[11px] text-slate-400 font-semibold">
                <span class="text-slate-900 font-bold flex items-center">
                    <i data-lucide="user" class="w-3.5 h-3.5 mr-1.5 text-blue-600"></i>
                    {{ $ticket->user->name ?? 'Agency User' }} (Original Request)
                </span>
                <span>{{ $ticket->created_at->diffForHumans() }}</span>
            </div>
            <div class="whitespace-pre-line text-slate-700 font-medium text-xs sm:text-sm">
                {{ $ticket->message }}
            </div>

            <!-- Main Ticket Attachments -->
            @if(count($ticket->attachments_list) > 0)
                <div class="pt-3 border-t border-slate-200/60">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Attached Files / Screenshots ({{ count($ticket->attachments_list) }}):</p>
                    <div class="flex flex-wrap gap-3">
                        @foreach($ticket->attachments_list as $url)
                            @if(Str::endsWith(strtolower($url), ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                <a href="{{ $url }}" target="_blank" class="inline-block group">
                                    <img src="{{ $url }}" alt="Attachment" class="h-36 rounded-xl border border-slate-200 shadow-sm group-hover:opacity-90 transition object-cover">
                                    <span class="text-[10px] font-bold text-blue-600 mt-1 block">
                                        <i data-lucide="external-link" class="w-3 h-3 inline"></i> View Full Image
                                    </span>
                                </a>
                            @else
                                <a href="{{ $url }}" target="_blank" download 
                                   class="inline-flex items-center space-x-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-blue-600 hover:bg-blue-50 transition shadow-sm">
                                    <i data-lucide="paperclip" class="w-4 h-4"></i>
                                    <span>Download ({{ basename($url) }})</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Replies Conversation Thread -->
    <div class="space-y-4">
        <h3 class="text-base font-extrabold text-slate-900 font-heading flex items-center space-x-2">
            <i data-lucide="message-square" class="w-5 h-5 text-blue-600"></i>
            <span>Conversation Thread ({{ $ticket->replies->count() }} {{ Str::plural('Reply', $ticket->replies->count()) }})</span>
        </h3>

        @foreach($ticket->replies as $reply)
            @php $isSuperAdmin = $reply->user && ($reply->user->role === 'super_admin'); @endphp
            
            <div class="card-white p-5 rounded-2xl border {{ $isSuperAdmin ? 'border-indigo-100 bg-gradient-to-r from-indigo-50/30 to-white' : 'border-slate-100' }} shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-full {{ $isSuperAdmin ? 'bg-gradient-to-tr from-indigo-600 to-violet-600 text-white' : 'bg-gradient-to-tr from-blue-600 to-cyan-600 text-white' }} font-bold text-xs flex items-center justify-center shadow-md">
                            {{ substr($reply->user->name ?? ($isSuperAdmin ? 'SA' : 'AG'), 0, 2) }}
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="font-extrabold text-slate-900 text-xs font-heading">{{ $reply->user->name ?? 'User' }}</span>
                                @if($isSuperAdmin)
                                    <span class="px-2 py-0.5 rounded-full bg-indigo-600 text-white text-[9px] font-extrabold uppercase tracking-wider">Super Admin Support</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[9px] font-bold">Agency</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-slate-400 font-medium">{{ $reply->created_at->format('M d, Y • h:i A') }} ({{ $reply->created_at->diffForHumans() }})</p>
                        </div>
                    </div>
                </div>

                <div class="text-xs text-slate-700 leading-relaxed font-medium pl-12 whitespace-pre-line">
                    {{ $reply->message }}
                </div>

                <!-- Reply Attachments -->
                @if(count($reply->attachments_list) > 0)
                    <div class="pl-12 pt-2 flex flex-wrap gap-2">
                        @foreach($reply->attachments_list as $url)
                            @if(Str::endsWith(strtolower($url), ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                                <a href="{{ $url }}" target="_blank" class="inline-block group">
                                    <img src="{{ $url }}" alt="Attachment" class="h-28 rounded-xl border border-slate-200 shadow-sm group-hover:opacity-90 transition object-cover">
                                </a>
                            @else
                                <a href="{{ $url }}" target="_blank" download 
                                   class="inline-flex items-center space-x-2 px-3 py-1.5 bg-slate-100 border border-slate-200 rounded-lg text-xs font-bold text-blue-600 hover:bg-blue-50 transition">
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

    <!-- Reply Form Box -->
    <div class="card-white p-6 rounded-3xl border border-slate-200/80 shadow-md space-y-4">
        <h4 class="text-sm font-extrabold text-slate-900 font-heading flex items-center space-x-2">
            <i data-lucide="corner-down-right" class="w-4 h-4 text-blue-600"></i>
            <span>Post Follow-up Reply</span>
        </h4>

        <form action="{{ route('whitelabel.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <textarea name="message" rows="4" required placeholder="Type your reply or response here..." 
                          class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs text-slate-900 focus:outline-none focus:border-blue-500 focus:bg-white transition-all"></textarea>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                <div class="flex items-center space-x-2">
                    <label class="cursor-pointer px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition inline-flex items-center space-x-1.5">
                        <i data-lucide="paperclip" class="w-4 h-4 text-slate-500"></i>
                        <span id="replyFileLabel">Attach Images / Files</span>
                        <input type="file" name="attachments[]" multiple class="hidden" onchange="document.getElementById('replyFileLabel').innerText = this.files.length > 0 ? ('Selected ' + this.files.length + ' files') : 'Attach Images / Files'">
                    </label>
                </div>

                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-blue-600/30 inline-flex items-center justify-center space-x-2 transition">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Send Reply</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
