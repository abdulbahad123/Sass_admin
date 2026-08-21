<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['agency', 'plan'])->latest()->get();
        $totalRevenue = Subscription::where('status', 'active')->sum('amount');
        $activeCount = Subscription::where('status', 'active')->count();

        return view('admin.subscriptions.index', compact('subscriptions', 'totalRevenue', 'activeCount'));
    }

    public function updateStatus(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,trial,past_due,cancelled',
        ]);

        $subscription->update(['status' => $validated['status']]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'action' => "Updated Subscription Status for Agency {$subscription->agency->name} to {$validated['status']}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Subscription status updated.");
    }
}
