@extends('layouts.master')

@section('title', 'Integrations & Webhooks')

@section('content')
<div class="space-y-6">

    <div>
        <h2 class="text-xl font-bold text-slate-900 font-heading">Integrations & API Webhooks</h2>
        <p class="text-xs text-slate-500">Connect third-party webhooks, WhatsApp API gateways, and payment providers</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card-white rounded-2xl p-6 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center font-bold mb-3">
                    <i data-lucide="webhook" class="w-5 h-5"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 font-heading">Incoming Webhooks</h3>
                <p class="text-xs text-slate-500 mt-1">Receive automated payload triggers for new sub-agency registrations.</p>
            </div>
            <span class="mt-4 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 w-max">Active</span>
        </div>

        <div class="card-white rounded-2xl p-6 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold mb-3">
                    <i data-lucide="message-square" class="w-5 h-5"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 font-heading">WhatsApp API Gateway</h3>
                <p class="text-xs text-slate-500 mt-1">Automated WhatsApp payment alerts & onboarding notifications.</p>
            </div>
            <span class="mt-4 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 w-max">Connected</span>
        </div>

        <div class="card-white rounded-2xl p-6 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center font-bold mb-3">
                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 font-heading">Razorpay / Stripe Payments</h3>
                <p class="text-xs text-slate-500 mt-1">Automated subscription recurring billing collection.</p>
            </div>
            <span class="mt-4 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 w-max">Configured</span>
        </div>
    </div>

</div>
@endsection
