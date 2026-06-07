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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            
            // Required name string
            $table->string('name');
            
            // Nullable description
            $table->text('description')->nullable();
            
            // Duration in minutes, default 60
            $table->unsignedSmallInteger('default_duration')->default(60);
            
            // Price: 8 digits total, 2 decimal places. Nullable.
            $table->decimal('price', 8, 2)->nullable();
            
            // Enums for specific choices
            $table->enum('billing_unit', ['session', 'hour'])->default('session');
            $table->enum('modality', ['online', 'in_person', 'either'])->default('online');
            
            // Hex color code (e.g. #445566), limit to 7 characters
            $table->string('color', 7)->default('#445566');
            
            // Booleans for active state and recurring default
            $table->boolean('is_active')->default(true);
            $table->boolean('is_recurring_default')->default(false);
            
            $table->timestamps(); // Automatically creates 'created_at' and 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
