<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access the Super Admin Panel.');
        }

        $user = Auth::user();

        if ($user->role !== 'super_admin' || $user->status !== 'active') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Access Denied: You do not have Super Admin permissions.');
        }

        return $next($request);
    }
}
