<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // C1, C2, C3, dst
            $table->string('name'); // Nama kriteria
            $table->text('description')->nullable(); // Deskripsi kriteria
            $table->decimal('weight', 5, 4)->default(0); // Bobot hasil AHP (0.0000 - 1.0000)
            $table->boolean('is_active')->default(true); // Status aktif/nonaktif
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('criteria');
    }
};
