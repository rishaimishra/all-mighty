<?php

namespace App\Http\Controllers;

use App\Models\AdAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Exception;

class AdAccountController extends Controller
{
    public function index(Request $request)
    {
        return AdAccount::where('tenant_id', $request->user()->tenants->first()->id)->get();
    }

    public function store(Request $request)
    {
        // dd($request->user()->tenants->first()->id);
        try {
            $data = $request->validate([
                'platform_type' => 'required|string',
                'platform_account_id' => 'required|string',
                'credentials_encrypted' => 'required|string'
            ]);

            $adAccount = AdAccount::create([
                'tenant_id' => $request->user()->tenants->first()->id,
                'platform_type' => $data['platform_type'],
                'platform_account_id' => $data['platform_account_id'],
                'credentials_encrypted' => encrypt($data['credentials_encrypted']),
                'status' => 'active'
            ]);

            return response()->json($adAccount, 201);
        } catch (QueryException $e) {
            // Log a database-related error
            Log::error('Ad account creation failed due to a database error: ' . $e->getMessage(), [
                'tenant_id' => $request->user()->tenants->first()->id,
                'platform_type' => $request->platform_type,
                'platform_account_id' => $request->platform_account_id,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'A database error occurred while creating the ad account. Please try again later.'
            ], 500);

        } catch (Exception $e) {
            // Log a general, unexpected error
            Log::error('An unexpected error occurred during ad account creation: ' . $e->getMessage(), [
                'tenant_id' => $request->user()->tenant_id,
                'platform_type' => $request->platform_type,
                'platform_account_id' => $request->platform_account_id,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }
}

