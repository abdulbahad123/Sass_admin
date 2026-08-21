<?php

namespace App\Http\Controllers\WhiteLabel;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhiteLabelSubscriptionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();
        $subscriptions = Subscription::with(['plan'])->take(10)->get();

        return view('whitelabel.subscriptions.index', compact('user', 'agency', 'subscriptions'));
    }
}
