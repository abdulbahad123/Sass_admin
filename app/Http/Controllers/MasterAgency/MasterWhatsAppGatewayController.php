<?php

namespace App\Http\Controllers\MasterAgency;

use App\Http\Controllers\Controller;
use App\Http\Controllers\WhiteLabel\WhiteLabelWhatsAppGatewayController;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterWhatsAppGatewayController extends Controller
{
    public function index(Request $request)
    {
        $controller = new WhiteLabelWhatsAppGatewayController();
        return $controller->index($request);
    }

    public function update(Request $request)
    {
        $controller = new WhiteLabelWhatsAppGatewayController();
        return $controller->update($request);
    }

    public function testSend(Request $request)
    {
        $controller = new WhiteLabelWhatsAppGatewayController();
        return $controller->testSend($request);
    }
}
