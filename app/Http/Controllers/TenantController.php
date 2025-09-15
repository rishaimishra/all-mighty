<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Exception;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        return Tenant::get();
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'domain' => 'required|string|unique:tenants,domain'
            ]);

            $tenant = Tenant::create([
                'name' => $data['name'],
                'domain' => $data['domain'],
                'status' => 'active'
            ]);

            // Attach current user as admin
            $tenant->users()->attach($request->user()->id, [
                'role' => 'admin',
                'permissions' => json_encode([])
            ]);

            return response()->json($tenant, 201);
        } catch (QueryException $e) {
            // This catches database-specific errors, like a failed insert
            Log::error('Tenant creation failed due to database error: ' . $e->getMessage(), [
                'tenant_name' => $request->name,
                'tenant_domain' => $request->domain,
                'user_id' => $request->user()->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'A database error occurred while creating the tenant. Please try again later.'
            ], 500);

        } catch (Exception $e) {
            // This is a general catch-all for any other unexpected errors
            Log::error('Tenant creation failed unexpectedly: ' . $e->getMessage(), [
                'tenant_name' => $request->name,
                'tenant_domain' => $request->domain,
                'user_id' => $request->user()->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'An unexpected error occurred during tenant creation. Please try again later.'
            ], 500);
        }
    }
}
