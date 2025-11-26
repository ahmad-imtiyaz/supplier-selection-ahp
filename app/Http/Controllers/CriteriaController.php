<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
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

            Criteria::create($validated);

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

            $validated['is_active'] = $request->has('is_active');

            $criterion->update($validated);

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
