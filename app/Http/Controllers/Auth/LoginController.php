<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isSuperAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isMasterAgency()) {
                return redirect()->route('master.dashboard');
            } elseif ($user->isWhiteLabelAgency()) {
                return redirect()->route('whitelabel.dashboard');
            }
        }

        $host = $request->getHost();
        $agency = \App\Models\Agency::whereNotNull('custom_domain')
            ->where(function ($q) use ($host) {
                $q->where('custom_domain', $host)
                  ->orWhere('custom_domain', 'like', "%{$host}%");
            })->first();

        return view('auth.login', compact('agency'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withErrors(['email' => 'Account is inactive. Please contact support.']);
            }

            if (!$user->isSuperAdmin() && !$user->isMasterAgency() && !$user->isWhiteLabelAgency()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Access denied. Administrative privileges required.']);
            }

            $request->session()->regenerate();

            $panelName = $user->isSuperAdmin() ? 'Super Admin Panel' : ($user->isMasterAgency() ? 'Master Agency Portal' : 'White Label Dashboard');

            AuditLog::create([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'action' => "Logged into {$panelName}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($user->isWhiteLabelAgency()) {
                return redirect()->intended(route('whitelabel.dashboard'));
            } elseif ($user->isMasterAgency()) {
                return redirect()->intended(route('master.dashboard'));
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $panelName = $user->isSuperAdmin() ? 'Super Admin Panel' : 'Master Agency Portal';
            AuditLog::create([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'action' => "Logged out of {$panelName}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
