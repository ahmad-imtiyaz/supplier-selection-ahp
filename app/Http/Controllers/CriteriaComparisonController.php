<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\CriteriaComparison;
use App\Services\AHPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CriteriaComparisonController extends Controller
{
    protected $ahpService;

    public function __construct(AHPService $ahpService)
    {
        $this->ahpService = $ahpService;
    }

    public function index()
    {
        $criterias = Criteria::where('is_active', true)->orderBy('code')->get();

        if ($criterias->count() < 2) {
            return view('criteria-comparisons.index', [
                'criterias' => collect(),
                'matrix' => [],
                'progress' => ['total' => 0, 'completed' => 0, 'percentage' => 0]
            ])->with('warning', 'Minimal 2 kriteria aktif diperlukan untuk melakukan perbandingan AHP.');
        }

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

    public function create(Request $request)
    {
        try {
            $criteria1 = Criteria::findOrFail($request->criteria1);
            $criteria2 = Criteria::findOrFail($request->criteria2);

            if ($criteria1->id === $criteria2->id) {
                return redirect()
                    ->route('criteria-comparisons.index')
                    ->with('error', 'Tidak dapat membandingkan kriteria dengan dirinya sendiri.');
            }

            $comparison = CriteriaComparison::where('criteria_1_id', $criteria1->id)
                ->where('criteria_2_id', $criteria2->id)
                ->first();

            $saaty_scale = AHPService::SAATY_SCALE;

            return view('criteria-comparisons.create', compact('criteria1', 'criteria2', 'comparison', 'saaty_scale'));
        } catch (\Exception $e) {
            return redirect()
                ->route('criteria-comparisons.index')
                ->with('error', 'Kriteria tidak ditemukan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'criteria_1_id' => 'required|exists:criteria,id',
                'criteria_2_id' => 'required|exists:criteria,id|different:criteria_1_id',
                'value' => 'required|numeric|min:0.111|max:9',
                'note' => 'nullable|string|max:500'
            ], [
                'criteria_1_id.required' => 'Kriteria pertama wajib dipilih',
                'criteria_1_id.exists' => 'Kriteria pertama tidak valid',
                'criteria_2_id.required' => 'Kriteria kedua wajib dipilih',
                'criteria_2_id.exists' => 'Kriteria kedua tidak valid',
                'criteria_2_id.different' => 'Kriteria pertama dan kedua harus berbeda',
                'value.required' => 'Nilai perbandingan wajib diisi',
                'value.min' => 'Nilai perbandingan minimal 1/9 (0.111)',
                'value.max' => 'Nilai perbandingan maksimal 9',
                'note.max' => 'Catatan maksimal 500 karakter',
            ]);

            DB::beginTransaction();

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

            DB::commit();

            return redirect()->route('criteria-comparisons.index')
                ->with('success', 'Perbandingan kriteria berhasil disimpan');
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

    public function calculate()
    {
        try {
            $progress = $this->ahpService->getComparisonProgress();

            if ($progress['percentage'] < 100) {
                return redirect()
                    ->back()
                    ->with('warning', 'Perbandingan belum lengkap! Silakan lengkapi semua perbandingan kriteria terlebih dahulu. (' . $progress['completed'] . '/' . $progress['total'] . ')');
            }

            $result = $this->ahpService->calculateWeights();

            if (empty($result['weights'])) {
                return redirect()->back()
                    ->with('error', 'Tidak ada data perbandingan untuk dihitung');
            }

            DB::beginTransaction();

            // Save weights
            $this->ahpService->saveWeights();

            DB::commit();

            if (!$result['is_consistent']) {
                return redirect()->route('criteria-comparisons.result')
                    ->with('warning', 'Perhitungan selesai tetapi TIDAK KONSISTEN (CR = ' . number_format($result['consistency_ratio'], 4) . '). Disarankan untuk merevisi perbandingan Anda.');
            }

            return redirect()->route('criteria-comparisons.result')
                ->with('success', 'Bobot kriteria berhasil dihitung dan KONSISTEN (CR = ' . number_format($result['consistency_ratio'], 4) . ')');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghitung bobot: ' . $e->getMessage());
        }
    }

    public function result()
    {
        try {
            $result = $this->ahpService->calculateWeights();

            if (empty($result['weights'])) {
                return redirect()
                    ->route('criteria-comparisons.index')
                    ->with('error', 'Belum ada perhitungan bobot. Silakan lengkapi perbandingan kriteria terlebih dahulu.');
            }

            $matrixData = $this->ahpService->buildComparisonMatrix();

            return view('criteria-comparisons.result', compact('result', 'matrixData'));
        } catch (\Exception $e) {
            return redirect()
                ->route('criteria-comparisons.index')
                ->with('error', 'Terjadi kesalahan saat menampilkan hasil: ' . $e->getMessage());
        }
    }

    public function destroy(CriteriaComparison $criteriaComparison)
    {
        try {
            DB::beginTransaction();

            $criteriaComparison->delete();

            DB::commit();

            return redirect()->route('criteria-comparisons.index')
                ->with('success', 'Perbandingan kriteria berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus perbandingan: ' . $e->getMessage());
        }
    }

    public function reset()
    {
        try {
            DB::beginTransaction();

            CriteriaComparison::truncate();
            Criteria::query()->update(['weight' => 0]);

            DB::commit();

            return redirect()->route('criteria-comparisons.index')
                ->with('success', 'Semua perbandingan kriteria berhasil direset');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat mereset perbandingan: ' . $e->getMessage());
        }
    }
}
