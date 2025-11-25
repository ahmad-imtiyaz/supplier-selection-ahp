<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierAssessment extends Model
{
    protected $fillable = [
        'supplier_id',
        'criteria_id',
        'score',
        'notes',
    ];

    protected $casts = [
        'score' => 'decimal:2',
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
}
