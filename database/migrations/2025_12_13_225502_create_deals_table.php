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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pipeline_id')->constrained();
            $table->foreignId('pipeline_stage_id')->constrained('pipeline_stages');
        
            $table->decimal('value', 12, 2)->nullable();
            $table->foreignId('owner_id')->nullable()->constrained('users');
        
            $table->date('expected_close_date')->nullable();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
