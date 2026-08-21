@extends('layouts.admin')

@section('title', 'Platform Settings & Profile')
@section('page_title', 'Super Admin Profile & Platform Currency Settings')

@section('content')
<div class="space-y-8 max-w-5xl">

    <!-- Header Banner -->
    <div class="p-6 rounded-2xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-violet-900 text-white shadow-lg flex items-center space-x-4">
        <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center font-bold text-xl flex-shrink-0 border border-white/20">
            <i data-lucide="settings" class="w-6 h-6"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold font-heading">Super Admin Profile & Platform Preferences</h2>
            <p class="text-xs text-indigo-200 mt-1">Configure your administrative profile, upload avatar picture, and switch global platform currency symbols (INR ₹, USD $, EUR €, etc.).</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Platform Currency Settings Card -->
        <div class="card-white rounded-2xl p-6">
            <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="coins" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 font-heading">Platform Currency Settings</h3>
                    <p class="text-xs text-slate-500">Controls currency display across all revenue stats and plans</p>
                </div>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Platform Name</label>
                    <input type="text" name="platform_name" value="{{ $platformName }}" required 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Select Active Currency</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <label class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center space-x-2.5 cursor-pointer hover:border-indigo-500 transition-colors">
                            <input type="radio" name="currency_preset" value="INR|₹" {{ $currencyCode === 'INR' ? 'checked' : '' }}
                                   onchange="document.getElementById('c_code').value='INR'; document.getElementById('c_sym').value='₹';" class="text-indigo-600">
                            <div>
                                <span class="block text-xs font-bold text-slate-900">INR (₹)</span>
                                <span class="block text-[10px] text-slate-500">Indian Rupee</span>
                            </div>
                        </label>

                        <label class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center space-x-2.5 cursor-pointer hover:border-indigo-500 transition-colors">
                            <input type="radio" name="currency_preset" value="USD|$" {{ $currencyCode === 'USD' ? 'checked' : '' }}
                                   onchange="document.getElementById('c_code').value='USD'; document.getElementById('c_sym').value='$';" class="text-indigo-600">
                            <div>
                                <span class="block text-xs font-bold text-slate-900">USD ($)</span>
                                <span class="block text-[10px] text-slate-500">US Dollar</span>
                            </div>
                        </label>

                        <label class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center space-x-2.5 cursor-pointer hover:border-indigo-500 transition-colors">
                            <input type="radio" name="currency_preset" value="EUR|€" {{ $currencyCode === 'EUR' ? 'checked' : '' }}
                                   onchange="document.getElementById('c_code').value='EUR'; document.getElementById('c_sym').value='€';" class="text-indigo-600">
                            <div>
                                <span class="block text-xs font-bold text-slate-900">EUR (€)</span>
                                <span class="block text-[10px] text-slate-500">Euro</span>
                            </div>
                        </label>

                        <label class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center space-x-2.5 cursor-pointer hover:border-indigo-500 transition-colors">
                            <input type="radio" name="currency_preset" value="GBP|£" {{ $currencyCode === 'GBP' ? 'checked' : '' }}
                                   onchange="document.getElementById('c_code').value='GBP'; document.getElementById('c_sym').value='£';" class="text-indigo-600">
                            <div>
                                <span class="block text-xs font-bold text-slate-900">GBP (£)</span>
                                <span class="block text-[10px] text-slate-500">Pound</span>
                            </div>
                        </label>

                        <label class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center space-x-2.5 cursor-pointer hover:border-indigo-500 transition-colors">
                            <input type="radio" name="currency_preset" value="AED|AED" {{ $currencyCode === 'AED' ? 'checked' : '' }}
                                   onchange="document.getElementById('c_code').value='AED'; document.getElementById('c_sym').value='AED';" class="text-indigo-600">
                            <div>
                                <span class="block text-xs font-bold text-slate-900">AED</span>
                                <span class="block text-[10px] text-slate-500">Dirham</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Currency Code</label>
                        <input type="text" id="c_code" name="currency_code" value="{{ $currencyCode }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Currency Symbol</label>
                        <input type="text" id="c_sym" name="currency_symbol" value="{{ $currencySymbol }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center space-x-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Save Currency Settings</span>
                </button>
            </form>
        </div>

        <!-- Super Admin Profile Credentials & Avatar Upload (Task 2 Implementation) -->
        <div class="card-white rounded-2xl p-6">
            <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 font-heading">Super Admin Profile & Avatar</h3>
                    <p class="text-xs text-slate-500">Upload profile image and update credentials</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- Profile Avatar Image Upload Field (Task 2) -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Profile Image Avatar</label>
                    <div class="flex items-center space-x-4">
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="Avatar" class="w-16 h-16 rounded-full object-cover ring-4 ring-indigo-100 border border-indigo-200">
                        @else
                            <div class="w-16 h-16 rounded-full bg-indigo-600 text-white font-extrabold text-xl flex items-center justify-center ring-4 ring-indigo-100">
                                {{ substr($user->name, 0, 2) }}
                            </div>
                        @endif
                        <div class="flex-1">
                            <input type="file" name="avatar" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                            <p class="text-[10px] text-slate-400 mt-1">Upload JPG, PNG, WEBP or SVG (Max 4MB)</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Admin Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" required 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Admin Email Address</label>
                    <input type="email" name="email" value="{{ $user->email }}" required 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <p class="text-xs font-bold text-indigo-600 mb-3">Change Security Password (Optional)</p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">New Password</label>
                            <input type="password" name="password" placeholder="Leave blank to keep current" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" placeholder="Confirm new password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center space-x-2">
                    <i data-lucide="shield" class="w-4 h-4"></i>
                    <span>Update Account Credentials & Avatar</span>
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
