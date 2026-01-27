<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierTrackRecord extends Model
{
    protected $fillable = [
        'supplier_id',
        'criteria_id',
        'year',
        'month',
        'description',
        'recommended_score',
        'notes',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'recommended_score' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke Supplier
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relasi ke Criteria
     */
    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class);
    }

    /**
     * Get month name in Indonesian
     */
    public function getMonthNameAttribute(): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $months[$this->month] ?? 'Unknown';
    }

    /**
     * Get formatted period (Bulan Tahun)
     */
    public function getPeriodAttribute(): string
    {
        return $this->month_name . ' ' . $this->year;
    }

    /**
     * Get short period (MMM YYYY)
     */
    public function getShortPeriodAttribute(): string
    {
        $shortMonths = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        return $shortMonths[$this->month] . ' ' . $this->year;
    }

    /**
     * Check if record has content
     */
    public function hasContent(): bool
    {
        return !empty($this->description) || !is_null($this->recommended_score);
    }

    /**
     * Get score badge color
     */
    public function getScoreBadgeColorAttribute(): string
    {
        if (is_null($this->recommended_score)) {
            return 'gray';
        }

        $score = (float) $this->recommended_score;

        if ($score >= 80) return 'green';
        if ($score >= 60) return 'blue';
        if ($score >= 40) return 'yellow';
        return 'red';
    }

    /**
     * Scope: Filter by supplier
     */
    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    /**
     * Scope: Filter by criteria
     */
    public function scopeByCriteria($query, $criteriaId)
    {
        return $query->where('criteria_id', $criteriaId);
    }

    /**
     * Scope: Filter by year
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope: Filter by month
     */
    public function scopeByMonth($query, $month)
    {
        return $query->where('month', $month);
    }

    /**
     * Scope: Filter by period
     */
    public function scopeByPeriod($query, $year, $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    /**
     * Scope: Get records with content
     */
    public function scopeHasContent($query)
    {
        return $query->where(function ($q) {
            $q->whereNotNull('description')
              ->orWhereNotNull('recommended_score');
        });
    }

    /**
     * Scope: Order by period (latest first)
     */
    public function scopeLatestPeriod($query)
    {
        return $query->orderBy('year', 'desc')->orderBy('month', 'desc');
    }

    /**
     * Scope: Order by period (oldest first)
     */
    public function scopeOldestPeriod($query)
    {
        return $query->orderBy('year', 'asc')->orderBy('month', 'asc');
    }
}
