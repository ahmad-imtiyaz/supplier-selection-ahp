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
        Schema::create('supplier_track_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained('criteria')->onDelete('cascade');
            $table->year('year'); // Tahun (2024, 2025, dst)
            $table->tinyInteger('month'); // Bulan (1-12)
            $table->text('description')->nullable(); // Deskripsi track record
            $table->decimal('recommended_score', 5, 2)->nullable(); // Skor rekomendasi (0-100)
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();

            // Unique constraint: satu supplier tidak bisa punya 2 record untuk kriteria yang sama di bulan yang sama
            $table->unique(['supplier_id', 'criteria_id', 'year', 'month'], 'unique_track_record');

            // Index untuk query cepat
            $table->index(['supplier_id', 'year', 'month']);
            $table->index(['criteria_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_track_records');
    }
};
