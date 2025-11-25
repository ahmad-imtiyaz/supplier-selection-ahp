<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    // Jika nama tabel 'suppliers', tidak perlu $table
    // Jika nama tabel 'supplier', tambahkan:
    // protected $table = 'supplier';

    protected $fillable = [
        'code',
        'name',
        'address',
        'phone',
        'email',
        'contact_person',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke SupplierAssessment
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(SupplierAssessment::class);
    }

    /**
     * Scope untuk supplier yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
