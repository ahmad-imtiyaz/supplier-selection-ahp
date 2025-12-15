<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\CriteriaComparison;
use App\Services\AHPService;
use App\Helpers\ActivityLogHelper;
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
        // ✅ HANYA KRITERIA AKTIF
        $criterias = Criteria::where('is_active', true)->orderBy('code')->get();

        if ($criterias->count() < 2) {
            return view('criteria-comparisons.index', [
                'criterias' => collect(),
                'matrix' => [],
                'progress' => ['total' => 0, 'completed' => 0, 'percentage' => 0]
            ])->with('warning', 'Minimal 2 kriteria aktif diperlukan untuk melakukan perbandingan AHP.');
        }

        $progress = $this->ahpService->getComparisonProgress();

        // ✅ Build matrix untuk display - SEMUA CELL EDITABLE (kecuali diagonal)
        $matrix = [];
        foreach ($criterias as $i => $criteria1) {
            foreach ($criterias as $j => $criteria2) {
                if ($i === $j) {
                    // Diagonal = 1
                    $matrix[$criteria1->id][$criteria2->id] = [
                        'value' => 1,
                        'display' => '1',
                        'editable' => false,
                        'is_diagonal' => true
                    ];
                } else {
                    // ✅ SEMUA CELL LAIN EDITABLE
                    $comparison = $this->findComparison($criteria1->id, $criteria2->id);

                    if ($comparison) {
                        // ✅ FIX: Logika display yang BENAR
                        // Database menyimpan dengan normalisasi: ID kecil selalu jadi criteria_1_id
                        // Contoh: Input C1 vs C2 = 4 → DB: (criteria_1_id=1, criteria_2_id=2, value=4)
                        // Contoh: Input C2 vs C1 = 4 → DB: (criteria_1_id=1, criteria_2_id=2, value=0.25)

                        // Cek: Apakah cell ini sesuai arah penyimpanan di DB?
                        $isDatabaseDirection = (
                            $comparison->criteria_1_id == $criteria1->id &&
                            $comparison->criteria_2_id == $criteria2->id
                        );

                        if ($isDatabaseDirection) {
                            // ✅ Arah SAMA dengan database → tampilkan nilai asli
                            $value = (float) $comparison->value;
                            $display = number_format($value, 2);
                            $isReciprocal = false;
                        } else {
                            // ✅ Arah KEBALIKAN → tampilkan reciprocal
                            $value = 1 / (float) $comparison->value;
                            $display = '1/' . number_format((float) $comparison->value, 2);
                            $isReciprocal = true;
                        }

                        $matrix[$criteria1->id][$criteria2->id] = [
                            'value' => $value,
                            'display' => $display,
                            'editable' => true,
                            'comparison' => $comparison,
                            'is_reciprocal' => $isReciprocal
                        ];
                    } else {
                        // Belum ada perbandingan
                        $matrix[$criteria1->id][$criteria2->id] = [
                            'value' => null,
                            'display' => '-',
                            'editable' => true,
                            'comparison' => null
                        ];
                    }
                }
            }
        }

        return view('criteria-comparisons.index', compact('criterias', 'matrix', 'progress'));
    }

    /**
     * ✅ Helper untuk find comparison (kedua arah)
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

            // ✅ Cari comparison dari kedua arah
            $comparison = $this->findComparison($criteria1->id, $criteria2->id);

            // ✅ Jika comparison ada tapi terbalik, swap untuk display
            if ($comparison && $comparison->criteria_1_id == $criteria2->id) {
                // Swap agar sesuai dengan form display
                $temp = $criteria1;
                $criteria1 = $criteria2;
                $criteria2 = $temp;
            }

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

            // ✅ Get data SEBELUM transaction
            $criteria1 = Criteria::find($request->criteria_1_id);
            $criteria2 = Criteria::find($request->criteria_2_id);

            // ✅ PENTING: Simpan PERSIS seperti input user (TANPA normalisasi otomatis)
            // Normalisasi hanya untuk mencari, bukan untuk menyimpan
            $normalizedData = [
                'criteria_1_id' => $request->criteria_1_id,
                'criteria_2_id' => $request->criteria_2_id,
                'value' => (float) $request->value
            ];

            // Cari existing comparison (dari kedua arah)
            $existingComparison = $this->findComparison(
                $request->criteria_1_id,
                $request->criteria_2_id
            );

            $isUpdate = $existingComparison !== null;
            $oldValues = null;

            if ($isUpdate) {
                $oldValues = [
                    'criteria_1' => $criteria1->name . ' (' . $criteria1->code . ')',
                    'criteria_2' => $criteria2->name . ' (' . $criteria2->code . ')',
                    'value' => (float) $existingComparison->value,
                    'note' => $existingComparison->note,
                ];
            }

            DB::beginTransaction();

            // ✅ Update or Create LANGSUNG tanpa normalisasi
            // Jika sudah ada comparison dari arah manapun, update yang existing
            // Jika belum ada, buat baru dengan arah yang user input
            if ($existingComparison) {
                // Update existing (bisa dari arah manapun)
                $existingComparison->update([
                    'criteria_1_id' => $request->criteria_1_id,
                    'criteria_2_id' => $request->criteria_2_id,
                    'value' => (float) $request->value,
                    'note' => $request->note,
                ]);
                $comparison = $existingComparison;
            } else {
                // Buat baru
                $comparison = CriteriaComparison::create([
                    'criteria_1_id' => $request->criteria_1_id,
                    'criteria_2_id' => $request->criteria_2_id,
                    'value' => (float) $request->value,
                    'note' => $request->note,
                ]);
            }

            $newValues = [
                'criteria_1' => $criteria1->name . ' (' . $criteria1->code . ')',
                'criteria_2' => $criteria2->name . ' (' . $criteria2->code . ')',
                'value' => (float) $request->value, // Nilai asli yang diinput user
                'note' => $comparison->note,
            ];

            if ($isUpdate) {
                ActivityLogHelper::logUpdate(
                    'CriteriaComparison',
                    $comparison->id,
                    $criteria1->name . ' vs ' . $criteria2->name,
                    $oldValues,
                    $newValues
                );
            } else {
                ActivityLogHelper::logCreate(
                    'CriteriaComparison',
                    $comparison->id,
                    $criteria1->name . ' vs ' . $criteria2->name . ' = ' . $request->value,
                    $newValues
                );
            }

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
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

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

            $this->ahpService->saveWeights();

            // Build weights data untuk logging
            $weightsData = [];
            foreach ($result['criterias'] as $index => $criteria) {
                $weightsData[$criteria->code] = [
                    'name' => $criteria->name,
                    'weight' => round($result['weights'][$index], 4),
                ];
            }

            $crStatus = $result['is_consistent'] ? 'KONSISTEN' : 'TIDAK KONSISTEN';
            $description = "Menghitung bobot kriteria menggunakan AHP - Status: {$crStatus} (CR = " . number_format($result['consistency_ratio'], 4) . ")";

            ActivityLogHelper::logCalculate(
                $description,
                [
                    'weights' => $weightsData,
                    'consistency_ratio' => $result['consistency_ratio'],
                    'is_consistent' => $result['is_consistent'],
                    'lambda_max' => $result['lambda_max'] ?? null,
                ]
            );

            DB::commit();

            if (!$result['is_consistent']) {
                return redirect()->route('criteria-comparisons.result')
                    ->with('warning', 'Perhitungan selesai tetapi TIDAK KONSISTEN (CR = ' . number_format($result['consistency_ratio'], 4) . '). Disarankan untuk merevisi perbandingan Anda.');
            }

            return redirect()->route('criteria-comparisons.result')
                ->with('success', 'Bobot kriteria berhasil dihitung dan KONSISTEN (CR = ' . number_format($result['consistency_ratio'], 4) . ')');
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

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
            // ✅ Get data SEBELUM transaction
            $criteria1 = $criteriaComparison->criteria1;
            $criteria2 = $criteriaComparison->criteria2;

            DB::beginTransaction();

            ActivityLogHelper::logDelete(
                'CriteriaComparison',
                $criteriaComparison->id,
                $criteria1->name . ' vs ' . $criteria2->name,
                [
                    'criteria_1' => $criteria1->name . ' (' . $criteria1->code . ')',
                    'criteria_2' => $criteria2->name . ' (' . $criteria2->code . ')',
                    'value' => (float) $criteriaComparison->value,
                    'note' => $criteriaComparison->note,
                ]
            );

            $criteriaComparison->delete();

            DB::commit();

            return redirect()->route('criteria-comparisons.index')
                ->with('success', 'Perbandingan kriteria berhasil dihapus');
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus perbandingan: ' . $e->getMessage());
        }
    }

    public function reset()
    {
        try {
            // Hitung sebelum transaksi
            $totalComparisons = CriteriaComparison::count();
            $affectedCriteria = Criteria::where('weight', '>', 0)->count();

            if ($totalComparisons === 0 && $affectedCriteria === 0) {
                return redirect()->route('criteria-comparisons.index')
                    ->with('info', 'Tidak ada perbandingan atau bobot yang perlu direset.');
            }

            DB::beginTransaction();

            // ✅ Pakai delete() agar tetap dalam transaction
            CriteriaComparison::query()->delete();

            // Reset bobot
            Criteria::query()->update(['weight' => 0]);

            ActivityLogHelper::logReset(
                'CriteriaComparison',
                "Mereset semua perbandingan kriteria - {$totalComparisons} perbandingan dan {$affectedCriteria} bobot kriteria dihapus"
            );

            DB::commit();

            // (opsional) reset auto increment setelah commit
            DB::statement('ALTER TABLE criteria_comparisons AUTO_INCREMENT = 1');

            return redirect()->route('criteria-comparisons.index')
                ->with('success', 'Semua perbandingan kriteria berhasil direset');
        } catch (\Exception $e) {

            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat mereset perbandingan: ' . $e->getMessage());
        }
    }
}
