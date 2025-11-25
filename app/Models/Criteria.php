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
     * Relasi ke SupplierAssessment
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(SupplierAssessment::class);
    }

    /**
     * Scope untuk criteria yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
