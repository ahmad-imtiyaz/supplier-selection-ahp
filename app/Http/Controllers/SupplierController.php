<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
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

            Supplier::create($validated);

            DB::commit();

            return redirect()
                ->route('suppliers.index')
                ->with('success', 'Supplier berhasil ditambahkan!');
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

            DB::beginTransaction();

            $validated['is_active'] = $request->has('is_active');

            $supplier->update($validated);

            DB::commit();

            return redirect()
                ->route('suppliers.index')
                ->with('success', 'Supplier berhasil diperbarui!');
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

    public function destroy(Supplier $supplier)
    {
        try {
            // Check if supplier has assessments
            if ($supplier->assessments()->count() > 0) {
                return redirect()
                    ->back()
                    ->with('warning', 'Supplier tidak dapat dihapus karena sudah memiliki penilaian. Hapus penilaian terlebih dahulu.');
            }

            DB::beginTransaction();

            $supplier->delete();

            DB::commit();

            return redirect()
                ->route('suppliers.index')
                ->with('success', 'Supplier berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus supplier: ' . $e->getMessage());
        }
    }
}
