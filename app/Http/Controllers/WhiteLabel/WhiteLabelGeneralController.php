<?php

namespace App\Http\Controllers\WhiteLabel;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhiteLabelGeneralController extends Controller
{
    public function team()
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

        $team = User::where('agency_id', $agency->id ?? 0)
            ->whereIn('role', ['team_member', 'white_label_agency'])
            ->get();

        return view('whitelabel.team.index', compact('user', 'agency', 'team'));
    }

    public function activityLogs()
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

        $activities = AuditLog::latest()->take(30)->get();

        return view('whitelabel.activity.index', compact('user', 'agency', 'activities'));
    }

    public function billing()
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

        $subClients = User::where('agency_id', $agency->id ?? 0)->where('role', 'client')->get();

        $invoices = [];
        foreach ($subClients as $client) {
            $invoices[] = [
                'id' => 'INV-WL-' . str_pad($client->id, 3, '0', STR_PAD_LEFT),
                'client' => $client->name,
                'amount' => '₹' . number_format(24900, 0),
                'date' => $client->created_at ? $client->created_at->format('M d, Y') : now()->format('M d, Y'),
                'status' => ucfirst($client->status === 'active' ? 'Paid' : 'Pending'),
            ];
        }

        return view('whitelabel.billing.index', compact('user', 'agency', 'invoices'));
    }

    public function branding()
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

        return view('whitelabel.branding.index', compact('user', 'agency'));
    }

    public function updateBranding(Request $request)
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

        if (!$agency) {
            return back()->with('error', 'Agency profile not found in database.');
        }

        $validated = $request->validate([
            'agency_name' => 'required|string|max:255',
            'custom_domain' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:50',
        ]);

        $agency->update([
            'name' => $validated['agency_name'],
            'custom_domain' => $validated['custom_domain'] ?? $agency->custom_domain,
            'primary_color' => $validated['primary_color'] ?? $agency->primary_color,
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => "Updated custom agency branding: {$agency->name}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Custom branding & agency settings for '{$agency->name}' saved successfully!");
    }
}
