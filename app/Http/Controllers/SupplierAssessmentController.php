<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Supplier;
use App\Models\SupplierAssessment;
use App\Services\RankingService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RankingExport;

class SupplierAssessmentController extends Controller
{
    protected $rankingService;

    public function __construct(RankingService $rankingService)
    {
        $this->rankingService = $rankingService;
    }

    /**
     * Display assessment matrix
     */
    public function index()
    {
        $suppliers = Supplier::active()->orderBy('code')->get();
        $criterias = Criteria::active()->orderBy('code')->get();
        $progress = $this->rankingService->getAssessmentProgress();

        // Build assessment matrix
        $assessments = [];
        foreach ($suppliers as $supplier) {
            foreach ($criterias as $criteria) {
                $assessment = SupplierAssessment::where('supplier_id', $supplier->id)
                    ->where('criteria_id', $criteria->id)
                    ->first();

                $assessments[$supplier->id][$criteria->id] = $assessment;
            }
        }

        return view('supplier-assessments.index', compact('suppliers', 'criterias', 'assessments', 'progress'));
    }

    /**
     * Show form to assess supplier
     */
    public function create(Request $request)
    {
        $supplier = Supplier::findOrFail($request->supplier);
        $criteria = Criteria::findOrFail($request->criteria);

        $assessment = SupplierAssessment::where('supplier_id', $supplier->id)
            ->where('criteria_id', $criteria->id)
            ->first();

        return view('supplier-assessments.create', compact('supplier', 'criteria', 'assessment'));
    }

    /**
     * Store or update assessment
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'criteria_id' => 'required|exists:criteria,id',
            'score' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:500'
        ]);

        SupplierAssessment::updateOrCreate(
            [
                'supplier_id' => $request->supplier_id,
                'criteria_id' => $request->criteria_id,
            ],
            [
                'score' => $request->score,
                'notes' => $request->notes,
            ]
        );

        return redirect()->route('supplier-assessments.index')
            ->with('success', 'Penilaian supplier berhasil disimpan');
    }

    /**
     * Show ranking result
     */
    public function ranking()
    {
        $result = $this->rankingService->calculateRanking();

        if (isset($result['error'])) {
            return redirect()->route('supplier-assessments.index')
                ->with('error', $result['error']);
        }

        return view('supplier-assessments.ranking', $result);
    }

    /**
     * Delete assessment
     */
    public function destroy(SupplierAssessment $supplierAssessment)
    {
        $supplierAssessment->delete();

        return redirect()->route('supplier-assessments.index')
            ->with('success', 'Penilaian berhasil dihapus');
    }

    /**
     * Reset all assessments
     */
    public function reset()
    {
        SupplierAssessment::truncate();

        return redirect()->route('supplier-assessments.index')
            ->with('success', 'Semua penilaian berhasil direset');
    }

    /**
     * Export Ranking to PDF
     */
    public function exportPdf()
    {
        $result = $this->rankingService->calculateRanking();

        if (isset($result['error'])) {
            return redirect()->back()->with('error', $result['error']);
        }

        $rankings = $result['rankings'];
        $criterias = $result['criterias'];

        $pdf = Pdf::loadView('supplier-assessments.ranking-pdf', compact('rankings', 'criterias'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif'
            ]);

        return $pdf->download('ranking-supplier-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export Ranking to Excel
     */
    public function exportExcel()
    {
        $result = $this->rankingService->calculateRanking();

        if (isset($result['error'])) {
            return redirect()->back()->with('error', $result['error']);
        }

        $rankings = $result['rankings'];
        $criterias = $result['criterias'];

        return Excel::download(
            new RankingExport($rankings, $criterias),
            'ranking-supplier-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export Detail AHP Calculation to PDF
     */
    public function exportDetailPdf()
    {
        $result = $this->rankingService->calculateRanking();

        if (isset($result['error'])) {
            return redirect()->back()->with('error', $result['error']);
        }

        $rankings = $result['rankings'];
        $criterias = $result['criterias'];

        $pdf = Pdf::loadView('supplier-assessments.detail-pdf', compact('rankings', 'criterias'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif'
            ]);

        return $pdf->download('detail-perhitungan-ahp-' . date('Y-m-d') . '.pdf');
    }
}
