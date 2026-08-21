<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class WhiteLabelMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access the White Label Dashboard.');
        }

        $user = Auth::user();

        // Allow white_label_agency, master_agency, or super_admin
        if ($user->role !== 'white_label_agency' && $user->role !== 'master_agency' && $user->role !== 'super_admin') {
            return redirect()->route('login')->with('error', 'Access Denied: White Label Agency permissions required.');
        }

        return $next($request);
    }
}
