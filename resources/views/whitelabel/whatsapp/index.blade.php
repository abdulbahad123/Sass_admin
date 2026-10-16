@extends('layouts.whitelabel')

@section('title', 'WhatsApp Gateway & Message Templates')

@section('content')
<div class="space-y-6 max-w-5xl">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 font-heading flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-md shadow-emerald-600/20">
                    <i data-lucide="message-square" class="w-5 h-5"></i>
                </div>
                WhatsApp Gateway & Notification Templates
            </h2>
            <p class="text-xs text-slate-500 mt-1">Configure your WhatsApp API credentials and customize sending formats with real-time placeholders</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Dedicated Agency Gateway Active
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
            <p class="text-xs font-bold">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 flex items-center gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 shrink-0"></i>
            <p class="text-xs font-bold">{{ session('error') }}</p>
        </div>
    @endif

    <form action="{{ route('whitelabel.whatsapp-gateway.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Automatically maintained active state and server database routing (hidden from user) -->
        <input type="hidden" name="is_whatsapp_active" value="1">
        <input type="hidden" name="is_whatsapp_otp_active" value="1">
        <input type="hidden" name="is_whatsapp_credentials_active" value="1">
        <input type="hidden" name="is_whatsapp_invoice_active" value="1">
        <input type="hidden" name="whatsapp_gateway_url" value="{{ $autoGateway ?? 'http://127.0.0.1:8000' }}">
        <input type="hidden" name="whatsapp_database" value="{{ $autoDb ?? 'nooryak_ps_youverse_whatsapp' }}">

        <!-- 1. Gateway Connection & API Keys Card -->
        <div class="card-white rounded-2xl p-6 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">
                        <i data-lucide="key" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 font-heading">Custom Agency WhatsApp Gateway</h3>
                        <p class="text-[11px] text-slate-500">Provide your dedicated WhatsApp API Key / Token and Instance ID from your WhatsApp Bulk account.</p>
                    </div>
                </div>
            </div>

            <!-- Credentials Grid: Clean view showing only editable API Key & Instance ID -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- API Key / Access Token (Editable) -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-700">API Key / Access Token <span class="text-rose-500">*</span></label>
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Required</span>
                    </div>
                    <input type="text" name="whatsapp_access_token" id="whatsapp_access_token"
                           value="{{ $agency->whatsapp_access_token ?? '' }}" 
                           placeholder="e.g. team_0c4d122df386433837b99c94245"
                           required
                           class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-mono focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 focus:outline-none">
                    <p class="text-[10px] text-slate-400 mt-1">API Bearer token or team authentication key.</p>
                </div>

                <!-- Instance ID (Editable) -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-700">Instance ID <span class="text-rose-500">*</span></label>
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Required</span>
                    </div>
                    <input type="text" name="whatsapp_instance_id" id="whatsapp_instance_id"
                           value="{{ $agency->whatsapp_instance_id ?? '' }}" 
                           placeholder="e.g. 43192DD2DFD7"
                           required
                           class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-mono focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 focus:outline-none">
                    <p class="text-[10px] text-slate-400 mt-1">Linked WhatsApp account instance token.</p>
                </div>
            </div>
        </div>

        <!-- 2. Message Formats & Dynamic Placeholders Card -->
        <div class="card-white rounded-2xl p-6 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs">
                        <i data-lucide="file-code" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 font-heading">Sending Formats & Real-Time Placeholders</h3>
                        <p class="text-[11px] text-slate-500">Click any placeholder tag below to insert it into your message template. Use <code class="text-slate-800 font-bold">*text*</code> for bold and WhatsApp emojis.</p>
                    </div>
                </div>
            </div>

            <!-- Template 1: OTP Verification -->
            <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">1. OTP Verification Message Format</h4>
                    </div>

                    <button type="button" onclick="resetDefault('otp_textarea')" 
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 hover:text-slate-900 text-xs font-bold transition-all shadow-sm">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-slate-500"></i>
                        <span>Reset to Default</span>
                    </button>
                </div>

                <!-- Clickable Placeholders -->
                <div class="flex flex-wrap items-center gap-1.5 pt-1">
                    <span class="text-[11px] font-bold text-slate-500 mr-1">Available Placeholders:</span>
                    <button type="button" onclick="insertPlaceholder('otp_textarea', '{otp}')" 
                            class="px-2.5 py-1 rounded-lg bg-white border border-slate-300 hover:border-emerald-500 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 text-xs font-mono font-bold transition-all shadow-sm">
                        {otp}
                    </button>
                    <button type="button" onclick="insertPlaceholder('otp_textarea', '{product_name}')" 
                            class="px-2.5 py-1 rounded-lg bg-white border border-slate-300 hover:border-emerald-500 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 text-xs font-mono font-bold transition-all shadow-sm">
                        {product_name}
                    </button>
                    <button type="button" onclick="insertPlaceholder('otp_textarea', '{validity_minutes}')" 
                            class="px-2.5 py-1 rounded-lg bg-white border border-slate-300 hover:border-emerald-500 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 text-xs font-mono font-bold transition-all shadow-sm">
                        {validity_minutes}
                    </button>
                </div>

                <textarea id="otp_textarea" name="whatsapp_otp_template" rows="3"
                          class="w-full bg-white border border-slate-200 rounded-xl p-3 text-xs text-slate-800 font-sans leading-relaxed focus:outline-none focus:border-emerald-600"
                          placeholder="Your OTP message template...">{{ $agency->whatsapp_otp_template ?? $systemDefaults['whatsapp_otp_template'] }}</textarea>
            </div>

            <!-- Template 2: Website Launch / Store Credentials -->
            <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">2. Store Launch & Account Credentials Format</h4>
                    </div>

                    <button type="button" onclick="resetDefault('creds_textarea')" 
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 hover:text-slate-900 text-xs font-bold transition-all shadow-sm">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-slate-500"></i>
                        <span>Reset to Default</span>
                    </button>
                </div>

                <!-- Clickable Placeholders -->
                <div class="flex flex-wrap items-center gap-1.5 pt-1">
                    <span class="text-[11px] font-bold text-slate-500 mr-1">Available Placeholders:</span>
                    @foreach(['{user_name}', '{store_name}', '{email}', '{password}', '{store_url}', '{login_url}', '{product_name}', '{plan_name}'] as $tag)
                        <button type="button" onclick="insertPlaceholder('creds_textarea', '{{ $tag }}')" 
                                class="px-2.5 py-1 rounded-lg bg-white border border-slate-300 hover:border-blue-500 hover:bg-blue-50 text-slate-700 hover:text-blue-700 text-xs font-mono font-bold transition-all shadow-sm">
                            {{ $tag }}
                        </button>
                    @endforeach
                </div>

                <textarea id="creds_textarea" name="whatsapp_credentials_template" rows="7"
                          class="w-full bg-white border border-slate-200 rounded-xl p-3 text-xs text-slate-800 font-sans leading-relaxed focus:outline-none focus:border-blue-600"
                          placeholder="Store credentials template...">{{ $agency->whatsapp_credentials_template ?? $systemDefaults['whatsapp_credentials_template'] }}</textarea>
            </div>

            <!-- Template 3: Payment Receipt & Invoices -->
            <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">3. Payment Receipt & Invoice Confirmation Format</h4>
                    </div>

                    <button type="button" onclick="resetDefault('invoice_textarea')" 
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 hover:text-slate-900 text-xs font-bold transition-all shadow-sm">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-slate-500"></i>
                        <span>Reset to Default</span>
                    </button>
                </div>

                <!-- Clickable Placeholders -->
                <div class="flex flex-wrap items-center gap-1.5 pt-1">
                    <span class="text-[11px] font-bold text-slate-500 mr-1">Available Placeholders:</span>
                    @foreach(['{user_name}', '{invoice_id}', '{amount}', '{plan_title}', '{payment_method}', '{invoice_url}', '{product_name}'] as $tag)
                        <button type="button" onclick="insertPlaceholder('invoice_textarea', '{{ $tag }}')" 
                                class="px-2.5 py-1 rounded-lg bg-white border border-slate-300 hover:border-purple-500 hover:bg-purple-50 text-slate-700 hover:text-purple-700 text-xs font-mono font-bold transition-all shadow-sm">
                            {{ $tag }}
                        </button>
                    @endforeach
                </div>

                <textarea id="invoice_textarea" name="whatsapp_invoice_template" rows="6"
                          class="w-full bg-white border border-slate-200 rounded-xl p-3 text-xs text-slate-800 font-sans leading-relaxed focus:outline-none focus:border-purple-600"
                          placeholder="Invoice receipt template...">{{ $agency->whatsapp_invoice_template ?? $systemDefaults['whatsapp_invoice_template'] }}</textarea>
            </div>

            <!-- Save Changes Button -->
            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center space-x-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Save All WhatsApp Gateway Settings</span>
                </button>
            </div>
        </div>

    </form>

    <!-- 3. Real-Time WhatsApp Test Delivery Card -->
    <div class="card-white rounded-2xl p-6 space-y-4" x-data="{
        phone: '',
        type: 'otp',
        customMsg: '',
        loading: false,
        result: null,
        sendTest() {
            if (!this.phone) {
                alert('Please enter a target mobile number.');
                return;
            }
            this.loading = true;
            this.result = null;

            const tokenInput = document.getElementById('whatsapp_access_token');
            const instanceInput = document.getElementById('whatsapp_instance_id');

            fetch('{{ route('whitelabel.whatsapp-gateway.test') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    test_phone: this.phone,
                    test_type: this.type,
                    custom_message: this.customMsg,
                    access_token: tokenInput ? tokenInput.value : '',
                    instance_id: instanceInput ? instanceInput.value : ''
                })
            })
            .then(res => res.json())
            .then(data => {
                this.loading = false;
                this.result = data;
            })
            .catch(err => {
                this.loading = false;
                this.result = { success: false, error: err.message };
            });
        }
    }">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">
                    <i data-lucide="send" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 font-heading">Real-Time WhatsApp Connection Tester</h3>
                    <p class="text-[11px] text-slate-500">Send an instant test notification to any phone number to verify gateway credentials and formatting</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Target Mobile Number</label>
                <input type="text" x-model="phone" placeholder="e.g. 918667702473"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-mono focus:bg-white focus:outline-none focus:border-amber-600">
                <p class="text-[10px] text-slate-400 mt-1">Include country code (e.g. 91 for India).</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Test Template Type</label>
                <select x-model="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-bold focus:bg-white focus:outline-none focus:border-amber-600">
                    <option value="otp">1. OTP Verification Message</option>
                    <option value="credentials">2. Website Launch / Store Credentials</option>
                    <option value="invoice">3. Payment Receipt / Invoice</option>
                    <option value="custom">4. Custom Text Message</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="button" @click="sendTest()" :disabled="loading"
                        class="w-full py-2.5 bg-slate-900 hover:bg-black text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center justify-center space-x-2 disabled:opacity-50">
                    <span x-show="!loading" class="flex items-center space-x-1.5">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>Send Test WhatsApp</span>
                    </span>
                    <span x-show="loading" class="flex items-center space-x-1.5">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span>Dispatching...</span>
                    </span>
                </button>
            </div>
        </div>

        <!-- Live Response Output -->
        <template x-if="result">
            <div class="p-4 rounded-xl border mt-3 transition-all"
                 :class="result.success ? 'bg-emerald-50 border-emerald-200 text-emerald-950' : 'bg-rose-50 border-rose-200 text-rose-950'">
                <div class="flex items-start justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-bold flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full" :class="result.success ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                            <span x-text="result.success ? 'Test WhatsApp Delivered Successfully!' : 'Delivery Failed'"></span>
                        </p>
                        <p class="text-[11px]" x-show="result.message_preview">
                            <strong>Sent Preview:</strong> <span class="font-mono" x-text="result.message_preview"></span>
                        </p>
                        <p class="text-[10px] text-slate-500 font-mono" x-show="result.gateway_response">
                            Response: <span x-text="JSON.stringify(result.gateway_response)"></span>
                        </p>
                        <p class="text-[10px] text-rose-600 font-mono" x-show="result.error" x-text="result.error"></p>
                    </div>
                    <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-white/60 font-bold" x-text="'HTTP ' + (result.status_code || (result.success ? '200' : '500'))"></span>
                </div>
            </div>
        </template>
    </div>

</div>

<script>
    const defaultTemplates = {
        otp_textarea: @json($systemDefaults['whatsapp_otp_template']),
        creds_textarea: @json($systemDefaults['whatsapp_credentials_template']),
        invoice_textarea: @json($systemDefaults['whatsapp_invoice_template'])
    };

    function resetDefault(textareaId) {
        const el = document.getElementById(textareaId);
        if (el && defaultTemplates[textareaId]) {
            el.value = defaultTemplates[textareaId];
            el.focus();
        }
    }

    function insertPlaceholder(textareaId, tag) {
        const el = document.getElementById(textareaId);
        if (!el) return;
        const start = el.selectionStart || el.value.length;
        const end = el.selectionEnd || el.value.length;
        const text = el.value;
        el.value = text.substring(0, start) + tag + text.substring(end);
        el.focus();
        el.selectionStart = el.selectionEnd = start + tag.length;
    }
</script>
@endsection
