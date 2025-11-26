<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criteria extends Model
{
    protected $table = 'criteria';

    protected $fillable = [
        'code',
        'name',
        'description',
        'weight',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:4',
        'is_active' => 'boolean',
    ];

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
     * Alias untuk comparisonsAsCriteria1 (untuk backward compatibility)
     */
    public function comparisons1(): HasMany
    {
        return $this->comparisonsAsCriteria1();
    }

    /**
     * Alias untuk comparisonsAsCriteria2 (untuk backward compatibility)
     */
    public function comparisons2(): HasMany
    {
        return $this->comparisonsAsCriteria2();
    }

    /**
     * Get all comparisons yang melibatkan kriteria ini
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allComparisons()
    {
        return CriteriaComparison::where('criteria_1_id', $this->id)
            ->orWhere('criteria_2_id', $this->id);
    }

    /**
     * Check if criteria has any comparisons
     * 
     * @return bool
     */
    public function hasComparisons(): bool
    {
        return $this->comparisonsAsCriteria1()->exists()
            || $this->comparisonsAsCriteria2()->exists();
    }

    /**
     * Relasi ke SupplierAssessment
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(SupplierAssessment::class, 'criteria_id');
    }

    /**
     * Check if criteria has any assessments
     * 
     * @return bool
     */
    public function hasAssessments(): bool
    {
        return $this->assessments()->exists();
    }

    /**
     * Scope untuk criteria yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if criteria can be deleted
     * 
     * @return array ['can_delete' => bool, 'reason' => string|null]
     */
    public function canBeDeleted(): array
    {
        if ($this->hasComparisons()) {
            return [
                'can_delete' => false,
                'reason' => 'Kriteria tidak dapat dihapus karena sudah digunakan dalam perbandingan AHP. Hapus perbandingan terlebih dahulu.'
            ];
        }

        if ($this->hasAssessments()) {
            return [
                'can_delete' => false,
                'reason' => 'Kriteria tidak dapat dihapus karena sudah digunakan dalam penilaian supplier. Hapus penilaian terlebih dahulu.'
            ];
        }

        return [
            'can_delete' => true,
            'reason' => null
        ];
    }
}
