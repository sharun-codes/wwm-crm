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
        Schema::create('renewal_deal_details', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('deal_id')->unique()->constrained()->cascadeOnDelete();
        
            $table->date('previous_expiry_date');
            $table->integer('renewal_period_months');
            $table->unsignedTinyInteger('churn_risk')->nullable(); // 0–100
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renewal_deal_details');
    }
};
