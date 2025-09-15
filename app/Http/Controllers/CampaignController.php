<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        return Campaign::where('tenant_id', $request->user()->tenant_id)->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ad_account_id'     => 'required|exists:ad_accounts,id',
            'name'              => 'required|string|max:255',
            'daily_budget'      => 'required|numeric',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after:start_date',
            'targeting_criteria'=> 'nullable|json'
        ]);

        $campaign = Campaign::create([
            'tenant_id'         => $request->user()->tenant_id,
            'ad_account_id'     => $data['ad_account_id'],
            'name'              => $data['name'],
            'status'            => 'draft',
            'daily_budget'      => $data['daily_budget'],
            'start_date'        => $data['start_date'],
            'end_date'          => $data['end_date'],
            'targeting_criteria'=> $data['targeting_criteria'] ?? null
        ]);

        return response()->json($campaign, 201);
    }

    public function show(Campaign $campaign)
    {
        return response()->json($campaign);
    }

    public function update(Request $request, Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        $data = $request->validate([
            'name'              => 'sometimes|string|max:255',
            'daily_budget'      => 'sometimes|numeric',
            'start_date'        => 'sometimes|date',
            'end_date'          => 'sometimes|date|after:start_date',
            'targeting_criteria'=> 'nullable|json'
        ]);

        $campaign->update($data);

        return response()->json($campaign);
    }

    public function destroy(Campaign $campaign)
    {
        $this->authorize('delete', $campaign);

        $campaign->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}

