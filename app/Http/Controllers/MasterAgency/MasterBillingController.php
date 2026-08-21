<?php

namespace App\Http\Controllers\MasterAgency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterBillingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $masterAgency = $user->agency ?? Agency::where('type', 'master')->first();
        $masterAgencyId = $masterAgency->id ?? 0;

        $subAgencies = Agency::where('parent_id', $masterAgencyId)->with('subscription')->get();

        $invoices = [];
        foreach ($subAgencies as $sub) {
            if ($sub->subscription) {
                $invoices[] = [
                    'id' => 'INV-2026-' . str_pad($sub->id, 3, '0', STR_PAD_LEFT),
                    'agency' => $sub->name,
                    'amount' => $sub->subscription->amount,
                    'date' => $sub->subscription->created_at ? $sub->subscription->created_at->format('M d, Y') : now()->format('M d, Y'),
                    'status' => ucfirst($sub->subscription->status === 'active' ? 'Paid' : 'Pending'),
                ];
            }
        }

        return view('master.billing.index', compact('invoices'));
    }
}
