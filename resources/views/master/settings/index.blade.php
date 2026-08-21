@extends('layouts.master')

@section('title', 'Settings & Security')

@section('content')
<div class="space-y-6 max-w-3xl">

    <div>
        <h2 class="text-xl font-bold text-slate-900 font-heading">Master Admin Settings</h2>
        <p class="text-xs text-slate-500">Update account profile information and security credentials</p>
    </div>

    <div class="card-white rounded-2xl p-6">
        <form action="{{ route('master.settings.update') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Master Admin Full Name</label>
                <input type="text" name="name" value="{{ $user->name ?? 'Rahul Sharma' }}" required 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Master Admin Email</label>
                <input type="email" name="email" value="{{ $user->email ?? 'rahul@apexmaster.com' }}" required 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
            </div>

            <div class="pt-3 border-t border-slate-100 space-y-4">
                <p class="text-xs font-bold text-indigo-600">Change Account Password (Optional)</p>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">New Password</label>
                    <input type="password" name="password" placeholder="Leave blank to keep current" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" placeholder="Confirm new password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>
            </div>

            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 flex items-center space-x-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Update Credentials</span>
            </button>
        </form>
    </div>

</div>
@endsection
