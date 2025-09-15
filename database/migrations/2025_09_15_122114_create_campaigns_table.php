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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->comment('FK to tenants.');
            $table->foreignId('ad_account_id')->comment('FK to ad_accounts.');
            $table->string('name')->comment('Campaign Name.');
            $table->enum('status', ['draft', 'running']);
            $table->decimal('daily_budget', 12, 2)->default(0);
            $table->date('start_date')->comment('Campaign Start Date.');
            $table->date('end_date')->comment('Campaign End Date.');
            $table->json('targeting_criteria');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
