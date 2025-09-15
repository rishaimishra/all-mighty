<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function index(Request $request)
    {
        return Ad::whereHas('adGroup.campaign', function($q) use ($request) {
            $q->where('tenant_id', $request->user()->tenant_id);
        })->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ad_group_id' => 'required|exists:ad_groups,id',
            'name'        => 'required|string|max:255',
            'type'        => 'required|string',
            'headline'    => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|url',
            'final_url'   => 'nullable|url'
        ]);

        $ad = Ad::create([
            'ad_group_id' => $data['ad_group_id'],
            'name'        => $data['name'],
            'status'      => 'draft',
            'type'        => $data['type'],
            'headline'    => $data['headline'],
            'description' => $data['description'] ?? null,
            'image_url'   => $data['image_url'] ?? null,
            'final_url'   => $data['final_url'] ?? null
        ]);

        return response()->json($ad, 201);
    }

    public function show(Ad $ad)
    {
        return response()->json($ad);
    }

    public function update(Request $request, Ad $ad)
    {
        $data = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'type'        => 'sometimes|string',
            'headline'    => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|url',
            'final_url'   => 'nullable|url'
        ]);

        $ad->update($data);

        return response()->json($ad);
    }

    public function destroy(Ad $ad)
    {
        $ad->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}

