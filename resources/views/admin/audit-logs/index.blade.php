@extends('layouts.admin')

@section('title', 'Platform Audit Logs')
@section('page_title', 'Security Audit Trail & Admin Action Logs')

@section('content')
<div class="space-y-6">

    <div class="p-6 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900 font-heading">Security Audit Enforcement</h3>
                <p class="text-xs text-slate-600">All administrative operations, product modifications, agency edits, and logins are recorded with IP timestamps.</p>
            </div>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card-white rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="pb-3">Timestamp</th>
                        <th class="pb-3">Admin User</th>
                        <th class="pb-3">Action Description</th>
                        <th class="pb-3">IP Address</th>
                        <th class="pb-3 text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @foreach($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 text-slate-500 font-mono text-[11px]">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="py-3.5 font-bold text-slate-900">
                                {{ $log->user_name }}
                            </td>
                            <td class="py-3.5 text-slate-800 font-medium">
                                {{ $log->action }}
                            </td>
                            <td class="py-3.5 text-indigo-600 font-mono text-[11px]">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                            <td class="py-3.5 text-right font-mono text-[10px] text-slate-400 truncate max-w-xs">
                                {{ json_encode($log->details ?? []) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
