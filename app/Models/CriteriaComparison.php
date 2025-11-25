<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CriteriaComparison extends Model
{
    // Tidak perlu define $table karena 'criteria_comparisons' sudah sesuai default Laravel

    protected $fillable = [
        'criteria_1_id',
        'criteria_2_id',
        'value',
    ];

    protected $casts = [
        'value' => 'decimal:2',
    ];

    /**
     * Relasi ke Criteria pertama
     */
    public function criteria1(): BelongsTo
    {
        return $this->belongsTo(Criteria::class, 'criteria_1_id');
    }

    /**
     * Relasi ke Criteria kedua
     */
    public function criteria2(): BelongsTo
    {
        return $this->belongsTo(Criteria::class, 'criteria_2_id');
    }
}
