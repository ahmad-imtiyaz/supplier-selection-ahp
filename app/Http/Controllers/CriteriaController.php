<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Helpers\ActivityLogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CriteriaController extends Controller
{
    public function index()
    {
        $criteria = Criteria::latest()->get();
        return view('criteria.index', compact('criteria'));
    }

    public function create()
    {
        return view('criteria.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:10|unique:criteria,code|regex:/^[A-Z0-9]+$/',
                'name' => 'required|string|max:255|min:3',
                'description' => 'nullable|string|max:1000',
                'is_active' => 'boolean',
            ], [
                'code.required' => 'Kode kriteria wajib diisi',
                'code.unique' => 'Kode kriteria sudah digunakan',
                'code.regex' => 'Kode kriteria hanya boleh huruf kapital dan angka',
                'name.required' => 'Nama kriteria wajib diisi',
                'name.min' => 'Nama kriteria minimal 3 karakter',
                'description.max' => 'Deskripsi maksimal 1000 karakter',
            ]);

            DB::beginTransaction();

            $validated['is_active'] = $request->has('is_active');
            $validated['weight'] = 0;

            $criteria = Criteria::create($validated);

            ActivityLogHelper::logCreate(
                'Criteria',
                $criteria->id,
                $criteria->name . ' (' . $criteria->code . ')',
                [
                    'code' => $criteria->code,
                    'name' => $criteria->name,
                    'description' => $criteria->description,
                    'is_active' => $criteria->is_active,
                ]
            );

            DB::commit();

            return redirect()
                ->route('criteria.index')
                ->with('success', 'Kriteria berhasil ditambahkan!');
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

    public function show(Criteria $criterion)
    {
        return view('criteria.show', compact('criterion'));
    }

    public function edit(Criteria $criterion)
    {
        return view('criteria.edit', compact('criterion'));
    }

    public function update(Request $request, Criteria $criterion)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:10|unique:criteria,code,' . $criterion->id . '|regex:/^[A-Z0-9]+$/',
                'name' => 'required|string|max:255|min:3',
                'description' => 'nullable|string|max:1000',
                'is_active' => 'boolean',
            ], [
                'code.required' => 'Kode kriteria wajib diisi',
                'code.unique' => 'Kode kriteria sudah digunakan',
                'code.regex' => 'Kode kriteria hanya boleh huruf kapital dan angka',
                'name.required' => 'Nama kriteria wajib diisi',
                'name.min' => 'Nama kriteria minimal 3 karakter',
                'description.max' => 'Deskripsi maksimal 1000 karakter',
            ]);

            // ✅ Capture old values SEBELUM transaction
            $oldValues = [
                'code' => $criterion->code,
                'name' => $criterion->name,
                'description' => $criterion->description,
                'is_active' => $criterion->is_active,
            ];

            $validated['is_active'] = $request->has('is_active');

            // ✅ CHECK jika status aktif berubah
            $statusChanged = $oldValues['is_active'] != $validated['is_active'];
            $hasAssessments = $criterion->assessments()->count() > 0;
            $hasComparisons = $criterion->comparisons1()->count() > 0 || $criterion->comparisons2()->count() > 0;

            DB::beginTransaction();

            $criterion->update($validated);

            // ✅ LOG dengan informasi tambahan
            $logDescription = $criterion->name . ' (' . $criterion->code . ')';
            if ($statusChanged) {
                $status = $validated['is_active'] ? 'diaktifkan' : 'dinonaktifkan';
                $affected = [];

                if ($hasAssessments) {
                    $affected[] = $criterion->assessments()->count() . ' penilaian';
                }
                if ($hasComparisons) {
                    $totalComparisons = $criterion->comparisons1()->count() + $criterion->comparisons2()->count();
                    $affected[] = $totalComparisons . ' perbandingan';
                }

                if (!empty($affected)) {
                    $logDescription .= " - Status {$status} (" . implode(', ', $affected) . " akan dikecualikan dari perhitungan)";
                }
            }

            ActivityLogHelper::logUpdate(
                'Criteria',
                $criterion->id,
                $logDescription,
                $oldValues,
                [
                    'code' => $criterion->code,
                    'name' => $criterion->name,
                    'description' => $criterion->description,
                    'is_active' => $criterion->is_active,
                ]
            );

            DB::commit();

            // ✅ Pesan informatif
            $message = 'Kriteria berhasil diperbarui!';
            if ($statusChanged && ($hasAssessments || $hasComparisons)) {
                $status = $validated['is_active'] ? 'diaktifkan' : 'dinonaktifkan';
                $message .= " Status kriteria {$status}. Progress bar akan diperbarui otomatis.";
            }

            return redirect()
                ->route('criteria.index')
                ->with('success', $message);
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

    public function destroy(Criteria $criterion)
    {
        try {
            // ✅ Check constraint SEBELUM beginTransaction
            if ($criterion->comparisons1()->count() > 0 || $criterion->comparisons2()->count() > 0) {
                return redirect()
                    ->back()
                    ->with('warning', 'Kriteria tidak dapat dihapus karena sudah digunakan dalam perbandingan AHP. Hapus perbandingan terlebih dahulu atau nonaktifkan kriteria.');
            }

            if ($criterion->assessments()->count() > 0) {
                return redirect()
                    ->back()
                    ->with('warning', 'Kriteria tidak dapat dihapus karena sudah digunakan dalam penilaian supplier. Hapus penilaian terlebih dahulu atau nonaktifkan kriteria.');
            }

            DB::beginTransaction();

            ActivityLogHelper::logDelete(
                'Criteria',
                $criterion->id,
                $criterion->name . ' (' . $criterion->code . ')',
                [
                    'code' => $criterion->code,
                    'name' => $criterion->name,
                    'description' => $criterion->description,
                ]
            );

            $criterion->delete();

            DB::commit();

            return redirect()
                ->route('criteria.index')
                ->with('success', 'Kriteria berhasil dihapus!');
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus kriteria: ' . $e->getMessage());
        }
    }

    /**
     * Toggle criteria active status
     * ✅ UPDATED: Informasi detail tentang impact pada progress
     */
    public function toggleActive(Criteria $criterion)
    {
        try {
            // ✅ Get data SEBELUM transaction
            $oldStatus = $criterion->is_active;
            $newStatus = !$oldStatus;

            $hasAssessments = $criterion->assessments()->count() > 0;
            $hasComparisons = $criterion->comparisons1()->count() > 0 || $criterion->comparisons2()->count() > 0;

            DB::beginTransaction();

            $criterion->update(['is_active' => $newStatus]);

            $status = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
            $logDescription = $criterion->name . ' (' . $criterion->code . ') - Status ' . $status;

            $affected = [];
            if ($hasAssessments) {
                $affected[] = $criterion->assessments()->count() . ' penilaian';
            }
            if ($hasComparisons) {
                $totalComparisons = $criterion->comparisons1()->count() + $criterion->comparisons2()->count();
                $affected[] = $totalComparisons . ' perbandingan';
            }

            if (!empty($affected)) {
                $impactNote = $newStatus ? 'akan dihitung kembali' : 'dikecualikan dari perhitungan';
                $logDescription .= ' (' . implode(', ', $affected) . ' ' . $impactNote . ')';
            }

            ActivityLogHelper::logUpdate(
                'Criteria',
                $criterion->id,
                $logDescription,
                ['is_active' => $oldStatus],
                ['is_active' => $newStatus]
            );

            DB::commit();

            $message = 'Status kriteria berhasil ' . $status . '!';
            if (!empty($affected)) {
                $impactNote = $newStatus ? 'akan dihitung kembali dalam progress' : 'tidak akan dihitung dalam progress';
                $message .= ' ' . implode(', ', $affected) . ' ' . $impactNote . '.';
            }

            return redirect()
                ->back()
                ->with('success', $message);
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
