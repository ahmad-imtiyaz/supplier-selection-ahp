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
        <form action="{{ route('supplier-assessments.store') }}" 
              method="POST"
              x-data="{
                  score: '{{ old('score', $assessment->score ?? '') }}',
                  notes: '{{ old('notes', $assessment->notes ?? '') }}',
                  submitting: false,
                  errors: {
                      score: '',
                      notes: ''
                  },
                  getScoreCategory() {
                      const val = parseFloat(this.score);
                      if (isNaN(val)) return '';
                      if (val >= 80) return 'Sangat Baik';
                      if (val >= 60) return 'Baik';
                      if (val >= 40) return 'Cukup';
                      return 'Kurang';
                  },
                  getScoreColor() {
                      const val = parseFloat(this.score);
                      if (isNaN(val)) return 'gray';
                      if (val >= 80) return 'green';
                      if (val >= 60) return 'blue';
                      if (val >= 40) return 'yellow';
                      return 'red';
                  },
                  validateScore() {
                      const val = parseFloat(this.score);
                      if (!this.score) {
                          this.errors.score = 'Nilai wajib diisi';
                      } else if (isNaN(val)) {
                          this.errors.score = 'Nilai harus berupa angka';
                      } else if (val < 0) {
                          this.errors.score = 'Nilai minimal 0';
                      } else if (val > 100) {
                          this.errors.score = 'Nilai maksimal 100';
                      } else {
                          this.errors.score = '';
                      }
                  },
                  validateNotes() {
                      if (this.notes && this.notes.length > 500) {
                          this.errors.notes = 'Catatan maksimal 500 karakter';
                      } else {
                          this.errors.notes = '';
                      }
                  },
                  async submitForm(e) {
                      this.validateScore();
                      this.validateNotes();
                      
                      if (this.errors.score || this.errors.notes) {
                          e.preventDefault();
                          showToast('error', 'Mohon perbaiki error pada form!');
                          return;
                      }
                      
                      this.submitting = true;
                      showLoading();
                  }
              }"
              @submit="submitForm">
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
                       x-model="score"
                       @input="validateScore"
                       @blur="validateScore"
                       :class="{
                           'border-red-300 bg-red-50': errors.score || {{ $errors->has('score') ? 'true' : 'false' }},
                           'border-green-300 bg-green-50': score && !errors.score && !{{ $errors->has('score') ? 'true' : 'false' }},
                           'border-gray-300': !score || (!errors.score && !{{ $errors->has('score') ? 'true' : 'false' }})
                       }"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                       placeholder="Masukkan nilai 0-100">

                <!-- Frontend Error -->
                <p x-show="errors.score" 
                   x-text="errors.score" 
                   class="text-red-500 text-sm mt-1"
                   x-transition></p>

                <!-- Backend Error -->
                @error('score')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <!-- Real-time Category Display -->
                <div x-show="score && !errors.score" 
                     class="mt-3 p-3 rounded-lg transition-all duration-300"
                     :class="{
                         'bg-green-50 border border-green-200': getScoreColor() === 'green',
                         'bg-blue-50 border border-blue-200': getScoreColor() === 'blue',
                         'bg-yellow-50 border border-yellow-200': getScoreColor() === 'yellow',
                         'bg-red-50 border border-red-200': getScoreColor() === 'red'
                     }"
                     x-transition>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" 
                             :class="{
                                 'text-green-600': getScoreColor() === 'green',
                                 'text-blue-600': getScoreColor() === 'blue',
                                 'text-yellow-600': getScoreColor() === 'yellow',
                                 'text-red-600': getScoreColor() === 'red'
                             }"
                             fill="currentColor" 
                             viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-semibold"
                              :class="{
                                  'text-green-800': getScoreColor() === 'green',
                                  'text-blue-800': getScoreColor() === 'blue',
                                  'text-yellow-800': getScoreColor() === 'yellow',
                                  'text-red-800': getScoreColor() === 'red'
                              }">
                            Kategori: <span x-text="getScoreCategory()"></span>
                        </span>
                    </div>
                </div>

                <p class="text-gray-500 text-sm mt-2">Masukkan nilai antara 0 sampai 100</p>
            </div>

            <!-- Score Indicator -->
            <div class="mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600 mb-2">Panduan Kategori Nilai:</div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between p-2 rounded transition-colors"
                             :class="{ 'bg-green-100': score >= 80 && score <= 100 }">
                            <span class="text-sm">Sangat Baik</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">80 - 100</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded transition-colors"
                             :class="{ 'bg-blue-100': score >= 60 && score < 80 }">
                            <span class="text-sm">Baik</span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">60 - 79</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded transition-colors"
                             :class="{ 'bg-yellow-100': score >= 40 && score < 60 }">
                            <span class="text-sm">Cukup</span>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">40 - 59</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded transition-colors"
                             :class="{ 'bg-red-100': score >= 0 && score < 40 }">
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
                          x-model="notes"
                          @input="validateNotes"
                          :class="{
                              'border-red-300 bg-red-50': errors.notes,
                              'border-green-300 bg-green-50': notes && !errors.notes && notes.length > 0,
                              'border-gray-300': !notes || !errors.notes
                          }"
                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors resize-none"
                          placeholder="Tambahkan catatan atau alasan penilaian..."
                          maxlength="500"></textarea>

                <!-- Frontend Error -->
                <p x-show="errors.notes" 
                   x-text="errors.notes" 
                   class="text-red-500 text-sm mt-1"
                   x-transition></p>

                <!-- Backend Error -->
                @error('notes')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            <!-- Character Counter -->
            <p class="mt-2 text-xs text-gray-500">
                <span x-text="notes.length"></span>/500 karakter
                <span x-show="notes.length > 450" class="text-orange-600 font-semibold">
                    (<span x-text="500 - notes.length"></span> tersisa)
                </span>
            </p>
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
            <button type="submit" 
                    :disabled="submitting"
                    :class="{ 'opacity-50 cursor-not-allowed': submitting }"
                    class="flex-1 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                
                <svg x-show="submitting" 
                     class="animate-spin inline-block -ml-1 mr-2 h-4 w-4 text-white" 
                     fill="none" 
                     viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>

                <i x-show="!submitting" class="fas fa-save mr-2"></i>
                <span x-text="submitting ? 'Menyimpan...' : 'Simpan Penilaian'"></span>
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
