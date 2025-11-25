<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:criteria,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['weight'] = 0;

        Criteria::create($validated);

        return redirect()
            ->route('criteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan!');
    }

    // ✅ PERHATIKAN: Parameter diganti dari $criteria ke $criterion
    public function show(Criteria $criterion)
    {
        return view('criteria.show', compact('criterion'));
    }

    // ✅ PERHATIKAN: Parameter diganti dari $criteria ke $criterion
    public function edit(Criteria $criterion)
    {
        return view('criteria.edit', compact('criterion'));
    }

    // ✅ PERHATIKAN: Parameter diganti dari $criteria ke $criterion
    public function update(Request $request, Criteria $criterion)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:criteria,code,' . $criterion->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $criterion->update($validated);

        return redirect()
            ->route('criteria.index')
            ->with('success', 'Kriteria berhasil diperbarui!');
    }

    // ✅ PERHATIKAN: Parameter diganti dari $criteria ke $criterion
    public function destroy(Criteria $criterion)
    {
        $criterion->delete();

        return redirect()
            ->route('criteria.index')
            ->with('success', 'Kriteria berhasil dihapus!');
    }
}
