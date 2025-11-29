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

            // 🔥 LOG ACTIVITY
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
            DB::rollBack();

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

            DB::beginTransaction();

            // 🔥 CAPTURE OLD VALUES
            $oldValues = [
                'code' => $criterion->code,
                'name' => $criterion->name,
                'description' => $criterion->description,
                'is_active' => $criterion->is_active,
            ];

            $validated['is_active'] = $request->has('is_active');

            $criterion->update($validated);

            // 🔥 LOG ACTIVITY
            ActivityLogHelper::logUpdate(
                'Criteria',
                $criterion->id,
                $criterion->name . ' (' . $criterion->code . ')',
                $oldValues,
                [
                    'code' => $criterion->code,
                    'name' => $criterion->name,
                    'description' => $criterion->description,
                    'is_active' => $criterion->is_active,
                ]
            );

            DB::commit();

            return redirect()
                ->route('criteria.index')
                ->with('success', 'Kriteria berhasil diperbarui!');
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

    public function destroy(Criteria $criterion)
    {
        try {
            // Check if criteria has comparisons
            if ($criterion->comparisons1()->count() > 0 || $criterion->comparisons2()->count() > 0) {
                return redirect()
                    ->back()
                    ->with('warning', 'Kriteria tidak dapat dihapus karena sudah digunakan dalam perbandingan AHP. Hapus perbandingan terlebih dahulu.');
            }

            // Check if criteria has assessments
            if ($criterion->assessments()->count() > 0) {
                return redirect()
                    ->back()
                    ->with('warning', 'Kriteria tidak dapat dihapus karena sudah digunakan dalam penilaian supplier. Hapus penilaian terlebih dahulu.');
            }

            DB::beginTransaction();

            // 🔥 LOG ACTIVITY SEBELUM DELETE
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
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus kriteria: ' . $e->getMessage());
        }
    }
}
