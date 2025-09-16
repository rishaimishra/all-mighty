<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Events\CampaignCreated;


class CampaignController extends Controller
{
    public function index(Request $request)
    {
        return Campaign::where('tenant_id', $request->user()->tenants->first()->id)->get();
    }



    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'ad_account_id' => 'required|exists:ad_accounts,id',
                'name' => 'required|string|max:255',
                'daily_budget' => 'required|numeric',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'targeting_criteria' => 'nullable|json'
            ]);

            $campaign = Campaign::create([
                'tenant_id' => $request->user()->tenants->first()->id,
                'ad_account_id' => $data['ad_account_id'],
                'name' => $data['name'],
                'status' => 'draft',
                'daily_budget' => $data['daily_budget'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'targeting_criteria' => $data['targeting_criteria'] ?? null
            ]);

            // Event::dispatch(new CampaignCreated($campaign));


            return response()->json($campaign, 201);
        } catch (QueryException $e) {
            // Log a database-specific error
            Log::error('Campaign creation failed due to a database error: ' . $e->getMessage(), [
                'tenant_id' => $request->user()->tenants->first()->id,
                'ad_account_id' => $request->ad_account_id,
                'campaign_name' => $request->name,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'A database error occurred while creating the campaign. Please try again later.'
            ], 500);

        } catch (Exception $e) {
            // Log a general, unexpected error
            Log::error('An unexpected error occurred during campaign creation: ' . $e->getMessage(), [
                'tenant_id' => $request->user()->tenants->first()->id,
                'ad_account_id' => $request->ad_account_id,
                'campaign_name' => $request->name,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }

    public function show(Campaign $campaign)
    {
        return response()->json($campaign);
    }

    public function update(Request $request, Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'daily_budget' => 'sometimes|numeric',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'targeting_criteria' => 'nullable|json'
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

