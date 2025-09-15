<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        return Tenant::whereHas('users', function($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'domain' => 'required|string|unique:tenants,domain'
        ]);

        $tenant = Tenant::create([
            'name'   => $data['name'],
            'domain' => $data['domain'],
            'status' => 'active'
        ]);

        // Attach current user as admin
        $tenant->users()->attach($request->user()->id, [
            'role'        => 'admin',
            'permissions' => json_encode([])
        ]);

        return response()->json($tenant, 201);
    }
}
