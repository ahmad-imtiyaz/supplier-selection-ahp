<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CriteriaComparison extends Model
{
    // Table sudah otomatis: criteria_comparisons
    protected $fillable = [
        'criteria_1_id',
        'criteria_2_id',
        'value',
        'note', // pakai ini kalau kolom 'note' memang ada di tabel
    ];

    protected $casts = [
        'value' => 'decimal:2',
    ];

    /**
     * Relasi ke Kriteria pertama
     */
    public function criteria1(): BelongsTo
    {
        return $this->belongsTo(Criteria::class, 'criteria_1_id');
    }

    /**
     * Relasi ke Kriteria kedua
     */
    public function criteria2(): BelongsTo
    {
        return $this->belongsTo(Criteria::class, 'criteria_2_id');
    }

    /**
     * Mendapatkan nilai kebalikan (1/value)
     */
    public function getReciprocalValueAttribute(): float
    {
        return $this->value > 0 ? (1 / $this->value) : 0;
    }

    /**
     * Scope: mengambil perbandingan spesifik dua kriteria
     */
    public function scopeForCriteria($query, $criteria1Id, $criteria2Id)
    {
        return $query->where('criteria_1_id', $criteria1Id)
            ->where('criteria_2_id', $criteria2Id);
    }

    /**
     * Scope: mengambil semua perbandingan yang melibatkan satu kriteria
     */
    public function scopeInvolvingCriteria($query, $criteriaId)
    {
        return $query->where('criteria_1_id', $criteriaId)
            ->orWhere('criteria_2_id', $criteriaId);
    }

    /**
     * Validasi nilai perbandingan AHP
     */
    public function isValid(): bool
    {
        return $this->value >= 0.111 && $this->value <= 9;
    }

    /**
     * Menjelaskan arti nilai AHP secara verbal
     */
    public function getDescriptionAttribute(): string
    {
        if ($this->value == 1) {
            return 'Sama penting';
        } elseif ($this->value >= 7) {
            return 'Sangat lebih penting';
        } elseif ($this->value >= 5) {
            return 'Jelas lebih penting';
        } elseif ($this->value >= 3) {
            return 'Sedikit lebih penting';
        } else {
            return 'Nilai antara';
        }
    }
}
