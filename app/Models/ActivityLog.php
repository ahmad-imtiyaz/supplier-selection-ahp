<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke User yang melakukan aksi
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: filter by model/module
     */
    public function scopeByModel($query, $model)
    {
        return $query->where('model', $model);
    }

    /**
     * Scope: filter by action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: get recent activities
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->limit($limit);
    }

    /**
     * Get action badge color
     */
    public function getActionBadgeColorAttribute(): string
    {
        return match ($this->action) {
            'create' => 'green',
            'update' => 'blue',
            'delete' => 'red',
            'calculate' => 'purple',
            'reset' => 'yellow',
            default => 'gray',
        };
    }

    /**
     * Get action icon as SVG
     */
    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            'create' => '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                     </svg>',

            'update' => '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                     </svg>',

            'delete' => '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                     </svg>',

            'calculate' => '<svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>',

            'reset' => '<svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>',

            default => '<svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>',
        };
    }

    /**
     * Get action text in Indonesian
     */
    public function getActionTextAttribute(): string
    {
        return match ($this->action) {
            'create' => 'Menambahkan',
            'update' => 'Mengubah',
            'delete' => 'Menghapus',
            'calculate' => 'Menghitung',
            'reset' => 'Mereset',
            default => 'Aksi',
        };
    }

    /**
     * Get model name in Indonesian
     */
    public function getModelNameAttribute(): string
    {
        return match ($this->model) {
            'Supplier' => 'Supplier',
            'Criteria' => 'Kriteria',
            'CriteriaComparison' => 'Perbandingan AHP',
            'SupplierAssessment' => 'Penilaian Supplier',
            'SupplierTrackRecord' => 'Track Record Supplier',
            default => $this->model,
        };
    }

    /**
     * Get changes summary (untuk display di UI)
     */
    public function getChangesSummaryAttribute(): ?array
    {
        if (!$this->old_values || !$this->new_values) {
            return null;
        }

        $changes = [];

        foreach ($this->new_values as $key => $newValue) {
            $oldValue = $this->old_values[$key] ?? null;

            if ($oldValue !== $newValue) {
                $changes[] = [
                    'field' => $this->formatFieldName($key),
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changes;
    }

    /**
     * Format field name to Indonesian
     */
    private function formatFieldName(string $field): string
    {
        return match ($field) {
            'code' => 'Kode',
            'name' => 'Nama',
            'address' => 'Alamat',
            'phone' => 'Telepon',
            'email' => 'Email',
            'contact_person' => 'Kontak Person',
            'description' => 'Deskripsi',
            'is_active' => 'Status Aktif',
            'value' => 'Nilai',
            'score' => 'Skor',
            'notes' => 'Catatan',
            'note' => 'Catatan',
            'criteria_1' => 'Kriteria 1',
            'criteria_2' => 'Kriteria 2',
            'weight' => 'Bobot',
            'period' => 'Periode',
            'recommended_score' => 'Skor Rekomendasi',
            'supplier' => 'Supplier',
            'criteria' => 'Kriteria',
            'year' => 'Tahun',
            'month' => 'Bulan',
            default => ucfirst(str_replace('_', ' ', $field)),
        };
    }

    /**
     * Check if has changes to display
     */
    public function hasValueChanges(): bool
    {
        return !empty($this->changes_summary);
    }

    /**
     * Get time ago in Indonesian (FIXED - properly rounded)
     */
    public function getTimeAgoAttribute(): string
    {
        $diffInMinutes = $this->created_at->diffInMinutes(now());
        $diffInHours = $this->created_at->diffInHours(now());
        $diffInDays = $this->created_at->diffInDays(now());

        if ($diffInMinutes < 1) {
            return 'Baru saja';
        } elseif ($diffInMinutes < 60) {
            return $diffInMinutes . ' menit yang lalu';
        } elseif ($diffInHours < 24) {
            return $diffInHours . ' jam yang lalu';
        } elseif ($diffInDays < 7) {
            return $diffInDays . ' hari yang lalu';
        } elseif ($diffInDays < 30) {
            $weeks = floor($diffInDays / 7);
            return $weeks . ' minggu yang lalu';
        } else {
            return $this->created_at->format('d M Y, H:i');
        }
    }
}
