<?php

namespace App\Http\Controllers\MasterAgency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\Request;

use App\Models\AuditLog;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MasterClientController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $masterAgency = $user->agency ?? Agency::where('type', 'master')->first();
        $masterAgencyId = $masterAgency->id ?? 0;

        // Sub-agencies managed under this master agency
        $subAgencies = Agency::where('parent_id', $masterAgencyId)->get();
        $subAgencyIds = $subAgencies->pluck('id')->toArray();
        $subAgencyIds[] = $masterAgencyId;

        $clientUsers = User::whereIn('agency_id', array_filter($subAgencyIds))
            ->where('role', 'client')
            ->with(['agency', 'product'])
            ->latest()
            ->get();

        $products = Product::where('is_active', true)->get();
        $defaultProduct = $products->firstWhere('slug', 'launchshop') ?? $products->first();

        $clients = [];
        $productCounts = [];

        foreach ($clientUsers as $c) {
            $productName = $c->product->name ?? ($defaultProduct->name ?? 'Launchshop');
            $clients[] = [
                'id' => $c->id,
                'name' => $c->name,
                'owner' => $c->name,
                'email' => $c->email,
                'sub_agency' => $c->agency->name ?? 'Direct Network Client',
                'product' => $productName,
                'product_id' => $c->product_id ?? ($defaultProduct->id ?? null),
                'plan' => 'Growth',
                'status' => ucfirst($c->status ?? 'Active'),
                'joined' => $c->created_at ? $c->created_at->format('M d, Y') : now()->format('M d, Y'),
            ];

            if (!isset($productCounts[$productName])) {
                $productCounts[$productName] = 0;
            }
            $productCounts[$productName]++;
        }

        return view('master.clients.index', compact('user', 'masterAgency', 'subAgencies', 'clients', 'products', 'productCounts'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $masterAgency = $user->agency ?? Agency::where('type', 'master')->first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'owner' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'sub_agency_id' => 'nullable|exists:agencies,id',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $agencyId = $validated['sub_agency_id'] ?? ($masterAgency->id ?? 0);
        $defaultProduct = Product::where('slug', 'launchshop')->first() ?? Product::first();

        $client = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('password'),
            'role' => 'client',
            'agency_id' => $agencyId,
            'product_id' => $validated['product_id'] ?? ($defaultProduct->id ?? null),
            'status' => 'active',
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => "Master Agency onboarded client '{$client->name}'",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('master.clients.index')->with('success', "End-client account '{$client->name}' onboarded into network database successfully!");
    }
}
