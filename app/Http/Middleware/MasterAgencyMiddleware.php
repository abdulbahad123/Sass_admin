<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MasterAgencyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access the Master Agency Dashboard.');
        }

        $user = Auth::user();

        // Allow super_admin or master_agency
        if ($user->role !== 'master_agency' && $user->role !== 'super_admin') {
            return redirect()->route('login')->with('error', 'Access Denied: Master Agency permissions required.');
        }

        return $next($request);
    }
}
