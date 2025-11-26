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

    <!-- AHP Scale Reference Card -->
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-5 mb-6 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Skala Perbandingan AHP
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="bg-white rounded-lg p-3 border border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Sama penting</span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-bold rounded">1</span>
                </div>
            </div>
            <div class="bg-white rounded-lg p-3 border border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Sedikit lebih penting</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-bold rounded">3</span>
                </div>
            </div>
            <div class="bg-white rounded-lg p-3 border border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Jelas lebih penting</span>
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-sm font-bold rounded">5</span>
                </div>
            </div>
            <div class="bg-white rounded-lg p-3 border border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Sangat lebih penting</span>
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm font-bold rounded">7</span>
                </div>
            </div>
            <div class="bg-white rounded-lg p-3 border border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Mutlak lebih penting</span>
                    <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-bold rounded">9</span>
                </div>
            </div>
            <div class="bg-white rounded-lg p-3 border border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Nilai antara</span>
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-bold rounded">2,4,6,8</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('criteria-comparisons.store') }}" 
              method="POST"
              x-data="{
                  value: '{{ old('value', '1') }}',
                  submitting: false,
                  getValueDescription() {
                      const val = parseFloat(this.value);
                      if (val === 1) return 'Sama penting';
                      if (val === 2) return 'Sedikit menuju lebih penting';
                      if (val === 3) return 'Sedikit lebih penting';
                      if (val === 4) return 'Menuju jelas lebih penting';
                      if (val === 5) return 'Jelas lebih penting';
                      if (val === 6) return 'Menuju sangat lebih penting';
                      if (val === 7) return 'Sangat lebih penting';
                      if (val === 8) return 'Menuju mutlak lebih penting';
                      if (val === 9) return 'Mutlak lebih penting';
                      return '';
                  },
                  getValueColor() {
                      const val = parseFloat(this.value);
                      if (val === 1) return 'gray';
                      if (val <= 3) return 'blue';
                      if (val <= 5) return 'indigo';
                      if (val <= 7) return 'purple';
                      return 'red';
                  },
                  async submitForm(e) {
                      this.submitting = true;
                      showLoading();
                  }
              }"
              @submit="submitForm">
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
            <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg">
                <p class="text-sm text-blue-900">
                    <svg class="inline w-5 h-5 mr-2 -mt-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <strong>Seberapa penting kriteria "{{ $criteria1->name }}" 
                    dibandingkan dengan "{{ $criteria2->name }}"?</strong>
                </p>
            </div>

            <!-- Range Slider with Radio Buttons -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Pilih Nilai Perbandingan <span class="text-red-500">*</span>
                </label>

                <!-- Range Slider -->
                <div class="mb-4">
                    <input type="range" 
                           name="value" 
                           id="value"
                           min="1" 
                           max="9" 
                           step="1"
                           x-model="value"
                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                    
                    <!-- Scale Markers -->
                    <div class="flex justify-between text-xs text-gray-500 mt-2 px-1">
                        <span>1</span>
                        <span>2</span>
                        <span>3</span>
                        <span>4</span>
                        <span>5</span>
                        <span>6</span>
                        <span>7</span>
                        <span>8</span>
                        <span>9</span>
                    </div>
                </div>

                <!-- Value Display -->
                <div x-show="value" 
                     class="mt-4 p-4 rounded-lg border-2 transition-all duration-300"
                     :class="{
                         'bg-gray-50 border-gray-300': getValueColor() === 'gray',
                         'bg-blue-50 border-blue-300': getValueColor() === 'blue',
                         'bg-indigo-50 border-indigo-300': getValueColor() === 'indigo',
                         'bg-purple-50 border-purple-300': getValueColor() === 'purple',
                         'bg-red-50 border-red-300': getValueColor() === 'red'
                     }"
                     x-transition>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nilai yang dipilih:</p>
                            <p class="text-2xl font-bold"
                               :class="{
                                   'text-gray-700': getValueColor() === 'gray',
                                   'text-blue-700': getValueColor() === 'blue',
                                   'text-indigo-700': getValueColor() === 'indigo',
                                   'text-purple-700': getValueColor() === 'purple',
                                   'text-red-700': getValueColor() === 'red'
                               }"
                               x-text="value"></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600 mb-1">Interpretasi:</p>
                            <p class="text-sm font-semibold"
                               :class="{
                                   'text-gray-700': getValueColor() === 'gray',
                                   'text-blue-700': getValueColor() === 'blue',
                                   'text-indigo-700': getValueColor() === 'indigo',
                                   'text-purple-700': getValueColor() === 'purple',
                                   'text-red-700': getValueColor() === 'red'
                               }"
                               x-text="getValueDescription()"></p>
                        </div>
                    </div>
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
    <textarea name="note" 
              rows="3" 
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Tambahkan alasan atau catatan untuk perbandingan ini...">{{ old('note', $comparison->note ?? '') }}</textarea>

    @error('note')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>


            <!-- Actions -->
            <div class="flex justify-between items-center pt-4 border-t">
                <a href="{{ route('criteria-comparisons.index') }}" 
                   class="inline-flex items-center px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
                <button type="submit" 
                        :disabled="submitting"
                        :class="{ 'opacity-50 cursor-not-allowed': submitting }"
                        class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    
                    <!-- Loading Spinner -->
                    <svg x-show="submitting" 
                         class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" 
                         fill="none" 
                         viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>

                    <svg x-show="!submitting" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    
                    <span x-text="submitting ? 'Menyimpan...' : 'Simpan Perbandingan'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection