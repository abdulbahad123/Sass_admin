@extends('layouts.master')

@section('title', 'Custom Branding')

@section('content')
<div class="space-y-6 max-w-4xl">

    <div>
        <h2 class="text-xl font-bold text-slate-900 font-heading">Custom White-Label Branding</h2>
        <p class="text-xs text-slate-500">Configure logo, custom CNAME domain, and accent color scheme for your network portal</p>
    </div>

    <div class="card-white rounded-2xl p-6">
        <form action="{{ route('master.branding.update') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Master Agency Name</label>
                <input type="text" name="name" value="{{ $agency->name ?? 'Apex Master Ventures' }}" required 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Custom CNAME Domain</label>
                <input type="text" name="custom_domain" value="{{ $agency->custom_domain ?? 'partner.apexmaster.com' }}" placeholder="partner.agency.com" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 font-mono">
                <p class="text-[10px] text-slate-400 mt-1">Point CNAME DNS record to <code>cname.platform.app</code></p>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Primary Theme Accent Color</label>
                <div class="flex items-center space-x-3">
                    <input type="color" id="primary_color_picker" name="primary_color" value="{{ $agency->primary_color ?? '#4f46e5' }}" 
                           oninput="document.getElementById('primary_color_text').value = this.value"
                           class="w-10 h-10 rounded-xl border border-slate-200 cursor-pointer">
                    <input type="text" id="primary_color_text" value="{{ $agency->primary_color ?? '#4f46e5' }}" 
                           oninput="if(this.value.length === 7 && this.value.startsWith('#')) document.getElementById('primary_color_picker').value = this.value"
                           class="w-32 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 font-mono">
                </div>
            </div>

            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 flex items-center space-x-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Save Branding Settings</span>
            </button>
        </form>
    </div>

</div>
@endsection
