<?php

namespace App\Services;

use App\Models\Criteria;
use App\Models\Supplier;
use App\Models\SupplierAssessment;
use Illuminate\Support\Collection;

class RankingService
{
    /**
     * Calculate final scores and ranking for all suppliers
     */
    public function calculateRanking(): array
    {
        $suppliers = Supplier::active()->get();
        $criterias = Criteria::active()->where('weight', '>', 0)->get();

        if ($criterias->isEmpty()) {
            return [
                'error' => 'Belum ada bobot kriteria. Silakan hitung bobot di menu Perbandingan AHP terlebih dahulu.',
                'rankings' => []
            ];
        }

        $rankings = [];

        foreach ($suppliers as $supplier) {
            $totalScore = 0;
            $criteriaScores = [];
            $hasAllScores = true;

            foreach ($criterias as $criteria) {
                $assessment = SupplierAssessment::where('supplier_id', $supplier->id)
                    ->where('criteria_id', $criteria->id)
                    ->first();

                if (!$assessment) {
                    $hasAllScores = false;
                    $criteriaScores[$criteria->id] = [
                        'criteria' => $criteria,
                        'raw_score' => 0,
                        'normalized_score' => 0,
                        'weighted_score' => 0,
                    ];
                    continue;
                }

                // Normalisasi sederhana: score / 100
                $normalizedScore = $assessment->score / 100;

                // Weighted score: normalized * weight
                $weightedScore = $normalizedScore * $criteria->weight;

                $criteriaScores[$criteria->id] = [
                    'criteria' => $criteria,
                    'raw_score' => $assessment->score,
                    'normalized_score' => $normalizedScore,
                    'weighted_score' => $weightedScore,
                ];

                $totalScore += $weightedScore;
            }

            $rankings[] = [
                'supplier' => $supplier,
                'total_score' => $totalScore,
                'criteria_scores' => $criteriaScores,
                'has_all_scores' => $hasAllScores,
                'percentage' => $totalScore * 100, // Convert to percentage
            ];
        }

        // Sort by total_score descending
        usort($rankings, function ($a, $b) {
            return $b['total_score'] <=> $a['total_score'];
        });

        // Add rank
        foreach ($rankings as $index => &$ranking) {
            $ranking['rank'] = $index + 1;
        }

        return [
            'rankings' => $rankings,
            'criterias' => $criterias,
        ];
    }

    /**
     * Get assessment progress
     */
    public function getAssessmentProgress(): array
    {
        $suppliers = Supplier::active()->count();
        $criterias = Criteria::active()->count();
        $totalRequired = $suppliers * $criterias;
        $completed = SupplierAssessment::count();

        return [
            'total' => $totalRequired,
            'completed' => $completed,
            'percentage' => $totalRequired > 0 ? ($completed / $totalRequired) * 100 : 0,
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
