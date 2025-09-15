<?php

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


use App\Http\Controllers\AuthController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\AdAccountController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\AdGroupController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\MetricController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {

    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);

    // Tenant Management
    Route::get('/tenants', [TenantController::class, 'index']);
    Route::post('/tenants', [TenantController::class, 'store']);

    // Ad Accounts
    Route::get('/ad-accounts', [AdAccountController::class, 'index']);
    Route::post('/ad-accounts', [AdAccountController::class, 'store']);

    // Campaigns
    Route::get('/campaigns', [CampaignController::class, 'index']);
    Route::post('/campaigns', [CampaignController::class, 'store']);
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show']);
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update']);
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy']);

    // Ad Groups
    Route::get('/ad-groups', [AdGroupController::class, 'index']);
    Route::post('/ad-groups', [AdGroupController::class, 'store']);
    Route::put('/ad-groups/{adGroup}', [AdGroupController::class, 'update']);
    Route::delete('/ad-groups/{adGroup}', [AdGroupController::class, 'destroy']);

    // Ads
    Route::get('/ads', [AdController::class, 'index']);
    Route::post('/ads', [AdController::class, 'store']);
    Route::get('/ads/{ad}', [AdController::class, 'show']);
    Route::put('/ads/{ad}', [AdController::class, 'update']);
    Route::delete('/ads/{ad}', [AdController::class, 'destroy']);

    // Metrics
    Route::get('/campaigns/{campaign}/metrics', [MetricController::class, 'campaignMetrics']);
    Route::get('/ads/{ad}/metrics', [MetricController::class, 'adMetrics']);
});

