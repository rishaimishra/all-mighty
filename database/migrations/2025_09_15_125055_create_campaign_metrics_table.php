<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campaign_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->comment('FK to campaign.');
            $table->date('date')->comment('Report Date.');
            $table->string('impressions')->comment('Impression count.');
            $table->string('clicks')->comment('Click count.');
            $table->string('conversions')->comment('Conversions count.');
            $table->decimal('spend', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_metrics');
    }
};
