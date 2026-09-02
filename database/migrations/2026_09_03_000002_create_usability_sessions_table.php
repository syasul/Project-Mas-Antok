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
        Schema::create('usability_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('operator_name');
            $table->string('task_code'); // T1, T2, T3, T4
            $table->string('task_name'); // e.g. "Identifikasi Log Verifikasi Gagal"
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->float('completion_time_sec', 8, 2)->default(0);
            $table->integer('error_count')->default(0); // misclick count / wrong attempts
            $table->integer('clicks_count')->default(0);
            $table->enum('status', ['in_progress', 'completed', 'abandoned'])->default('in_progress');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usability_sessions');
    }
};
