@extends('layouts.admin')

@section('title', 'Bandingkan Kriteria')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Perbandingan Kriteria</h1>
        <p class="mt-1 text-sm text-gray-500">
            Tentukan tingkat kepentingan antara dua kriteria
        </p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('criteria-comparisons.store') }}" method="POST">
            @csrf
            
            <input type="hidden" name="criteria_1_id" value="{{ $criteria1->id }}">
            <input type="hidden" name="criteria_2_id" value="{{ $criteria2->id }}">

            <!-- Criteria Comparison Display -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="grid grid-cols-3 gap-4 items-center">
                    <!-- Criteria 1 -->
                    <div class="text-center">
                        <div class="text-sm text-gray-500 mb-2">Kriteria 1</div>
                        <div class="font-bold text-lg text-gray-900">{{ $criteria1->code }}</div>
                        <div class="text-sm text-gray-600 mt-1">{{ $criteria1->name }}</div>
                    </div>

                    <!-- VS -->
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">VS</div>
                    </div>

                    <!-- Criteria 2 -->
                    <div class="text-center">
                        <div class="text-sm text-gray-500 mb-2">Kriteria 2</div>
                        <div class="font-bold text-lg text-gray-900">{{ $criteria2->code }}</div>
                        <div class="text-sm text-gray-600 mt-1">{{ $criteria2->name }}</div>
                    </div>
                </div>
            </div>

            <!-- Question -->
            <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400">
                <p class="text-sm text-blue-900">
                    <i class="fas fa-question-circle mr-2"></i>
                    <strong>Seberapa penting kriteria "{{ $criteria1->name }}" 
                    dibandingkan dengan "{{ $criteria2->name }}"?</strong>
                </p>
            </div>

            <!-- Scale Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Pilih Tingkat Kepentingan <span class="text-red-500">*</span>
                </label>
                
                <div class="space-y-2">
                    @foreach($saaty_scale as $value => $label)
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition
                                      {{ old('value', $comparison?->value ?? '') == $value ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                            <input type="radio" name="value" value="{{ $value }}" 
                                   class="mr-3 text-blue-600"
                                   {{ old('value', $comparison?->value ?? '') == $value ? 'checked' : '' }}
                                   required>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-gray-900">{{ $value }}</span>
                                    <span class="text-sm text-gray-600">{{ $label }}</span>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('value')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan (Opsional)
                </label>
                <textarea name="note" rows="3" 
                          class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Tambahkan alasan atau catatan untuk perbandingan ini...">{{ old('note', $comparison?->note ?? '') }}</textarea>
                @error('note')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center pt-4 border-t">
                <a href="{{ route('criteria-comparisons.index') }}" 
                   class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Simpan Perbandingan
                </button>
            </div>
        </form>
    </div>

    <!-- Saaty Scale Reference -->
    <div class="mt-6 bg-gray-50 rounded-lg p-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            Panduan Skala Saaty (AHP)
        </h3>
        <div class="space-y-2 text-sm text-gray-700">
            <div class="flex justify-between">
                <span class="font-semibold">1:</span>
                <span>Kedua kriteria sama penting</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">3:</span>
                <span>Kriteria pertama sedikit lebih penting</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">5:</span>
                <span>Kriteria pertama jelas lebih penting</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">7:</span>
                <span>Kriteria pertama sangat lebih penting</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">9:</span>
                <span>Kriteria pertama mutlak lebih penting</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span class="font-semibold">2, 4, 6, 8:</span>
                <span>Nilai antara di atas</span>
            </div>
        </div>
    </div>
</div>
@endsection