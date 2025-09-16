<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Exception;

class AdController extends Controller
{
    public function index(Request $request)
    {
        return Ads::whereHas('adGroup.campaign', function ($q) use ($request) {
            $q->where('tenant_id', $request->user()->tenant_id);
        })->get();
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'ad_group_id' => 'required|exists:ad_groups,id',
                'name' => 'required|string|max:255',
                'type' => 'required|string',
                'headline' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image_url' => 'nullable|url',
                'final_url' => 'nullable|url'
            ]);

            $ad = Ads::create([
                'ad_group_id' => $data['ad_group_id'],
                'name' => $data['name'],
                'status' => 'draft',
                'type' => $data['type'],
                'headline' => $data['headline'],
                'description' => $data['description'] ?? null,
                'image_url' => $data['image_url'] ?? null,
                'final_url' => $data['final_url'] ?? null
            ]);

            return response()->json($ad, 201);
        } catch (QueryException $e) {
            // Log a database-specific error
            Log::error('Ad creation failed due to a database error: ' . $e->getMessage(), [
                'ad_group_id' => $request->ad_group_id,
                'ad_name' => $request->name,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'A database error occurred while creating the ad. Please try again later.'
            ], 500);

        } catch (Exception $e) {
            // Log a general, unexpected error
            Log::error('An unexpected error occurred during ad creation: ' . $e->getMessage(), [
                'ad_group_id' => $request->ad_group_id,
                'ad_name' => $request->name,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }

    public function show(Ads $ad)
    {
        return response()->json($ad);
    }

    public function update(Request $request, Ads $ad)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|string',
            'headline' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'final_url' => 'nullable|url'
        ]);

        $ad->update($data);

        return response()->json($ad);
    }

    public function destroy(Ads $ad)
    {
        $ad->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}

