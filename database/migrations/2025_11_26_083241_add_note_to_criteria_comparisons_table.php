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
        Schema::table('criteria_comparisons', function (Blueprint $table) {

            // ✅ Tambah kolom catatan perbandingan
            $table->text('note')->nullable()->after('value');

            // ✅ Tambah unique constraint untuk mencegah duplikasi perbandingan
            $table->unique(
                ['criteria_1_id', 'criteria_2_id'],
                'unique_criteria_pair'
            );

            // ✅ Index untuk optimasi query
            $table->index('criteria_1_id', 'idx_criteria_1');
            $table->index('criteria_2_id', 'idx_criteria_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('criteria_comparisons', function (Blueprint $table) {

            // Hapus kolom note
            $table->dropColumn('note');

            // Hapus unique constraint
            $table->dropUnique('unique_criteria_pair');

            // Hapus index
            $table->dropIndex('idx_criteria_1');
            $table->dropIndex('idx_criteria_2');
        });
    }
};
