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
        Schema::create('ad_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->comment('FK to campaigns.');
            $table->string('name')->comment('Group Name.');
            $table->enum('status', ['active', 'inactive']);
            $table->decimal('default_bid', 12, 2)->default(0)->comment('Bid amount.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_groups');
    }
};
