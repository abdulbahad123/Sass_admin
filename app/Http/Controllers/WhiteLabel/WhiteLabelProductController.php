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

        $products = $products->sortBy(function ($prod) {
            $identifier = strtolower(trim(($prod->slug ?? '') . ' ' . ($prod->name ?? '')));
            // 1) Ecom Builder
            if (str_contains($identifier, 'ecom') || str_contains($identifier, 'launchshop') || str_contains($identifier, 'store') || str_contains($identifier, 'shop')) {
                return 1;
            }
            // 2) Website Builder
            if (str_contains($identifier, 'website') || str_contains($identifier, 'site') || (str_contains($identifier, 'builder') && !str_contains($identifier, 'ecom'))) {
                return 2;
            }
            // 3) WhatsApp Bulk
            if (str_contains($identifier, 'whatsapp') || str_contains($identifier, 'bulk') || str_contains($identifier, 'wa')) {
                return 3;
            }
            return 99;
        })->values();

        return view('whitelabel.products.index', compact('user', 'agency', 'products'));
    }
}
