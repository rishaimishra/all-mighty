<?php

namespace App\Http\Controllers;

use App\Models\AdAccount;
use Illuminate\Http\Request;

class AdAccountController extends Controller
{
    public function index(Request $request)
    {
        return AdAccount::where('tenant_id', $request->user()->tenant_id)->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'platform_type'        => 'required|string',
            'platform_account_id'  => 'required|string',
            'credentials_encrypted'=> 'required|string'
        ]);

        $adAccount = AdAccount::create([
            'tenant_id'            => $request->user()->tenant_id,
            'platform_type'        => $data['platform_type'],
            'platform_account_id'  => $data['platform_account_id'],
            'credentials_encrypted'=> encrypt($data['credentials_encrypted']),
            'status'               => 'active'
        ]);

        return response()->json($adAccount, 201);
    }
}

