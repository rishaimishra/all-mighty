<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use App\Models\Campaign;
use App\Models\Ad;
use Illuminate\Http\Request;

class MetricController extends Controller
{
    public function campaignMetrics(Campaign $campaign)
    {
        return $campaign->metrics()->get();
    }

    public function adMetrics(Ads $ad)
    {
        return $ad->metrics()->get();
    }
}

