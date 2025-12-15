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

            $table->foreignId('criteria_1_id')
                ->constrained('criteria')
                ->onDelete('cascade');

            $table->foreignId('criteria_2_id')
                ->constrained('criteria')
                ->onDelete('cascade');

            // ✅ FIXED: Precision 8,4 untuk support 1/9 = 0.1111
            $table->decimal('value', 8, 4);
            $table->text('note')->nullable();
            $table->timestamps();

            // ✅ HAPUS UNIQUE CONSTRAINT
            // Kita tidak perlu unique constraint karena:
            // - User bisa input C1 vs C2 = 4
            // - Lalu update dengan C2 vs C1 = 5 (akan replace yang lama)
            // Validasi duplikasi akan dilakukan di aplikasi level
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('criteria_comparisons');
    }
};
