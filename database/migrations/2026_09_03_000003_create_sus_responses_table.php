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
        Schema::create('sus_responses', function (Blueprint $table) {
            $table->id();
            $table->string('respondent_name');
            $table->string('respondent_role')->default('Operator Lapangan'); // Operator Lapangan, Perwira Jaga, Teknisi IT
            $table->integer('q1'); // Saya berpikir akan sering menggunakan sistem ini
            $table->integer('q2'); // Saya merasa sistem ini terlalu rumit padahal tidak perlu
            $table->integer('q3'); // Saya merasa sistem ini mudah digunakan
            $table->integer('q4'); // Saya membutuhkan bantuan orang teknis untuk menggunakan sistem ini
            $table->integer('q5'); // Berbagai fungsi dalam sistem ini terintegrasi dengan baik
            $table->integer('q6'); // Saya merasa ada banyak inkonsistensi dalam sistem ini
            $table->integer('q7'); // Sebagian besar orang akan cepat belajar menggunakan sistem ini
            $table->integer('q8'); // Saya merasa sistem ini sangat janggal / membingungkan saat digunakan
            $table->integer('q9'); // Saya merasa sangat percaya diri saat menggunakan sistem ini
            $table->integer('q10'); // Saya harus belajar banyak hal sebelum dapat mengoperasikan sistem ini
            $table->float('final_score', 5, 2); // 0.00 - 100.00
            $table->string('grade'); // F, D, C, B, A
            $table->string('adjective_rating'); // Worst Imaginable, Poor, OK, Good, Excellent, Best Imaginable
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sus_responses');
    }
};
