<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();

            // Ownership & Classification
            $table->foreignId('gym_id')->constrained('gyms')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();

            // Core Details
            $table->string('name');
            $table->string('brand')->nullable(); // e.g., Life Fitness, Rogue
            $table->string('model_number')->nullable();
            $table->text('usage_notes')->nullable();

            // Identification
            $table->string('manufacturer_serial_no', 100)->unique();
            $table->string('asset_code', 100)->nullable();

            // Financials & Lifecycle
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_expiration')->nullable();

            // Maintenance Tracking
            $table->enum('status', ['active', 'under_maintenance', 'faulty', 'decommissioned'])->default('active');
            $table->date('last_serviced_at')->nullable();
            $table->date('next_service_due_at')->nullable();
            $table->integer('service_interval_days')->default(180); // Default 6 months

            // Safety & Location
            $table->string('floor_location')->nullable(); // e.g., "Zone A - Cardio"
            $table->boolean('is_safety_hazard')->default(false);

            $table->timestamps();
            $table->softDeletes(); // CRITICAL for legal/maintenance history

            // Indexes & Uniqueness
            $table->unique(['gym_id', 'asset_code']);
            $table->index('status');
            $table->index('next_service_due_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
