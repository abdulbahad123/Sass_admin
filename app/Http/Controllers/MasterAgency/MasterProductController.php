<?php

namespace App\Http\Controllers\MasterAgency;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class MasterProductController extends Controller
{
    public function index()
    {
        $orderMap = [
            'launchshop' => 1,
            'website-builder' => 2,
            'whatsappbulk' => 3,
        ];

        $products = Product::where('is_active', true)->get()->sortBy(function ($prod) use ($orderMap) {
            return $orderMap[$prod->slug] ?? 99;
        })->values();

        return view('master.products.index', compact('products'));
    }
}
