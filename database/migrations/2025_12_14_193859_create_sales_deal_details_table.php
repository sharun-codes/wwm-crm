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
        Schema::create('sales_deal_details', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('deal_id')->unique()->constrained()->cascadeOnDelete();
        
            $table->string('campaign_type');
            $table->string('platform'); // Instagram, Google, etc.
            $table->integer('duration_days')->nullable();
            $table->integer('expected_reach')->nullable();
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_deal_details');
    }
};
