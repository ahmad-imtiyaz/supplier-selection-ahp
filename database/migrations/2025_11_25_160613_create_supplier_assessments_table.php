<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained('criteria')->onDelete('cascade');
            $table->decimal('score', 8, 2); // Nilai penilaian (0 - 100)
            $table->text('notes')->nullable(); // Catatan penilaian
            $table->timestamps();

            // Pastikan satu supplier hanya punya satu nilai per kriteria
            $table->unique(['supplier_id', 'criteria_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_assessments');
    }
};
