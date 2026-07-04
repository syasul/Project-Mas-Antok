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
        Schema::create('decision_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('security_event_id')->nullable()->constrained('security_events')->onDelete('cascade');
            $table->json('rules_applied');
            $table->json('action_taken');
            $table->text('decision_rationale');
            $table->boolean('is_successful')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decision_logs');
    }
};
