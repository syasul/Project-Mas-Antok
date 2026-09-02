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
        Schema::create('verification_logs', function (Blueprint $table) {
            $table->id();
            $table->string('subject_name');
            $table->string('nim')->nullable();
            $table->string('category')->default('Taruna'); // Taruna, Dosen, Staf Militer, Tamu
            $table->string('photo_url')->nullable();
            $table->enum('status', ['verified', 'failed', 'pending'])->default('pending');
            $table->float('confidence_score', 5, 2); // e.g. 98.50
            $table->string('device_id'); // e.g. CAM_GATE_UTAMA_01
            $table->string('location')->default('Gate Utama Poltekad');
            $table->float('latency_ms', 8, 2)->default(0); // WebSocket / Ingestion end-to-end latency
            $table->string('failure_reason')->nullable(); // e.g. Face mismatch, Spoofing attempt
            $table->json('metadata')->nullable(); // bounding_box, eye_distance, illumination_lux
            $table->boolean('manual_override')->default(false);
            $table->string('overridden_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_logs');
    }
};
