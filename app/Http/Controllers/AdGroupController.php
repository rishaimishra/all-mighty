<?php

namespace App\Http\Controllers;

use App\Models\AdGroup;
use Illuminate\Http\Request;

class AdGroupController extends Controller
{
    public function index(Request $request)
    {
        return AdGroup::whereHas('campaign', function($q) use ($request) {
            $q->where('tenant_id', $request->user()->tenant_id);
        })->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'name'        => 'required|string|max:255',
            'default_bid' => 'required|numeric'
        ]);

        $adGroup = AdGroup::create([
            'campaign_id' => $data['campaign_id'],
            'name'        => $data['name'],
            'status'      => 'active',
            'default_bid' => $data['default_bid']
        ]);

        return response()->json($adGroup, 201);
    }

    public function update(Request $request, AdGroup $adGroup)
    {
        $data = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'default_bid' => 'sometimes|numeric'
        ]);

        $adGroup->update($data);

        return response()->json($adGroup);
    }

    public function destroy(AdGroup $adGroup)
    {
        $adGroup->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}

