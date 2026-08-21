@extends('layouts.whitelabel')

@section('title', 'Team Members')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 font-heading">Team Members & Staff Access</h2>
            <p class="text-xs text-slate-500">Manage internal team members for your White Label agency</p>
        </div>
        <button class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-600/30">+ Invite Member</button>
    </div>

    <div class="card-white rounded-2xl p-6">
        <div class="space-y-4">
            @foreach($team as $m)
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">
                            {{ substr($m['name'], 0, 2) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 leading-tight">{{ $m['name'] }}</h4>
                            <p class="text-slate-500 text-[11px]">{{ $m['email'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-violet-100 text-violet-700 uppercase">{{ $m['role'] }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">{{ $m['status'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
