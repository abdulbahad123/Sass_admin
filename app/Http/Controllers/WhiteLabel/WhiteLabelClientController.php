<?php

namespace App\Http\Controllers\WhiteLabel;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class WhiteLabelClientController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

        $clientUsers = User::where('agency_id', $agency->id ?? 0)
            ->where('role', 'client')
            ->latest()
            ->get();

        $clients = [];
        foreach ($clientUsers as $c) {
            $clients[] = [
                'id' => $c->id,
                'name' => $c->name,
                'contact' => $c->name,
                'email' => $c->email,
                'plan' => 'Growth',
                'status' => ucfirst($c->status ?? 'active'),
                'joined' => $c->created_at ? $c->created_at->format('M d, Y') : now()->format('M d, Y'),
            ];
        }

        return view('whitelabel.clients.index', compact('user', 'agency', 'clients'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'plan' => 'required|string',
        ]);

        $client = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('password'),
            'role' => 'client',
            'agency_id' => $agency->id ?? 0,
            'status' => 'active',
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => "New client '{$client->name}' onboarded",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', "Client '{$client->name}' onboarded successfully into White Label Network!");
    }
}
