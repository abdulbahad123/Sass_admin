<?php

namespace App\Http\Controllers\MasterAgency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterSubscriptionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $masterAgency = $user->agency ?? Agency::where('type', 'master')->first();
        $masterAgencyId = $masterAgency->id ?? 0;

        $subAgencyIds = Agency::where('parent_id', $masterAgencyId)->pluck('id');

        $subscriptions = Subscription::whereIn('agency_id', $subAgencyIds)
            ->with(['agency', 'plan'])
            ->get();

        return view('master.subscriptions.index', compact('subscriptions'));
    }

    public function updateStatus(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:active,trial,past_due,cancelled',
        ]);

        $subscription->update(['status' => $validated['status']]);

        return redirect()->route('master.subscriptions.index')->with('success', 'Subscription status updated.');
    }
}
