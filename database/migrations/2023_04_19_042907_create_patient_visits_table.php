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
        Schema::create('patient_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('treatment_plan');
            $table->string('type');
            $table->integer('units_number');
            $table->decimal('cost', 8, 2);
            $table->decimal('discount', 8, 2);
            $table->decimal('total', 8, 2);
            $table->decimal('paid', 8, 2);
            $table->decimal('remaining', 8, 2);
            $table->text('note')->nullable();
            $table->date('date');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_visits');
    }
};
