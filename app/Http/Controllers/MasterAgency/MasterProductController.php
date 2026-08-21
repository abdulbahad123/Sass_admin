<?php

namespace App\Http\Controllers\MasterAgency;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class MasterProductController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->get();

        return view('master.products.index', compact('products'));
    }
}
