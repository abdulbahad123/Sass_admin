<?php

namespace App\Http\Controllers\WhiteLabel;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhiteLabelProductController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $agency = $user->agency ?? Agency::where('type', 'white_label')->first();
        $agencyProducts = null;
        if ($agency) {
            $agencyProducts = $agency->products()
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('agency_products.status')
                      ->orWhere('agency_products.status', 'enabled');
                })
                ->get();
        }

        $products = ($agencyProducts && $agencyProducts->isNotEmpty()) 
            ? $agencyProducts 
            : Product::where('is_active', true)->get();

        $orderMap = [
            'launchshop' => 1,
            'website-builder' => 2,
            'whatsappbulk' => 3,
        ];

        $products = $products->sortBy(function ($prod) use ($orderMap) {
            return $orderMap[$prod->slug] ?? 99;
        })->values();

        return view('whitelabel.products.index', compact('user', 'agency', 'products'));
    }
}
