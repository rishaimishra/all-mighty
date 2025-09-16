<?php

namespace App\Providers;

use App\Jobs\FetchAdMetrics;
use App\Jobs\FetchCampaignMetrics;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Schedule $schedule): void
    {
        // Register your scheduled jobs here
        $schedule->job(new FetchCampaignMetrics)->hourly();
        $schedule->job(new FetchAdMetrics)->hourly();
    }
}
