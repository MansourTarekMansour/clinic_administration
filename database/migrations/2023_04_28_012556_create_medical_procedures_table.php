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
        Schema::create('medical_procedures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('treatment_plan_id');
            $table->foreign('treatment_plan_id')->references('id')->on('treatment_plans')->onDelete('cascade');
            
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_procedures');
    }
};
