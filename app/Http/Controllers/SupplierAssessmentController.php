<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Supplier;
use App\Models\SupplierAssessment;
use App\Services\RankingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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

        if ($suppliers->isEmpty() || $criterias->isEmpty()) {
            return view('supplier-assessments.index', [
                'suppliers' => $suppliers,
                'criterias' => $criterias,
                'assessments' => [],
                'progress' => ['total' => 0, 'completed' => 0, 'percentage' => 0]
            ])->with('warning', 'Pastikan minimal 1 supplier dan 1 kriteria aktif untuk melakukan penilaian.');
        }

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
        try {
            $supplier = Supplier::findOrFail($request->supplier);
            $criteria = Criteria::findOrFail($request->criteria);

            if (!$supplier->is_active) {
                return redirect()
                    ->route('supplier-assessments.index')
                    ->with('warning', 'Supplier tidak aktif dan tidak dapat dinilai.');
            }

            if (!$criteria->is_active) {
                return redirect()
                    ->route('supplier-assessments.index')
                    ->with('warning', 'Kriteria tidak aktif dan tidak dapat digunakan untuk penilaian.');
            }

            $assessment = SupplierAssessment::where('supplier_id', $supplier->id)
                ->where('criteria_id', $criteria->id)
                ->first();

            return view('supplier-assessments.create', compact('supplier', 'criteria', 'assessment'));
        } catch (\Exception $e) {
            return redirect()
                ->route('supplier-assessments.index')
                ->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Store or update assessment
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'criteria_id' => 'required|exists:criteria,id',
                'score' => 'required|numeric|min:0|max:100',
                'notes' => 'nullable|string|max:500'
            ], [
                'supplier_id.required' => 'Supplier wajib dipilih',
                'supplier_id.exists' => 'Supplier tidak valid',
                'criteria_id.required' => 'Kriteria wajib dipilih',
                'criteria_id.exists' => 'Kriteria tidak valid',
                'score.required' => 'Nilai penilaian wajib diisi',
                'score.min' => 'Nilai penilaian minimal 0',
                'score.max' => 'Nilai penilaian maksimal 100',
                'notes.max' => 'Catatan maksimal 500 karakter',
            ]);

            DB::beginTransaction();

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

            DB::commit();

            return redirect()->route('supplier-assessments.index')
                ->with('success', 'Penilaian supplier berhasil disimpan');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Validasi gagal! Periksa kembali form Anda.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show ranking result
     */
    public function ranking()
    {
        try {
            // Check if weights are calculated
            $criteriaWithWeights = Criteria::active()->where('weight', '>', 0)->count();
            if ($criteriaWithWeights === 0) {
                return redirect()
                    ->route('supplier-assessments.index')
                    ->with('warning', 'Bobot kriteria belum dihitung. Silakan hitung bobot kriteria di menu Perbandingan AHP terlebih dahulu.');
            }

            $result = $this->rankingService->calculateRanking();

            if (isset($result['error'])) {
                return redirect()
                    ->route('supplier-assessments.index')
                    ->with('error', $result['error']);
            }

            // Check if there are any assessments
            if (empty($result['rankings'])) {
                return redirect()
                    ->route('supplier-assessments.index')
                    ->with('warning', 'Belum ada penilaian supplier. Silakan lakukan penilaian terlebih dahulu.');
            }

            return view('supplier-assessments.ranking', $result);
        } catch (\Exception $e) {
            return redirect()
                ->route('supplier-assessments.index')
                ->with('error', 'Terjadi kesalahan saat menghitung ranking: ' . $e->getMessage());
        }
    }

    /**
     * Delete assessment
     */
    public function destroy(SupplierAssessment $supplierAssessment)
    {
        try {
            DB::beginTransaction();

            $supplierAssessment->delete();

            DB::commit();

            return redirect()->route('supplier-assessments.index')
                ->with('success', 'Penilaian berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus penilaian: ' . $e->getMessage());
        }
    }

    /**
     * Reset all assessments
     */
    public function reset()
    {
        try {
            DB::beginTransaction();

            SupplierAssessment::truncate();

            DB::commit();

            return redirect()->route('supplier-assessments.index')
                ->with('success', 'Semua penilaian berhasil direset');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat mereset penilaian: ' . $e->getMessage());
        }
    }

    /**
     * Export Ranking to PDF
     */
    public function exportPdf()
    {
        try {
            $result = $this->rankingService->calculateRanking();

            if (isset($result['error'])) {
                return redirect()->back()->with('error', $result['error']);
            }

            if (empty($result['rankings'])) {
                return redirect()->back()->with('warning', 'Tidak ada data ranking untuk di-export.');
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
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat export PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export Ranking to Excel
     */
    public function exportExcel()
    {
        try {
            $result = $this->rankingService->calculateRanking();

            if (isset($result['error'])) {
                return redirect()->back()->with('error', $result['error']);
            }

            if (empty($result['rankings'])) {
                return redirect()->back()->with('warning', 'Tidak ada data ranking untuk di-export.');
            }

            $rankings = $result['rankings'];
            $criterias = $result['criterias'];

            return Excel::download(
                new RankingExport($rankings, $criterias),
                'ranking-supplier-' . date('Y-m-d') . '.xlsx'
            );
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat export Excel: ' . $e->getMessage());
        }
    }

    /**
     * Export Detail AHP Calculation to PDF
     */
    public function exportDetailPdf()
    {
        try {
            $result = $this->rankingService->calculateRanking();

            if (isset($result['error'])) {
                return redirect()->back()->with('error', $result['error']);
            }

            if (empty($result['rankings'])) {
                return redirect()->back()->with('warning', 'Tidak ada data ranking untuk di-export.');
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
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat export detail PDF: ' . $e->getMessage());
        }
    }
}
