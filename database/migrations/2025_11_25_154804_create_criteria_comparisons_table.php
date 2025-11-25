<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('criteria_comparisons', function (Blueprint $table) {
            $table->id();

            // PERBAIKAN: Gunakan 'criteria' bukan 'criterias'
            $table->foreignId('criteria_1_id')
                ->constrained('criteria')  // ← Ganti dari 'criterias' ke 'criteria'
                ->onDelete('cascade');

            $table->foreignId('criteria_2_id')
                ->constrained('criteria')  // ← Ganti dari 'criterias' ke 'criteria'
                ->onDelete('cascade');

            $table->decimal('value', 5, 2); // Nilai perbandingan (0.11 - 9.00)
            $table->timestamps();

            // Pastikan tidak ada duplikasi perbandingan
            $table->unique(['criteria_1_id', 'criteria_2_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('criteria_comparisons');
    }
};
