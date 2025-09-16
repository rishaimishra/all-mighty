<?php

namespace App\Jobs;

use App\Models\CampaignMetric;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchCampaignMetrics implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        CampaignMetric::updateOrCreate(
            ['campaign_id' => $campaign->id, 'date' => $date],
            [
                'impressions' => $impressions,
                'clicks' => $clicks,
                'conversions' => $conversions,
                'spend' => $spend
            ]
        );

    }
}
