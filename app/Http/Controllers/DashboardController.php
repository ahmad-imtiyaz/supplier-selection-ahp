<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Supplier;
use App\Models\SupplierAssessment;
use App\Models\CriteriaComparison;
use App\Services\RankingService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $rankingService;

    public function __construct(RankingService $rankingService)
    {
        $this->rankingService = $rankingService;
    }

    public function index()
    {
        // Statistics
        $stats = [
            'total_criteria' => Criteria::count(),
            'active_criteria' => Criteria::where('is_active', true)->count(),
            'total_suppliers' => Supplier::count(),
            'active_suppliers' => Supplier::where('is_active', true)->count(),
            'total_assessments' => SupplierAssessment::count(),
            'criteria_comparisons' => CriteriaComparison::count(),
        ];

        // Check if AHP weights are calculated
        $criteriaWithWeights = Criteria::where('weight', '>', 0)->count();
        $ahpCompleted = $criteriaWithWeights > 0;

        // Assessment Progress
        $assessmentProgress = $this->rankingService->getAssessmentProgress();

        // Latest Rankings (if assessment is complete)
        $latestRankings = [];
        if ($assessmentProgress['completed'] > 0) {
            $rankingResult = $this->rankingService->calculateRanking();
            if (isset($rankingResult['rankings'])) {
                $latestRankings = array_slice($rankingResult['rankings'], 0, 5); // Top 5
            }
        }

        // Recent Activities (simplified)
        $recentActivities = [
            [
                'type' => 'assessment',
                'count' => SupplierAssessment::whereDate('created_at', today())->count(),
                'label' => 'Penilaian hari ini'
            ],
            [
                'type' => 'comparison',
                'count' => CriteriaComparison::whereDate('created_at', today())->count(),
                'label' => 'Perbandingan hari ini'
            ],
        ];

        // Criteria Weights for Chart
        $criteriaWeights = Criteria::where('is_active', true)
            ->where('weight', '>', 0)
            ->orderBy('weight', 'desc')
            ->get()
            ->map(function ($criteria) {
                return [
                    'name' => $criteria->name,
                    'weight' => $criteria->weight * 100, // Convert to percentage
                ];
            });

        return view('dashboard.index', compact(
            'stats',
            'ahpCompleted',
            'assessmentProgress',
            'latestRankings',
            'recentActivities',
            'criteriaWeights'
        ));
    }
}
