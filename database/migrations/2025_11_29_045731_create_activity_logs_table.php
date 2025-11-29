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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // User yang melakukan aksi
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Jenis aksi (create, update, delete, calculate, reset)
            $table->string('action', 50);

            // Model/Module yang diubah (Supplier, Criteria, CriteriaComparison, SupplierAssessment)
            $table->string('model', 100);

            // ID dari record yang diubah (bisa null untuk bulk action seperti reset)
            $table->string('model_id', 50)->nullable();

            // Deskripsi yang human-readable
            $table->text('description');

            // Data sebelum perubahan (JSON)
            $table->json('old_values')->nullable();

            // Data setelah perubahan (JSON)
            $table->json('new_values')->nullable();

            // Informasi tambahan untuk tracking
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            // Index untuk performa query
            $table->index(['user_id', 'created_at']);
            $table->index('model');
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
