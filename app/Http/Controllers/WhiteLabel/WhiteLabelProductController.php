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
        $products = Product::where('is_active', true)->get();

        return view('whitelabel.products.index', compact('user', 'agency', 'products'));
    }
}
