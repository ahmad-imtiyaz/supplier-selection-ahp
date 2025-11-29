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
     * Get action icon
     */
    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            'create' => 'plus-circle',
            'update' => 'pencil',
            'delete' => 'trash',
            'calculate' => 'calculator',
            'reset' => 'refresh',
            default => 'document',
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
     * Get time ago in Indonesian
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
