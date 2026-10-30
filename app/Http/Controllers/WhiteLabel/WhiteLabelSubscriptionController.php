<?php

namespace App\Http\Controllers\WhiteLabel;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhiteLabelSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $agency = Agency::resolveCurrent($request, $user);
        $subscriptions = Subscription::with(['plan'])->take(10)->get();

        return view('whitelabel.subscriptions.index', compact('user', 'agency', 'subscriptions'));
    }
}
