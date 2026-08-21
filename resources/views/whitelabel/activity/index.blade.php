@extends('layouts.whitelabel')

@section('title', 'Activity Logs')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-900 font-heading">Agency Activity Logs</h2>
        <p class="text-xs text-slate-500">Real-time audit trail of client onboardings, logins, and system events</p>
    </div>

    <div class="card-white rounded-2xl p-6">
        <div class="space-y-3">
            @foreach($activities as $act)
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                            <i data-lucide="activity" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 leading-tight">{{ $act->action }}</p>
                            <p class="text-[10px] text-slate-400">User: {{ $act->user_name ?? 'Rahul Sharma' }} | IP: {{ $act->ip_address ?? '127.0.0.1' }}</p>
                        </div>
                    </div>
                    <span class="text-[10px] text-slate-400 font-mono">{{ $act->created_at->diffForHumans() }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
