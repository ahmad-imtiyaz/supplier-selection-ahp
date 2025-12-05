<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActivityLogHelper
{
    /**
     * Log activity dengan format yang konsisten
     * 
     * @param string $action create|update|delete|calculate|reset
     * @param string $model Supplier|Criteria|CriteriaComparison|SupplierAssessment
     * @param string|int|null $modelId ID dari record yang diubah
     * @param string $description Deskripsi human-readable
     * @param array|null $oldValues Data sebelum perubahan
     * @param array|null $newValues Data setelah perubahan
     * @return ActivityLog|null
     */
    public static function log(
        string $action,
        string $model,
        $modelId = null,
        string $description = '',
        ?array $oldValues = null,
        ?array $newValues = null
    ): ?ActivityLog {
        try {
            // Skip jika user belum login
            if (!Auth::check()) {
                return null;
            }

            return ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model' => $model,
                'model_id' => $modelId,
                'description' => $description,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silent fail - logging tidak boleh break aplikasi
            \Log::error('Activity Log Failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Log untuk CREATE action
     */
    public static function logCreate(string $model, $modelId, string $name, ?array $newValues = null): ?ActivityLog
    {
        $description = self::buildCreateDescription($model, $name);
        return self::log('create', $model, $modelId, $description, null, $newValues);
    }

    /**
     * Log untuk UPDATE action
     */
    public static function logUpdate(string $model, $modelId, string $name, array $oldValues, array $newValues): ?ActivityLog
    {
        $description = self::buildUpdateDescription($model, $name, $oldValues, $newValues);
        return self::log('update', $model, $modelId, $description, $oldValues, $newValues);
    }

    /**
     * Log untuk DELETE action
     */
    public static function logDelete(string $model, $modelId, string $name, ?array $oldValues = null): ?ActivityLog
    {
        $description = self::buildDeleteDescription($model, $name);
        return self::log('delete', $model, $modelId, $description, $oldValues, null);
    }

    /**
     * Log untuk CALCULATE action (khusus AHP)
     */
    public static function logCalculate(string $description, ?array $data = null): ?ActivityLog
    {
        return self::log('calculate', 'CriteriaComparison', null, $description, null, $data);
    }

    /**
     * Log untuk RESET action
     */
    public static function logReset(string $model, string $description): ?ActivityLog
    {
        return self::log('reset', $model, null, $description, null, null);
    }

    /**
     * Build description untuk CREATE
     */
    private static function buildCreateDescription(string $model, string $name): string
    {
        $modelName = self::getModelName($model);
        return "Menambahkan {$modelName} baru: {$name}";
    }

    /**
     * Build description untuk UPDATE
     */
    private static function buildUpdateDescription(string $model, string $name, array $oldValues, array $newValues): string
    {
        $modelName = self::getModelName($model);

        // Untuk CriteriaComparison, format khusus
        if ($model === 'CriteriaComparison') {
            $criteria1 = $newValues['criteria_1'] ?? $oldValues['criteria_1'] ?? 'Kriteria 1';
            $criteria2 = $newValues['criteria_2'] ?? $oldValues['criteria_2'] ?? 'Kriteria 2';
            $oldValue = $oldValues['value'] ?? '-';
            $newValue = $newValues['value'] ?? '-';

            return "Mengubah nilai perbandingan AHP - {$criteria1} vs {$criteria2}: {$oldValue} → {$newValue}";
        }

        // Untuk SupplierAssessment, format khusus
        if ($model === 'SupplierAssessment') {
            $supplier = $newValues['supplier'] ?? $oldValues['supplier'] ?? 'Supplier';
            $criteria = $newValues['criteria'] ?? $oldValues['criteria'] ?? 'Kriteria';
            $oldScore = $oldValues['score'] ?? '-';
            $newScore = $newValues['score'] ?? '-';

            return "Mengubah penilaian {$supplier} pada kriteria {$criteria}: {$oldScore} → {$newScore}";
        }

        // Default format
        $changes = self::getMainChanges($oldValues, $newValues);
        if (!empty($changes)) {
            return "Mengubah {$modelName}: {$name} - {$changes}";
        }

        return "Mengubah {$modelName}: {$name}";
    }

    /**
     * Build description untuk DELETE
     */
    private static function buildDeleteDescription(string $model, string $name): string
    {
        $modelName = self::getModelName($model);
        return "Menghapus {$modelName}: {$name}";
    }

    /**
     * Get model name dalam bahasa Indonesia
     */
    private static function getModelName(string $model): string
    {
        return match ($model) {
            'Supplier' => 'supplier',
            'Criteria' => 'kriteria',
            'CriteriaComparison' => 'perbandingan kriteria',
            'SupplierAssessment' => 'penilaian supplier',
            default => strtolower($model),
        };
    }

    /**
     * Get main changes untuk description
     */
    private static function getMainChanges(array $oldValues, array $newValues): string
    {
        $importantFields = ['name', 'code', 'address', 'is_active', 'value', 'score'];
        $changes = [];

        foreach ($importantFields as $field) {
            if (isset($oldValues[$field]) && isset($newValues[$field]) && $oldValues[$field] !== $newValues[$field]) {
                $fieldName = self::getFieldName($field);
                $oldVal = is_bool($oldValues[$field]) ? ($oldValues[$field] ? 'Aktif' : 'Tidak Aktif') : $oldValues[$field];
                $newVal = is_bool($newValues[$field]) ? ($newValues[$field] ? 'Aktif' : 'Tidak Aktif') : $newValues[$field];

                $changes[] = "{$fieldName}: {$oldVal} → {$newVal}";
            }
        }

        return implode(', ', array_slice($changes, 0, 2)); // Maksimal 2 perubahan di description
    }

    /**
     * Get field name dalam bahasa Indonesia
     */
    private static function getFieldName(string $field): string
    {
        return match ($field) {
            'code' => 'Kode',
            'name' => 'Nama',
            'address' => 'Alamat',
            'phone' => 'Telepon',
            'email' => 'Email',
            'is_active' => 'Status',
            'value' => 'Nilai',
            'score' => 'Skor',
            default => ucfirst($field),
        };
    }

    /**
     * Get user activities (untuk show.blade.php)
     */
    public static function getUserActivities(int $userId, ?string $model = null, int $limit = 20)
    {
        $query = ActivityLog::where('user_id', $userId)
            ->with('user')
            ->latest();

        if ($model) {
            $query->where('model', $model);
        }

        return $query->limit($limit)->get();
    }

    /**
     * Get activity statistics
     */
    public static function getActivityStats(int $userId): array
    {
        $total = ActivityLog::where('user_id', $userId)->count();

        $byModel = ActivityLog::where('user_id', $userId)
            ->selectRaw('model, COUNT(*) as count')
            ->groupBy('model')
            ->pluck('count', 'model')
            ->toArray();

        $byAction = ActivityLog::where('user_id', $userId)
            ->selectRaw('action, COUNT(*) as count')
            ->groupBy('action')
            ->pluck('count', 'action')
            ->toArray();

        return [
            'total' => $total,
            'by_model' => $byModel,
            'by_action' => $byAction,
        ];
    }

    /**
     * Safe rollback - hanya rollback jika ada transaksi aktif
     */
    protected function safeRollback()
    {
        if (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
    }
}
