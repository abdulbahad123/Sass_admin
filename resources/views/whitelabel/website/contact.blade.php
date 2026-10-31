@extends('layouts.whitelabel')

@section('title', 'Website - Contact & Leads')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">Contact &amp; Lead Management</h1>
            <p class="text-xs text-slate-500">Manage submitted visitor inquiries, configure SMTP copy-to-email forwarding, and customize contact details.</p>
        </div>
        <a href="{{ url('/contact') }}" target="_blank" class="inline-flex items-center space-x-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
            <span>View Public Contact Page</span>
        </a>
    </div>

    {{-- Tabs Navigation --}}
    <div class="border-b border-slate-200">
        <nav class="flex space-x-6 text-xs font-bold" aria-label="Tabs">
            <a href="{{ route('whitelabel.website.contact', ['tab' => 'submitted_data']) }}" 
               class="py-3 px-1 border-b-2 flex items-center space-x-2 transition-all {{ $activeTab === 'submitted_data' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                <i data-lucide="inbox" class="w-4 h-4"></i>
                <span>Submitted Inquiries</span>
                @if($unreadCount > 0)
                    <span class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-700">
                        {{ $unreadCount }} new
                    </span>
                @endif
            </a>

            <a href="{{ route('whitelabel.website.contact', ['tab' => 'contact_info']) }}" 
               class="py-3 px-1 border-b-2 flex items-center space-x-2 transition-all {{ $activeTab === 'contact_info' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                <i data-lucide="phone-call" class="w-4 h-4"></i>
                <span>Contact Details &amp; Socials</span>
            </a>

            <a href="{{ route('whitelabel.website.contact', ['tab' => 'smtp']) }}" 
               class="py-3 px-1 border-b-2 flex items-center space-x-2 transition-all {{ $activeTab === 'smtp' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                <i data-lucide="mail" class="w-4 h-4"></i>
                <span>SMTP &amp; Email Copy</span>
            </a>

            <a href="{{ route('whitelabel.website.contact', ['tab' => 'recaptcha']) }}" 
               class="py-3 px-1 border-b-2 flex items-center space-x-2 transition-all {{ $activeTab === 'recaptcha' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                <span>Google reCAPTCHA</span>
            </a>
        </nav>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════════════ --}}
    {{-- TAB 1: SUBMITTED INQUIRIES LEADS TABLE                                       --}}
    {{-- ════════════════════════════════════════════════════════════════════════════ --}}
    @if($activeTab === 'submitted_data')
    <div class="space-y-6">
        
        {{-- Metrics Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="card-white rounded-2xl p-4 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <i data-lucide="mail-open" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Inquiries</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 font-heading">{{ $totalCount ?? 0 }}</h3>
                </div>
            </div>

            <div class="card-white rounded-2xl p-4 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Unread Messages</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 font-heading">{{ $unreadCount ?? 0 }}</h3>
                </div>
            </div>

            <div class="card-white rounded-2xl p-4 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <i data-lucide="sparkles" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Received Today</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 font-heading">{{ $todayCount ?? 0 }}</h3>
                </div>
            </div>
        </div>

        {{-- Filter & Search Bar --}}
        <div class="card-white rounded-2xl p-4">
            <form method="GET" action="{{ route('whitelabel.website.contact') }}" class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <input type="hidden" name="tab" value="submitted_data">
                
                {{-- Quick Date Pills --}}
                <div class="flex flex-wrap items-center gap-1.5 text-xs font-bold">
                    <a href="{{ route('whitelabel.website.contact', ['tab' => 'submitted_data', 'filter' => 'all']) }}" 
                       class="px-3 py-1.5 rounded-lg transition {{ request('filter', 'all') === 'all' && !request('start_date') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        All Time
                    </a>
                    <a href="{{ route('whitelabel.website.contact', ['tab' => 'submitted_data', 'filter' => 'today']) }}" 
                       class="px-3 py-1.5 rounded-lg transition {{ request('filter') === 'today' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Today
                    </a>
                    <a href="{{ route('whitelabel.website.contact', ['tab' => 'submitted_data', 'filter' => 'yesterday']) }}" 
                       class="px-3 py-1.5 rounded-lg transition {{ request('filter') === 'yesterday' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Yesterday
                    </a>
                </div>

                {{-- Custom Date Range & Search --}}
                <div class="flex flex-wrap items-center gap-2">
                    <div class="flex items-center space-x-1.5 bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">From:</span>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="bg-transparent text-xs text-slate-700 outline-none">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">To:</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="bg-transparent text-xs text-slate-700 outline-none">
                        <input type="hidden" name="filter" value="custom">
                    </div>

                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search sender, email, subject..." 
                               class="bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-3 py-1.5 text-xs text-slate-700 w-48 sm:w-64 focus:outline-none focus:border-blue-500">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5"></i>
                    </div>

                    <button type="submit" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['filter', 'start_date', 'end_date', 'search', 'status']))
                    <a href="{{ route('whitelabel.website.contact', ['tab' => 'submitted_data']) }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-500 text-xs font-bold rounded-xl" title="Clear Filters">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Leads Table --}}
        <div class="card-white rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-4">Sender</th>
                            <th class="py-3.5 px-4">Subject &amp; Message</th>
                            <th class="py-3.5 px-4">Date &amp; Time</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($submissions as $sub)
                        <tr class="hover:bg-slate-50/60 transition {{ $sub->status === 'unread' ? 'bg-blue-50/20 font-semibold' : '' }}">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">
                                        {{ substr($sub->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $sub->name }}</div>
                                        <div class="text-[11px] text-slate-400 flex items-center space-x-1">
                                            <span>{{ $sub->email }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 max-w-xs sm:max-w-md">
                                <div class="font-bold text-slate-800 truncate">{{ $sub->subject ?: 'General Inquiry' }}</div>
                                <div class="text-slate-500 text-[11px] truncate">{{ $sub->message }}</div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="text-slate-700 font-medium">{{ $sub->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-slate-400">{{ $sub->created_at->format('h:i A') }} ({{ $sub->created_at->diffForHumans() }})</div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($sub->status === 'unread')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>
                                        Unread
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                                        Read
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end space-x-1.5">
                                    {{-- View Details Modal Trigger --}}
                                    <button type="button" onclick="openLeadModal('{{ addslashes($sub->name) }}', '{{ addslashes($sub->email) }}', '{{ addslashes($sub->subject) }}', `{{ addslashes($sub->message) }}`, '{{ $sub->created_at->format('M d, Y h:i A') }}', '{{ $sub->ip_address }}', '{{ $sub->id }}')" 
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition" title="View Full Message">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>

                                    {{-- Status Toggle --}}
                                    <form action="{{ route('whitelabel.website.contact.submissions.status', $sub->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $sub->status === 'unread' ? 'read' : 'unread' }}">
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition" title="Mark as {{ $sub->status === 'unread' ? 'Read' : 'Unread' }}">
                                            <i data-lucide="{{ $sub->status === 'unread' ? 'check' : 'rotate-ccw' }}" class="w-4 h-4"></i>
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <form action="{{ route('whitelabel.website.contact.submissions.delete', $sub->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Delete Inquiry">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                <p class="text-sm font-bold text-slate-600">No contact inquiries found</p>
                                <p class="text-[11px] text-slate-400 mt-1">Inquiries submitted on your public website contact form will appear here in real time.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            @if($submissions->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/50">
                {{ $submissions->links() }}
            </div>
            @endif
        </div>

    </div>

    {{-- Detail Modal --}}
    <div id="leadModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4 border border-slate-100 animate-in fade-in zoom-in-95">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                    <h3 class="text-base font-bold text-slate-900 font-heading">Visitor Inquiry Details</h3>
                </div>
                <button type="button" onclick="closeLeadModal()" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
            </div>
            
            <div class="space-y-3 text-xs">
                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100 space-y-1.5">
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-bold uppercase text-[10px]">From</span>
                        <span id="modalDate" class="text-slate-400 text-[10px]"></span>
                    </div>
                    <div id="modalSender" class="text-sm font-extrabold text-slate-900"></div>
                    <div class="flex items-center justify-between">
                        <span id="modalEmail" class="text-blue-600 font-semibold"></span>
                        <a id="modalMailto" href="#" class="px-2.5 py-1 bg-blue-50 text-blue-700 font-bold rounded-lg hover:bg-blue-100 transition text-[11px] inline-flex items-center space-x-1">
                            <i data-lucide="reply" class="w-3 h-3"></i>
                            <span>Reply via Email</span>
                        </a>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Subject</label>
                    <div id="modalSubject" class="text-xs font-bold text-slate-800 bg-slate-50 p-2.5 rounded-xl border border-slate-100"></div>
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Message Content</label>
                    <div id="modalMessage" class="text-xs text-slate-700 leading-relaxed bg-slate-50 p-3.5 rounded-xl border border-slate-100 whitespace-pre-line max-h-56 overflow-y-auto"></div>
                </div>

                <div class="text-[10px] text-slate-400 flex items-center justify-between pt-1">
                    <span>IP Address: <strong id="modalIp" class="font-mono"></strong></span>
                    <span>Status: Stored on Dashboard</span>
                </div>
            </div>

            <div class="flex justify-end pt-2 border-t border-slate-100">
                <button type="button" onclick="closeLeadModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════════════ --}}
    {{-- TAB 2: CONTACT DETAILS & SOCIALS FORM                                        --}}
    {{-- ════════════════════════════════════════════════════════════════════════════ --}}
    @if($activeTab === 'contact_info')
    <form action="{{ route('whitelabel.website.contact.update') }}" method="POST" class="space-y-6">
        @csrf
        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="phone" class="w-4 h-4 text-emerald-600"></i>
                <span>Direct Contact Channels</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $agency->contact_email ?? $agency->email) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Contact Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $agency->contact_phone ?? $agency->phone) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:outline-none focus:border-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Office Address</label>
                <textarea name="contact_address" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:outline-none focus:border-blue-500">{{ old('contact_address', $agency->contact_address) }}</textarea>
            </div>
        </div>

        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="share-2" class="w-4 h-4 text-blue-600"></i>
                <span>Social Media Links</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Facebook Page URL</label>
                    <input type="url" name="facebook" value="{{ $socialLinks['facebook'] ?? '' }}" placeholder="https://facebook.com/youragency" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Instagram URL</label>
                    <input type="url" name="instagram" value="{{ $socialLinks['instagram'] ?? '' }}" placeholder="https://instagram.com/youragency" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">YouTube Channel URL</label>
                    <input type="url" name="youtube" value="{{ $socialLinks['youtube'] ?? '' }}" placeholder="https://youtube.com/@youragency" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">LinkedIn Company Page</label>
                    <input type="url" name="linkedin" value="{{ $socialLinks['linkedin'] ?? '' }}" placeholder="https://linkedin.com/company/youragency" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">X (Twitter) Profile</label>
                    <input type="url" name="twitter" value="{{ $socialLinks['twitter'] ?? '' }}" placeholder="https://x.com/youragency" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs">
                </div>
            </div>
        </div>

        <div class="card-white rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i data-lucide="copyright" class="w-4 h-4 text-purple-600"></i>
                <span>Footer Copyright &amp; Summary</span>
            </h3>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Footer Tagline / Short Summary</label>
                <textarea name="footer_content" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:outline-none focus:border-blue-500">{{ old('footer_content', $agency->footer_content ?? 'Powering the growth of local businesses with smart digital solutions.') }}</textarea>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 transition-all">
                Save Contact &amp; Social Info
            </button>
        </div>
    </form>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════════════ --}}
    {{-- TAB 3: SMTP SETTINGS & COPY TO EMAIL                                         --}}
    {{-- ════════════════════════════════════════════════════════════════════════════ --}}
    @if($activeTab === 'smtp')
    <div class="space-y-6">
        {{-- Explanation Banner --}}
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-5 flex items-start space-x-4">
            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold flex-shrink-0">
                <i data-lucide="send" class="w-5 h-5"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-sm font-bold text-slate-900">Why configure SMTP?</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    When visitors submit an inquiry on your public <strong>Contact Us</strong> page, our system will automatically save it on your dashboard AND immediately send an email copy directly to your inbox so you never miss a prospective client lead!
                </p>
            </div>
        </div>

        <form action="{{ route('whitelabel.website.contact.smtp') }}" method="POST" class="space-y-6">
            @csrf

            <div class="card-white rounded-2xl p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900 font-heading flex items-center space-x-2">
                        <i data-lucide="bell-ring" class="w-4 h-4 text-indigo-600"></i>
                        <span>Lead Forwarding &amp; Notification Email</span>
                    </h3>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="smtp_enabled" value="1" class="sr-only peer" {{ !empty($smtpSettings['smtp_enabled']) ? 'checked' : '' }}>
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-2 text-xs font-bold text-slate-700">Enable Email Forwarding</span>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Forward Copy to Email Address <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="smtp_to_email" value="{{ $smtpSettings['smtp_to_email'] ?? ($agency->contact_email ?? $agency->email) }}" 
                           placeholder="yourname@gmail.com or support@yourdomain.com" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold focus:outline-none focus:border-blue-500">
                    <p class="text-[11px] text-slate-400 mt-1">Every submitted inquiry will be copied to this email address automatically.</p>
                </div>
            </div>

            <div class="card-white rounded-2xl p-6 space-y-4">
                <h3 class="text-sm font-bold text-slate-900 font-heading border-b border-slate-100 pb-3 flex items-center space-x-2">
                    <i data-lucide="server" class="w-4 h-4 text-purple-600"></i>
                    <span>Custom SMTP Server Configuration (Optional)</span>
                    <span class="text-[11px] font-normal text-slate-400 ml-2">Leave blank to use default server mail delivery</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">SMTP Host</label>
                        <input type="text" name="smtp_host" value="{{ $smtpSettings['smtp_host'] ?? '' }}" placeholder="smtp.gmail.com or mail.yourdomain.com" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">SMTP Port</label>
                        <input type="number" name="smtp_port" value="{{ $smtpSettings['smtp_port'] ?? 587 }}" placeholder="587" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500 font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Encryption Protocol</label>
                        <select name="smtp_encryption" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500">
                            <option value="tls" {{ ($smtpSettings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS (Port 587)</option>
                            <option value="ssl" {{ ($smtpSettings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL (Port 465)</option>
                            <option value="none" {{ ($smtpSettings['smtp_encryption'] ?? '') === 'none' ? 'selected' : '' }}>None (Port 25)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">SMTP Username</label>
                        <input type="text" name="smtp_username" value="{{ $smtpSettings['smtp_username'] ?? '' }}" placeholder="username@gmail.com" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">SMTP Password / App Password</label>
                        <input type="password" name="smtp_password" value="{{ $smtpSettings['smtp_password'] ?? '' }}" placeholder="••••••••••••" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500 font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">From Email Address</label>
                        <input type="email" name="smtp_from_address" value="{{ $smtpSettings['smtp_from_address'] ?? '' }}" placeholder="no-reply@youragency.com" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">From Name</label>
                        <input type="text" name="smtp_from_name" value="{{ $smtpSettings['smtp_from_name'] ?? ($agency->name ?? 'White Label Agency') }}" placeholder="{{ $agency->name ?? 'White Label Agency' }}" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 transition-all">
                    Save SMTP Settings
                </button>
            </div>
        </form>

        {{-- Test SMTP Connection Form --}}
        <div class="card-white rounded-2xl p-6 space-y-4 border-t-2 border-indigo-500">
            <h3 class="text-sm font-bold text-slate-900 font-heading flex items-center space-x-2">
                <i data-lucide="zap" class="w-4 h-4 text-amber-500"></i>
                <span>Test SMTP Email Delivery</span>
            </h3>
            <p class="text-xs text-slate-500">Send an instant test email to confirm that your SMTP configuration is functioning properly.</p>

            <form action="{{ route('whitelabel.website.contact.smtp.test') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-3">
                @csrf
                <input type="email" name="test_email" value="{{ $smtpSettings['smtp_to_email'] ?? ($agency->contact_email ?? $agency->email) }}" 
                       placeholder="Enter test recipient email" required
                       class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-indigo-500">
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition whitespace-nowrap">
                    Send Test Email 🚀
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════════════ --}}
    {{-- TAB 4: GOOGLE RECAPTCHA                                                      --}}
    {{-- ════════════════════════════════════════════════════════════════════════════ --}}
    @if($activeTab === 'recaptcha')
    <form action="{{ route('whitelabel.website.contact.recaptcha') }}" method="POST" class="space-y-6">
        @csrf
        <div class="card-white rounded-2xl p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 font-heading flex items-center space-x-2">
                        <i data-lucide="shield" class="w-4 h-4 text-blue-600"></i>
                        <span>Google reCAPTCHA Protection</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Protect your public contact form from automated spam bots and fraudulent submissions.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="recaptcha_enabled" value="1" class="sr-only peer" {{ !empty($recaptchaSettings['recaptcha_enabled']) ? 'checked' : '' }}>
                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ml-2 text-xs font-bold text-slate-700">Enable reCAPTCHA</span>
                </label>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">reCAPTCHA Version</label>
                    <select name="recaptcha_version" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:border-blue-500">
                        <option value="v2" {{ ($recaptchaSettings['recaptcha_version'] ?? 'v2') === 'v2' ? 'selected' : '' }}>reCAPTCHA v2 ("I'm not a robot" Checkbox)</option>
                        <option value="v3" {{ ($recaptchaSettings['recaptcha_version'] ?? '') === 'v3' ? 'selected' : '' }}>reCAPTCHA v3 (Invisible Score-based)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Google Site Key</label>
                    <input type="text" name="recaptcha_site_key" value="{{ $recaptchaSettings['recaptcha_site_key'] ?? '' }}" placeholder="6LeIxacTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Google Secret Key</label>
                    <input type="password" name="recaptcha_secret_key" value="{{ $recaptchaSettings['recaptcha_secret_key'] ?? '' }}" placeholder="6LeIxacTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="bg-slate-50 rounded-xl p-3 text-[11px] text-slate-500 leading-relaxed border border-slate-200">
                💡 You can generate your API keys at <a href="https://www.google.com/recaptcha/admin" target="_blank" class="text-blue-600 font-bold underline">Google reCAPTCHA Console</a>. Make sure to whitelist your custom domain: <code>{{ $agency->clean_domain }}</code>.
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-blue-600/30 transition-all">
                Save reCAPTCHA Settings
            </button>
        </div>
    </form>
    @endif

</div>

<script>
    function openLeadModal(name, email, subject, message, date, ip, id) {
        document.getElementById('modalSender').innerText = name;
        document.getElementById('modalEmail').innerText = email;
        document.getElementById('modalSubject').innerText = subject || 'General Inquiry';
        document.getElementById('modalMessage').innerText = message;
        document.getElementById('modalDate').innerText = date;
        document.getElementById('modalIp').innerText = ip || 'N/A';
        document.getElementById('modalMailto').href = 'mailto:' + encodeURIComponent(email) + '?subject=' + encodeURIComponent('Re: ' + (subject || 'Your Inquiry'));
        document.getElementById('leadModal').classList.remove('hidden');
    }

    function closeLeadModal() {
        document.getElementById('leadModal').classList.add('hidden');
    }
</script>
@endsection
