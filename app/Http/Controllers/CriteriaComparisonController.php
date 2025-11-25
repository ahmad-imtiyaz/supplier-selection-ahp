<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\CriteriaComparison;
use App\Services\AHPService;
use Illuminate\Http\Request;

class CriteriaComparisonController extends Controller
{
    protected $ahpService;

    public function __construct(AHPService $ahpService)
    {
        $this->ahpService = $ahpService;
    }

    /**
     * Display comparison matrix
     */
    public function index()
    {
        $criterias = Criteria::where('is_active', true)->orderBy('code')->get();
        $progress = $this->ahpService->getComparisonProgress();

        // Build matrix untuk display
        $matrix = [];
        foreach ($criterias as $i => $criteria1) {
            foreach ($criterias as $j => $criteria2) {
                if ($i === $j) {
                    $matrix[$criteria1->id][$criteria2->id] = [
                        'value' => 1,
                        'display' => '1',
                        'editable' => false
                    ];
                } elseif ($i < $j) {
                    $comparison = CriteriaComparison::where('criteria_1_id', $criteria1->id)
                        ->where('criteria_2_id', $criteria2->id)
                        ->first();

                    $value = $comparison ? $comparison->value : null;
                    $matrix[$criteria1->id][$criteria2->id] = [
                        'value' => $value,
                        'display' => $value ? number_format($value, 2) : '-',
                        'editable' => true,
                        'comparison' => $comparison
                    ];

                    // Reciprocal
                    $matrix[$criteria2->id][$criteria1->id] = [
                        'value' => $value ? (1 / $value) : null,
                        'display' => $value ? '1/' . number_format($value, 2) : '-',
                        'editable' => false
                    ];
                }
            }
        }

        return view('criteria-comparisons.index', compact('criterias', 'matrix', 'progress'));
    }

    /**
     * Show form to compare two criteria
     */
    public function create(Request $request)
    {
        $criteria1 = Criteria::findOrFail($request->criteria1);
        $criteria2 = Criteria::findOrFail($request->criteria2);

        $comparison = CriteriaComparison::where('criteria_1_id', $criteria1->id)
            ->where('criteria_2_id', $criteria2->id)
            ->first();

        $saaty_scale = AHPService::SAATY_SCALE;

        return view('criteria-comparisons.create', compact('criteria1', 'criteria2', 'comparison', 'saaty_scale'));
    }

    /**
     * Store comparison
     */
    public function store(Request $request)
    {
        $request->validate([
            // PERBAIKAN: Ganti 'criterias' menjadi 'criteria'
            'criteria_1_id' => 'required|exists:criteria,id',
            'criteria_2_id' => 'required|exists:criteria,id|different:criteria_1_id',
            'value' => 'required|numeric|min:0.111|max:9',
            'note' => 'nullable|string|max:500'
        ]);

        CriteriaComparison::updateOrCreate(
            [
                'criteria_1_id' => $request->criteria_1_id,
                'criteria_2_id' => $request->criteria_2_id,
            ],
            [
                'value' => $request->value,
                'note' => $request->note,
            ]
        );

        return redirect()->route('criteria-comparisons.index')
            ->with('success', 'Perbandingan kriteria berhasil disimpan');
    }

    /**
     * Calculate weights based on comparisons
     */
    public function calculate()
    {
        $result = $this->ahpService->calculateWeights();

        if (empty($result['weights'])) {
            return redirect()->back()
                ->with('error', 'Tidak ada data perbandingan untuk dihitung');
        }

        // Save weights
        $this->ahpService->saveWeights();

        $consistencyStatus = $result['is_consistent'] ?
            'Konsisten (CR = ' . number_format($result['consistency_ratio'], 4) . ')' :
            'Tidak Konsisten (CR = ' . number_format($result['consistency_ratio'], 4) . ')';

        return redirect()->route('criteria-comparisons.result')
            ->with('success', 'Bobot kriteria berhasil dihitung. Status: ' . $consistencyStatus);
    }

    /**
     * Show calculation result
     */
    public function result()
    {
        $result = $this->ahpService->calculateWeights();
        $matrixData = $this->ahpService->buildComparisonMatrix();

        return view('criteria-comparisons.result', compact('result', 'matrixData'));
    }

    /**
     * Delete comparison
     */
    public function destroy(CriteriaComparison $criteriaComparison)
    {
        $criteriaComparison->delete();

        return redirect()->route('criteria-comparisons.index')
            ->with('success', 'Perbandingan kriteria berhasil dihapus');
    }

    /**
     * Reset all comparisons
     */
    public function reset()
    {
        CriteriaComparison::truncate();

        Criteria::query()->update(['weight' => 0]);

        return redirect()->route('criteria-comparisons.index')
            ->with('success', 'Semua perbandingan kriteria berhasil direset');
    }
}
