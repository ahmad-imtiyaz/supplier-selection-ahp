<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criteria extends Model
{
    protected $table = 'criteria';

    /**
     * Mass assignment
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'weight',
        'is_active',
    ];

    /**
     * Cast attributes
     */
    protected $casts = [
        'weight'     => 'decimal:4',
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* =====================================================
     | RELATIONS – AHP COMPARISON
     ===================================================== */

    /**
     * Relasi ke CriteriaComparison sebagai criteria_1
     */
    public function comparisonsAsCriteria1(): HasMany
    {
        return $this->hasMany(CriteriaComparison::class, 'criteria_1_id');
    }

    /**
     * Relasi ke CriteriaComparison sebagai criteria_2
     */
    public function comparisonsAsCriteria2(): HasMany
    {
        return $this->hasMany(CriteriaComparison::class, 'criteria_2_id');
    }

    /**
     * Alias (BACKWARD COMPATIBILITY)
     */
    public function comparisons1(): HasMany
    {
        return $this->comparisonsAsCriteria1();
    }

    /**
     * Alias (BACKWARD COMPATIBILITY)
     */
    public function comparisons2(): HasMany
    {
        return $this->comparisonsAsCriteria2();
    }

    /**
     * Semua comparison yang melibatkan kriteria ini
     */
    public function allComparisons()
    {
        return CriteriaComparison::where('criteria_1_id', $this->id)
            ->orWhere('criteria_2_id', $this->id);
    }

    /**
     * Check jika kriteria memiliki perbandingan AHP
     */
    public function hasComparisons(): bool
    {
        return $this->comparisons1()->exists() || $this->comparisons2()->exists();
    }

    /* =====================================================
     | RELATIONS – ASSESSMENT & TRACK RECORD
     ===================================================== */

    /**
     * Relasi ke SupplierAssessment
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(SupplierAssessment::class, 'criteria_id');
    }

    /**
     * Relasi ke SupplierTrackRecord
     */
    public function trackRecords(): HasMany
    {
        return $this->hasMany(SupplierTrackRecord::class, 'criteria_id');
    }

    /**
     * Track record berdasarkan supplier
     */
    public function trackRecordsForSupplier($supplierId)
    {
        return $this->trackRecords()->where('supplier_id', $supplierId);
    }

    /**
     * Check jika kriteria memiliki penilaian
     */
    public function hasAssessments(): bool
    {
        return $this->assessments()->exists();
    }

    /**
     * Check jika kriteria memiliki track record
     */
    public function hasTrackRecords(): bool
    {
        return $this->trackRecords()->exists();
    }

    /* =====================================================
     | BUSINESS LOGIC
     ===================================================== */

    /**
     * Total dampak penggunaan kriteria
     */
    public function impactCount(): array
    {
        return [
            'assessments' => $this->assessments()->count(),
            'comparisons' => $this->comparisons1()->count() + $this->comparisons2()->count(),
            'track_records' => $this->trackRecords()->count(),
        ];
    }

    /**
     * Validasi apakah kriteria boleh dihapus
     */
    public function canBeDeleted(): array
    {
        if ($this->hasComparisons()) {
            return [
                'can_delete' => false,
                'reason' => 'Kriteria tidak dapat dihapus karena sudah digunakan dalam perbandingan AHP.'
            ];
        }

        if ($this->hasAssessments()) {
            return [
                'can_delete' => false,
                'reason' => 'Kriteria tidak dapat dihapus karena sudah digunakan dalam penilaian supplier.'
            ];
        }

        if ($this->hasTrackRecords()) {
            return [
                'can_delete' => false,
                'reason' => 'Kriteria tidak dapat dihapus karena sudah digunakan dalam track record supplier.'
            ];
        }

        return [
            'can_delete' => true,
            'reason' => null
        ];
    }

    /* =====================================================
     | SCOPES
     ===================================================== */

    /**
     * Scope: kriteria aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
