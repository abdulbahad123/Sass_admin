@extends('layouts.admin')

@section('title', 'Global WhatsApp Gateway Settings')
@section('page_title', 'WhatsApp Gateway & Notification Templates')

@section('content')
<div class="space-y-6 max-w-5xl">

    <!-- Header Banner -->
    <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-900 text-white shadow-lg flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center font-bold text-xl flex-shrink-0 border border-white/20">
                <i data-lucide="message-square" class="w-6 h-6 text-emerald-300"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold font-heading">SuperAdmin WhatsApp Gateway & Message Formats</h2>
                <p class="text-xs text-emerald-200 mt-1">Configure global WhatsApp API credentials, active instance, and default notification templates with real-time placeholders for all tenants and white-label agencies.</p>
            </div>
        </div>
        <span class="hidden sm:inline-flex px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
            System Master Gateway
        </span>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
            <p class="text-xs font-bold">{{ session('success') }}</p>
        </div>
    @endif

    <form action="{{ route('admin.whatsapp-gateway.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- 1. Gateway Connection Card -->
        <div class="card-white rounded-2xl p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">
                        <i data-lucide="server" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 font-heading">Global WhatsApp Engine & API Authentication</h3>
                        <p class="text-[11px] text-slate-500">Universal 1-Node Gateway connection settings (inherited by agencies that don't provide custom keys)</p>
                    </div>
                </div>

                <!-- Master Toggle -->
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="whatsapp_is_active" value="1" class="sr-only peer" {{ ($settings['whatsapp_is_active'] ?? 1) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    <span class="ml-2.5 text-xs font-bold text-slate-800">Global WhatsApp Active</span>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Gateway Base URL</label>
                    <input type="text" name="whatsapp_gateway_url" value="{{ $settings['whatsapp_gateway_url'] }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-mono focus:bg-white focus:outline-none focus:border-emerald-600">
                    <p class="text-[10px] text-slate-400 mt-1">Default: http://localhost:8000 or production gateway URL</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Access Token / API Key</label>
                    <input type="text" name="whatsapp_access_token" value="{{ $settings['whatsapp_access_token'] }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-mono focus:bg-white focus:outline-none focus:border-emerald-600">
                    <p class="text-[10px] text-slate-400 mt-1">API Key / Team Bearer Token</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Active Instance ID</label>
                    <input type="text" name="whatsapp_instance_id" value="{{ $settings['whatsapp_instance_id'] }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-mono focus:bg-white focus:outline-none focus:border-emerald-600">
                    <p class="text-[10px] text-slate-400 mt-1">Universal active WhatsApp device instance ID</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Automation Database</label>
                    <input type="text" name="whatsapp_database" value="{{ $settings['whatsapp_database'] }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-mono focus:bg-white focus:outline-none focus:border-emerald-600">
                    <p class="text-[10px] text-slate-400 mt-1">Target database (e.g. nooryak_whatautomation)</p>
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
                        <h3 class="text-sm font-bold text-slate-900 font-heading">Global Sending Formats & Placeholders</h3>
                        <p class="text-[11px] text-slate-500">These default formats are sent to users when verifying OTPs, launching stores, or receiving invoices.</p>
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

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="whatsapp_otp_active" value="1" class="sr-only peer" {{ ($settings['whatsapp_otp_active'] ?? 1) ? 'checked' : '' }}>
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                        <span class="ml-2 text-xs font-semibold text-slate-700">Active</span>
                    </label>
                </div>

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
                          class="w-full bg-white border border-slate-200 rounded-xl p-3 text-xs text-slate-800 font-sans leading-relaxed focus:outline-none focus:border-emerald-600">{{ $settings['whatsapp_otp_template'] }}</textarea>
            </div>

            <!-- Template 2: Website Launch / Store Credentials -->
            <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">2. Store Launch & Account Credentials Format</h4>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="whatsapp_credentials_active" value="1" class="sr-only peer" {{ ($settings['whatsapp_credentials_active'] ?? 1) ? 'checked' : '' }}>
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-2 text-xs font-semibold text-slate-700">Active</span>
                    </label>
                </div>

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
                          class="w-full bg-white border border-slate-200 rounded-xl p-3 text-xs text-slate-800 font-sans leading-relaxed focus:outline-none focus:border-blue-600">{{ $settings['whatsapp_credentials_template'] }}</textarea>
            </div>

            <!-- Template 3: Payment Receipt & Invoices -->
            <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">3. Payment Receipt & Invoice Confirmation Format</h4>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="whatsapp_invoice_active" value="1" class="sr-only peer" {{ ($settings['whatsapp_invoice_active'] ?? 1) ? 'checked' : '' }}>
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-600"></div>
                        <span class="ml-2 text-xs font-semibold text-slate-700">Active</span>
                    </label>
                </div>

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
                          class="w-full bg-white border border-slate-200 rounded-xl p-3 text-xs text-slate-800 font-sans leading-relaxed focus:outline-none focus:border-purple-600">{{ $settings['whatsapp_invoice_template'] }}</textarea>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center space-x-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Save Global WhatsApp Settings</span>
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

            fetch('{{ route('admin.whatsapp-gateway.test') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    test_phone: this.phone,
                    test_type: this.type,
                    custom_message: this.customMsg
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
                    <h3 class="text-sm font-bold text-slate-900 font-heading">SuperAdmin WhatsApp Gateway Tester</h3>
                    <p class="text-[11px] text-slate-500">Dispatch an immediate test WhatsApp notification to any phone number</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Target Mobile Number</label>
                <input type="text" x-model="phone" placeholder="e.g. 918667702473"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-mono focus:bg-white focus:outline-none focus:border-amber-600">
                <p class="text-[10px] text-slate-400 mt-1">Include country code without + (e.g. 919876543210)</p>
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
