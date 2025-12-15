<?php

namespace App\Services;

use App\Models\Criteria;
use App\Models\CriteriaComparison;
use Illuminate\Support\Collection;

class AHPService
{
    /**
     * Saaty Scale untuk AHP
     */
    const SAATY_SCALE = [
        1 => 'Sama penting',
        2 => 'Sama hingga sedikit lebih penting',
        3 => 'Sedikit lebih penting',
        4 => 'Sedikit hingga jelas lebih penting',
        5 => 'Jelas lebih penting',
        6 => 'Jelas hingga sangat lebih penting',
        7 => 'Sangat lebih penting',
        8 => 'Sangat hingga mutlak lebih penting',
        9 => 'Mutlak lebih penting',
    ];

    /**
     * Random Index (RI) untuk Consistency Ratio
     */
    const RANDOM_INDEX = [
        1 => 0.00,
        2 => 0.00,
        3 => 0.58,
        4 => 0.90,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49,
    ];

    /**
     * Build pairwise comparison matrix
     * ✅ FIX: Hanya gunakan kriteria AKTIF
     */
    public function buildComparisonMatrix(): array
    {
        // ✅ HANYA KRITERIA AKTIF
        $criterias = Criteria::where('is_active', true)->orderBy('code')->get();
        $n = $criterias->count();
        $matrix = array_fill(0, $n, array_fill(0, $n, 1));

        foreach ($criterias as $i => $criteria1) {
            foreach ($criterias as $j => $criteria2) {
                if ($i === $j) {
                    $matrix[$i][$j] = 1;
                } elseif ($i < $j) {
                    // ✅ FIX: Cari comparison dengan normalisasi ID
                    $comparison = $this->findComparison($criteria1->id, $criteria2->id);

                    if ($comparison) {
                        // Jika criteria1 adalah criteria_1 di database
                        if ($comparison->criteria_1_id == $criteria1->id) {
                            $matrix[$i][$j] = $comparison->value;
                            $matrix[$j][$i] = 1 / $comparison->value;
                        } else {
                            // Jika criteria1 adalah criteria_2 di database (kebalikan)
                            $matrix[$i][$j] = 1 / $comparison->value;
                            $matrix[$j][$i] = $comparison->value;
                        }
                    } else {
                        $matrix[$i][$j] = 1;
                        $matrix[$j][$i] = 1;
                    }
                }
            }
        }

        return [
            'matrix' => $matrix,
            'criterias' => $criterias,
            'size' => $n
        ];
    }

    /**
     * ✅ NEW: Find comparison dengan normalisasi (bisa dari arah manapun)
     */
    private function findComparison($criteriaId1, $criteriaId2)
    {
        return CriteriaComparison::where(function ($query) use ($criteriaId1, $criteriaId2) {
            $query->where('criteria_1_id', $criteriaId1)
                ->where('criteria_2_id', $criteriaId2);
        })->orWhere(function ($query) use ($criteriaId1, $criteriaId2) {
            $query->where('criteria_1_id', $criteriaId2)
                ->where('criteria_2_id', $criteriaId1);
        })->first();
    }

    /**
     * Calculate criteria weights using Geometric Mean method
     * ✅ FIX: Hanya hitung kriteria AKTIF
     */
    public function calculateWeights(): array
    {
        $data = $this->buildComparisonMatrix();
        $matrix = $data['matrix'];
        $n = $data['size'];

        if ($n === 0) {
            return [
                'weights' => [],
                'criterias' => collect([]),
                'consistency_ratio' => 0,
                'lambda_max' => 0,
                'is_consistent' => true
            ];
        }

        // Step 1: Calculate geometric mean for each row
        $geometricMeans = [];
        for ($i = 0; $i < $n; $i++) {
            $product = 1;
            for ($j = 0; $j < $n; $j++) {
                $product *= $matrix[$i][$j];
            }
            $geometricMeans[$i] = pow($product, 1 / $n);
        }

        // Step 2: Normalize to get weights
        $sumGeometric = array_sum($geometricMeans);
        $weights = [];
        foreach ($geometricMeans as $i => $gm) {
            $weights[$i] = $gm / $sumGeometric;
        }

        // Step 3: Calculate Consistency Ratio
        $lambdaMax = $this->calculateLambdaMax($matrix, $weights, $n);
        $consistencyRatio = $this->calculateConsistencyRatio($lambdaMax, $n);

        return [
            'weights' => $weights,
            'criterias' => $data['criterias'],
            'consistency_ratio' => $consistencyRatio,
            'lambda_max' => $lambdaMax,
            'is_consistent' => $consistencyRatio <= 0.1
        ];
    }

    /**
     * ✅ NEW: Calculate Lambda Max
     */
    private function calculateLambdaMax(array $matrix, array $weights, int $n): float
    {
        if ($n <= 0) return 0;

        $lambdaMax = 0;
        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $matrix[$i][$j] * $weights[$j];
            }
            $lambdaMax += $sum / $weights[$i];
        }

        return $lambdaMax / $n;
    }

    /**
     * Calculate Consistency Ratio (CR)
     * ✅ UPDATED: Receive lambda max as parameter
     */
    private function calculateConsistencyRatio(float $lambdaMax, int $n): float
    {
        if ($n <= 2) {
            return 0; // Always consistent for n <= 2
        }

        // Calculate Consistency Index (CI)
        $ci = ($lambdaMax - $n) / ($n - 1);

        // Get Random Index (RI)
        $ri = self::RANDOM_INDEX[$n] ?? 1.49;

        // Calculate Consistency Ratio (CR)
        return $ri > 0 ? $ci / $ri : 0;
    }

    /**
     * Save calculated weights to database
     */
    public function saveWeights(): bool
    {
        $result = $this->calculateWeights();

        if (empty($result['weights'])) {
            return false;
        }

        foreach ($result['criterias'] as $index => $criterion) {
            $criterion->update([
                'weight' => round($result['weights'][$index], 4)
            ]);
        }

        return true;
    }

    /**
     * Get comparison progress
     * ✅ FIX: Hanya hitung kriteria AKTIF
     */
    public function getComparisonProgress(): array
    {
        // ✅ HANYA KRITERIA AKTIF
        $totalCriterias = Criteria::where('is_active', true)->count();

        if ($totalCriterias < 2) {
            return [
                'total' => 0,
                'completed' => 0,
                'percentage' => 0
            ];
        }

        $totalComparisons = ($totalCriterias * ($totalCriterias - 1)) / 2;

        // ✅ Hitung perbandingan HANYA untuk kriteria AKTIF
        $completedComparisons = CriteriaComparison::whereHas('criteria1', function ($q) {
            $q->where('is_active', true);
        })->whereHas('criteria2', function ($q) {
            $q->where('is_active', true);
        })->count();

        return [
            'total' => (int)$totalComparisons,
            'completed' => $completedComparisons,
            'percentage' => $totalComparisons > 0 ?
                round(($completedComparisons / $totalComparisons) * 100, 2) : 0
        ];
    }

    /**
     * ✅ NEW: Normalize comparison untuk konsistensi penyimpanan
     * Selalu simpan dengan criteria ID kecil sebagai criteria_1
     */
    public function normalizeComparisonData($criteriaId1, $criteriaId2, $value): array
    {
        // ✅ Tambah cast (float) untuk hasil pembagian
        if ($criteriaId1 < $criteriaId2) {
            return [
                'criteria_1_id' => $criteriaId1,
                'criteria_2_id' => $criteriaId2,
                'value' => (float) $value  // ✅ Cast ke float
            ];
        } else {
            return [
                'criteria_1_id' => $criteriaId2,
                'criteria_2_id' => $criteriaId1,
                'value' => (float) (1 / $value)  // ✅ Cast hasil pembagian
            ];
        }
    }
}
