<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Helpers\ActivityLogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->get();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:50|unique:suppliers,code|regex:/^[A-Z0-9-]+$/',
                'name' => 'required|string|max:255|min:3',
                'address' => 'nullable|string|max:500',
                'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
                'email' => 'nullable|email|max:255',
                'contact_person' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'is_active' => 'boolean',
            ], [
                'code.required' => 'Kode supplier wajib diisi',
                'code.unique' => 'Kode supplier sudah digunakan',
                'code.regex' => 'Kode supplier hanya boleh huruf kapital, angka, dan tanda hubung',
                'name.required' => 'Nama supplier wajib diisi',
                'name.min' => 'Nama supplier minimal 3 karakter',
                'phone.regex' => 'Format nomor telepon tidak valid',
                'email.email' => 'Format email tidak valid',
            ]);

            DB::beginTransaction();

            $validated['is_active'] = $request->has('is_active');
            $supplier = Supplier::create($validated);

            ActivityLogHelper::logCreate(
                'Supplier',
                $supplier->id,
                $supplier->name . ' (' . $supplier->code . ')',
                [
                    'code' => $supplier->code,
                    'name' => $supplier->name,
                    'address' => $supplier->address,
                    'phone' => $supplier->phone,
                    'email' => $supplier->email,
                    'contact_person' => $supplier->contact_person,
                    'is_active' => $supplier->is_active,
                ]
            );

            DB::commit();

            return redirect()
                ->route('suppliers.index')
                ->with('success', 'Supplier berhasil ditambahkan!');
        } catch (ValidationException $e) {
            // ✅ Validation error terjadi SEBELUM beginTransaction
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Validasi gagal! Periksa kembali form Anda.');
        } catch (\Exception $e) {
            // ✅ Safe rollback - cek dulu apakah ada transaksi
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:50|unique:suppliers,code,' . $supplier->id . '|regex:/^[A-Z0-9-]+$/',
                'name' => 'required|string|max:255|min:3',
                'address' => 'nullable|string|max:500',
                'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
                'email' => 'nullable|email|max:255',
                'contact_person' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'is_active' => 'boolean',
            ], [
                'code.required' => 'Kode supplier wajib diisi',
                'code.unique' => 'Kode supplier sudah digunakan',
                'code.regex' => 'Kode supplier hanya boleh huruf kapital, angka, dan tanda hubung',
                'name.required' => 'Nama supplier wajib diisi',
                'name.min' => 'Nama supplier minimal 3 karakter',
                'phone.regex' => 'Format nomor telepon tidak valid',
                'email.email' => 'Format email tidak valid',
            ]);

            // ✅ Capture old values SEBELUM beginTransaction
            $oldValues = [
                'code' => $supplier->code,
                'name' => $supplier->name,
                'address' => $supplier->address,
                'phone' => $supplier->phone,
                'email' => $supplier->email,
                'contact_person' => $supplier->contact_person,
                'is_active' => $supplier->is_active,
            ];

            $validated['is_active'] = $request->has('is_active');

            // ✅ CHECK jika status aktif berubah dan ada penilaian
            $statusChanged = $oldValues['is_active'] != $validated['is_active'];
            $hasAssessments = $supplier->assessments()->count() > 0;

            DB::beginTransaction();

            $supplier->update($validated);

            // ✅ LOG dengan informasi tambahan jika status berubah
            $logDescription = $supplier->name . ' (' . $supplier->code . ')';
            if ($statusChanged && $hasAssessments) {
                $status = $validated['is_active'] ? 'diaktifkan' : 'dinonaktifkan';
                $assessmentCount = $supplier->assessments()->count();
                $logDescription .= " - Status {$status} ({$assessmentCount} penilaian terpengaruh)";
            }

            ActivityLogHelper::logUpdate(
                'Supplier',
                $supplier->id,
                $logDescription,
                $oldValues,
                [
                    'code' => $supplier->code,
                    'name' => $supplier->name,
                    'address' => $supplier->address,
                    'phone' => $supplier->phone,
                    'email' => $supplier->email,
                    'contact_person' => $supplier->contact_person,
                    'is_active' => $supplier->is_active,
                ]
            );

            DB::commit();

            // ✅ Pesan yang lebih informatif
            $message = 'Supplier berhasil diperbarui!';
            if ($statusChanged && $hasAssessments) {
                $status = $validated['is_active'] ? 'diaktifkan' : 'dinonaktifkan';
                $message .= " Status supplier {$status}. Progress penilaian akan diperbarui.";
            }

            return redirect()
                ->route('suppliers.index')
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

    public function destroy(Supplier $supplier)
    {
        try {
            // ✅ Check constraint SEBELUM beginTransaction
            if ($supplier->assessments()->count() > 0) {
                return redirect()
                    ->back()
                    ->with('warning', 'Supplier tidak dapat dihapus karena sudah memiliki penilaian. Hapus penilaian terlebih dahulu.');
            }

            DB::beginTransaction();

            ActivityLogHelper::logDelete(
                'Supplier',
                $supplier->id,
                $supplier->name . ' (' . $supplier->code . ')',
                [
                    'code' => $supplier->code,
                    'name' => $supplier->name,
                    'address' => $supplier->address,
                    'phone' => $supplier->phone,
                    'email' => $supplier->email,
                ]
            );

            $supplier->delete();

            DB::commit();

            return redirect()
                ->route('suppliers.index')
                ->with('success', 'Supplier berhasil dihapus!');
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus supplier: ' . $e->getMessage());
        }
    }


    /**
     * Toggle supplier active status (OPTIONAL - untuk quick toggle)
     */
    public function toggleActive(Supplier $supplier)
    {
        try {
            $oldStatus = $supplier->is_active;
            $newStatus = !$oldStatus;
            $hasAssessments = $supplier->assessments()->count() > 0;

            DB::beginTransaction();

            $supplier->update(['is_active' => $newStatus]);

            // Log dengan informasi assessment
            $status = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
            $logDescription = $supplier->name . ' (' . $supplier->code . ') - Status ' . $status;

            if ($hasAssessments) {
                $assessmentCount = $supplier->assessments()->count();
                $logDescription .= " ({$assessmentCount} penilaian terpengaruh)";
            }

            ActivityLogHelper::logUpdate(
                'Supplier',
                $supplier->id,
                $logDescription,
                ['is_active' => $oldStatus],
                ['is_active' => $newStatus]
            );

            DB::commit();

            $message = 'Status supplier berhasil ' . $status . '!';
            if ($hasAssessments) {
                $message .= ' Progress penilaian akan diperbarui.';
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
