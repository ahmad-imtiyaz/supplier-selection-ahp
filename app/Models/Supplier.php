<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    /**
     * Mass assignment
     */
    protected $fillable = [
        'code',
        'name',
        'address',
        'phone',
        'email',
        'contact_person',
        'description',
        'is_active',
    ];

    /**
     * Cast attributes
     */
    protected $casts = [
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* =====================================================
     | RELATIONS
     ===================================================== */

    /**
     * Relasi ke SupplierAssessment
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(SupplierAssessment::class);
    }

    /**
     * Relasi ke SupplierTrackRecord
     */
    public function trackRecords(): HasMany
    {
        return $this->hasMany(SupplierTrackRecord::class);
    }

    /* =====================================================
     | ASSESSMENT LOGIC (DARI CODE LAMA)
     ===================================================== */

    /**
     * Check jika supplier memiliki penilaian
     */
    public function hasAssessments(): bool
    {
        return $this->assessments()->exists();
    }

    /**
     * Get jumlah penilaian
     */
    public function assessmentsCount(): int
    {
        return $this->assessments()->count();
    }

    /**
     * Scope: supplier yang punya penilaian
     */
    public function scopeWithAssessments($query)
    {
        return $query->whereHas('assessments');
    }

    /* =====================================================
     | TRACK RECORD LOGIC (CODE BARU)
     ===================================================== */

    /**
     * Track record berdasarkan kriteria
     */
    public function trackRecordsForCriteria($criteriaId)
    {
        return $this->trackRecords()->where('criteria_id', $criteriaId);
    }

    /**
     * Track record berdasarkan tahun
     */
    public function trackRecordsForYear($year)
    {
        return $this->trackRecords()->where('year', $year);
    }

    /**
     * Check jika supplier memiliki track record
     */
    public function hasTrackRecords(): bool
    {
        return $this->trackRecords()->exists();
    }

    /**
     * Persentase kelengkapan track record per tahun
     */
    public function getTrackRecordCompletion($year): array
    {
        $activeCriteria = Criteria::where('is_active', true)->count();
        $totalExpected  = $activeCriteria * 12; // 12 bulan

        if ($totalExpected === 0) {
            return [
                'total'      => 0,
                'completed'  => 0,
                'percentage' => 0,
            ];
        }

        $completed = $this->trackRecords()
            ->where('year', $year)
            ->where(function ($query) {
                $query->whereNotNull('description')
                      ->orWhereNotNull('recommended_score');
            })
            ->count();

        return [
            'total'      => $totalExpected,
            'completed'  => $completed,
            'percentage' => round(($completed / $totalExpected) * 100, 2),
        ];
    }

    /* =====================================================
     | SCOPES
     ===================================================== */

    /**
     * Scope: supplier aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
