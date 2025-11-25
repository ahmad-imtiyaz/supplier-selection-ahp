@extends('layouts.admin')

@section('title', 'Penilaian Supplier')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Penilaian Supplier</h1>
        <p class="text-gray-600 mt-1">Berikan nilai untuk supplier pada kriteria tertentu</p>
    </div>

    <!-- Info Card -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
            <div>
                <h3 class="font-semibold text-blue-900 mb-1">Supplier: {{ $supplier->name }}</h3>
                <h3 class="font-semibold text-blue-900 mb-2">Kriteria: {{ $criteria->name }}</h3>
                <p class="text-sm text-blue-800">{{ $criteria->description }}</p>
                <p class="text-sm text-blue-800 mt-2">
                    <strong>Bobot Kriteria:</strong> {{ number_format($criteria->weight * 100, 2) }}%
                </p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('supplier-assessments.store') }}" method="POST">
            @csrf
            
            <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
            <input type="hidden" name="criteria_id" value="{{ $criteria->id }}">

            <!-- Score Input -->
            <div class="mb-6">
                <label for="score" class="block text-sm font-medium text-gray-700 mb-2">
                    Nilai (0 - 100) <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       name="score" 
                       id="score" 
                       min="0" 
                       max="100" 
                       step="0.01"
                       value="{{ old('score', $assessment->score ?? '') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('score') border-red-500 @enderror"
                       required>
                @error('score')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">Masukkan nilai antara 0 sampai 100</p>
            </div>

            <!-- Score Indicator -->
            <div class="mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600 mb-2">Kategori Nilai:</div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm">Sangat Baik</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">80 - 100</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm">Baik</span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">60 - 79</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm">Cukup</span>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">40 - 59</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm">Kurang</span>
                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">0 - 39</span>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Notes -->
<div class="mb-6">
    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
        Catatan (Opsional)
    </label>
    <textarea name="notes" 
              id="notes" 
              rows="4"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
              placeholder="Tambahkan catatan atau alasan penilaian...">{{ old('notes', $assessment->notes ?? '') }}</textarea>
    @error('notes')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

            <!-- Actions -->
            <div class="flex gap-3">
                <button type="submit" class="flex-1 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>Simpan Penilaian
                </button>
                <a href="{{ route('supplier-assessments.index') }}" 
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection