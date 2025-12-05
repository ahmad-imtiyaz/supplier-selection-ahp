<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CriteriaComparison extends Model
{
    protected $fillable = [
        'criteria_1_id',
        'criteria_2_id',
        'value',
        'note',
    ];

    protected $casts = [
        'value' => 'decimal:4', // ✅ Ubah precision ke 4 untuk lebih akurat
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
     * ✅ NEW: Get display value for specific criteria direction
     * Menampilkan nilai yang benar tergantung arah perbandingan
     */
    public function getValueForCriteria($criteriaId1, $criteriaId2)
    {
        // Jika urutan sesuai dengan database
        if ($this->criteria_1_id == $criteriaId1 && $this->criteria_2_id == $criteriaId2) {
            return [
                'value' => (float) $this->value,
                'display' => number_format($this->value, 2),
                'is_reciprocal' => false
            ];
        }
        // Jika urutan terbalik (reciprocal)
        elseif ($this->criteria_1_id == $criteriaId2 && $this->criteria_2_id == $criteriaId1) {
            $reciprocal = 1 / $this->value;
            return [
                'value' => $reciprocal,
                'display' => '1/' . number_format($this->value, 2),
                'is_reciprocal' => true
            ];
        }

        return null;
    }

    /**
     * Scope: mengambil perbandingan spesifik dua kriteria (dari kedua arah)
     * ✅ UPDATED: Support bidirectional search
     */
    public function scopeForCriteria($query, $criteria1Id, $criteria2Id)
    {
        return $query->where(function ($q) use ($criteria1Id, $criteria2Id) {
            $q->where('criteria_1_id', $criteria1Id)
                ->where('criteria_2_id', $criteria2Id);
        })->orWhere(function ($q) use ($criteria1Id, $criteria2Id) {
            $q->where('criteria_1_id', $criteria2Id)
                ->where('criteria_2_id', $criteria1Id);
        });
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
     * ✅ NEW: Scope untuk kriteria aktif saja
     */
    public function scopeOnlyActiveCriteria($query)
    {
        return $query->whereHas('criteria1', function ($q) {
            $q->where('is_active', true);
        })->whereHas('criteria2', function ($q) {
            $q->where('is_active', true);
        });
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

    /**
     * ✅ NEW: Check if both criteria are active
     */
    public function bothCriteriaActive(): bool
    {
        return $this->criteria1->is_active && $this->criteria2->is_active;
    }

    /**
     * ✅ NEW: Get criteria pair as string
     */
    public function getCriteriaPairAttribute(): string
    {
        return "{$this->criteria1->code} vs {$this->criteria2->code}";
    }
}
