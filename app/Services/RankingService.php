<?php

namespace App\Services;

use App\Models\Criteria;
use App\Models\Supplier;
use App\Models\SupplierAssessment;
use Illuminate\Support\Collection;

class RankingService
{
    /**
     * Calculate ranking (HANYA untuk supplier & kriteria AKTIF)
     */
    public function calculateRanking()
    {
        // ✅ Ambil HANYA kriteria AKTIF dengan bobot > 0
        $criterias = Criteria::where('is_active', true)
            ->where('weight', '>', 0)
            ->orderBy('code')
            ->get();

        if ($criterias->isEmpty()) {
            return ['error' => 'Tidak ada kriteria aktif dengan bobot. Silakan hitung bobot kriteria terlebih dahulu.'];
        }

        // ✅ Ambil HANYA supplier AKTIF
        $suppliers = Supplier::where('is_active', true)
            ->orderBy('code')
            ->get();

        if ($suppliers->isEmpty()) {
            return ['error' => 'Tidak ada supplier aktif untuk dihitung.'];
        }

        $rankings = [];
        $maxScore = 0; // Untuk normalisasi ke percentage

        foreach ($suppliers as $supplier) {
            $totalScore = 0;
            $criteriaScores = [];
            $hasAllScores = true;

            foreach ($criterias as $criteria) {
                // Get assessment
                $assessment = SupplierAssessment::where('supplier_id', $supplier->id)
                    ->where('criteria_id', $criteria->id)
                    ->first();

                if (!$assessment) {
                    $hasAllScores = false;
                }

                $rawScore = $assessment ? $assessment->score : 0;

                // Normalized score (raw score dibagi 100 untuk normalisasi ke 0-1)
                $normalizedScore = $rawScore / 100;

                // Weighted score = normalized_score * weight
                $weightedScore = $normalizedScore * $criteria->weight;
                $totalScore += $weightedScore;

                $criteriaScores[$criteria->id] = [
                    'raw_score' => $rawScore,
                    'normalized_score' => $normalizedScore,
                    'weighted_score' => $weightedScore,
                ];
            }

            // Track max score untuk normalisasi
            if ($totalScore > $maxScore) {
                $maxScore = $totalScore;
            }

            $rankings[] = [
                'supplier' => $supplier,
                'total_score' => $totalScore,
                'criteria_scores' => $criteriaScores,
                'has_all_scores' => $hasAllScores,
            ];
        }

        // Sort by total_score DESC
        usort($rankings, function ($a, $b) {
            return $b['total_score'] <=> $a['total_score'];
        });

        // Add ranking position and percentage
        $rank = 1;
        foreach ($rankings as &$ranking) {
            $ranking['rank'] = $rank++;

            // ✅ Calculate percentage (normalisasi ke 100%)
            if ($maxScore > 0) {
                $ranking['percentage'] = ($ranking['total_score'] / $maxScore) * 100;
            } else {
                $ranking['percentage'] = 0;
            }
        }

        return [
            'rankings' => $rankings,
            'criterias' => $criterias,
        ];
    }

    /**
     * Get assessment progress
     */
    public function getAssessmentProgress()
    {
        // ✅ Hitung HANYA supplier dan kriteria yang AKTIF
        $activeSuppliers = Supplier::where('is_active', true)->count();
        $activeCriterias = Criteria::where('is_active', true)->count();

        // Validasi data kosong
        if ($activeSuppliers === 0 || $activeCriterias === 0) {
            return [
                'total' => 0,
                'completed' => 0,
                'percentage' => 0
            ];
        }

        $total = $activeSuppliers * $activeCriterias;

        // ✅ Hitung penilaian HANYA untuk supplier & kriteria yang AKTIF
        $completed = SupplierAssessment::whereHas('supplier', function ($q) {
            $q->where('is_active', true);
        })->whereHas('criteria', function ($q) {
            $q->where('is_active', true);
        })->count();

        return [
            'total' => $total,
            'completed' => $completed,
            'percentage' => $total > 0 ? round(($completed / $total) * 100, 2) : 0
        ];
    }


    /**
     * Check if assessment is complete
     */
    public function isAssessmentComplete(): bool
    {
        $progress = $this->getAssessmentProgress();
        return $progress['completed'] >= $progress['total'] && $progress['total'] > 0;
    }
}
