<?php

namespace App\Http\Controllers\WhiteLabel;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\AuditLog;
use App\Models\Product;
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
            ->with('product')
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
                'contact' => $c->name,
                'email' => $c->email,
                'product_name' => $productName,
                'product_id' => $c->product_id ?? ($defaultProduct->id ?? null),
                'plan' => 'Growth',
                'status' => ucfirst($c->status ?? 'active'),
                'joined' => $c->created_at ? $c->created_at->format('M d, Y') : now()->format('M d, Y'),
            ];

            if (!isset($productCounts[$productName])) {
                $productCounts[$productName] = 0;
            }
            $productCounts[$productName]++;
        }

        return view('whitelabel.clients.index', compact('user', 'agency', 'clients', 'products', 'productCounts'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'plan' => 'required|string',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $defaultProduct = Product::where('slug', 'launchshop')->first() ?? Product::first();

        $client = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('password'),
            'role' => 'client',
            'agency_id' => $agency->id ?? 0,
            'product_id' => $validated['product_id'] ?? ($defaultProduct->id ?? null),
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
