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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_group_id')->comment('FK to ad_groups.');
            $table->string('name')->comment('Ad Name.');
            $table->enum('status', ['draft', 'running']);
            $table->string('type')->comment('Image or Video type.');
            $table->string('headline')->comment('Title.');
            $table->text('description')->nullable();
            $table->string('image_url')->comment('File from S3.');
            $table->string('final_url')->comment('Target link.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
