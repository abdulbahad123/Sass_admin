@extends('layouts.whitelabel')

@section('title', 'End-Clients Directory')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 font-heading">Client Management</h2>
            <p class="text-xs text-slate-500">{{ count($clients) }} Onboarded clients under {{ $agency->name ?? 'Apex Digital Agency' }}</p>
        </div>
        <button onclick="document.getElementById('addClientModal').classList.remove('hidden')" 
                class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-600/30 flex items-center space-x-1.5">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>+ Add New Client</span>
        </button>
    </div>

    <div class="card-white rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-bold uppercase text-slate-400">
                        <th class="pb-3">Client Name</th>
                        <th class="pb-3">Contact Person</th>
                        <th class="pb-3">Email</th>
                        <th class="pb-3">Plan</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Joined On</th>
                        <th class="pb-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($clients as $client)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 font-bold text-slate-900">{{ $client['name'] }}</td>
                            <td class="py-4 text-slate-600">{{ $client['contact'] }}</td>
                            <td class="py-4 text-slate-500 font-mono text-[11px]">{{ $client['email'] }}</td>
                            <td class="py-4"><span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700">{{ $client['plan'] }}</span></td>
                            <td class="py-4"><span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">{{ $client['status'] }}</span></td>
                            <td class="py-4 text-slate-500">{{ $client['joined'] }}</td>
                            <td class="py-4 text-right">
                                <button class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px]">Manage</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 text-xs font-medium">
                                No end-clients onboarded yet. Click <strong>+ Add New Client</strong> to add one!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
