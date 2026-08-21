<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $currencySymbol = Setting::getCurrencySymbol();
        $currencyCode = Setting::getCurrencyCode();
        $platformName = Setting::get('platform_name', 'Master SaaS Engine');

        return view('admin.profile.index', compact('user', 'currencySymbol', 'currencyCode', 'platformName'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:4096',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $filename);
            $user->avatar = '/uploads/avatars/' . $filename;
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => 'Updated Super Admin Profile credentials',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Profile credentials updated successfully!');
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'platform_name' => 'required|string|max:255',
            'currency_code' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
        ]);

        Setting::set('platform_name', $validated['platform_name']);
        Setting::set('currency_code', strtoupper($validated['currency_code']));
        Setting::set('currency_symbol', $validated['currency_symbol']);

        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'action' => "Changed Platform Currency to {$validated['currency_symbol']} ({$validated['currency_code']})",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Platform settings & currency updated to {$validated['currency_symbol']} ({$validated['currency_code']})!");
    }
}
