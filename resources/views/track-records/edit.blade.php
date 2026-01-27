@extends('layouts.admin')

@section('title', 'Track Record - ' . $monthName[$month] . ' ' . $year)

@section('content')
<div class="max-w-5xl mx-auto">

    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-3">
            <a href="{{ route('track-records.show', ['supplier' => $supplier, 'year' => $year]) }}"
               class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Track Record Supplier</h1>
        </div>

        <div class="ml-9">
            <p class="text-sm text-gray-500">
                Periode: <span class="font-semibold text-gray-700">{{ $monthName[$month] }} {{ $year }}</span>
            </p>
            <p class="text-sm text-gray-500">
                Supplier: <span class="font-semibold text-gray-700">{{ $supplier->name }} ({{ $supplier->code }})</span>
            </p>
            <p class="text-sm text-gray-500">
                Kriteria: <span class="font-semibold text-gray-700">{{ $criteria->name }} ({{ $criteria->code }})</span>
            </p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form action="{{ route('track-records.update', $supplier) }}"
              method="POST"
              id="trackRecordForm"
              class="p-6 space-y-6"
              x-data="{
                  description: '{{ old('description', $trackRecord->description ?? '') }}',
                  recommendedScore: '{{ old('recommended_score', $trackRecord->recommended_score ?? '') }}',
                  notes: '{{ old('notes', $trackRecord->notes ?? '') }}',
                  submitting: false,
                  errors: {
                      description: '',
                      recommendedScore: '',
                      notes: ''
                  },
                  validateDescription() {
                      if (this.description && this.description.length > 2000) {
                          this.errors.description = 'Deskripsi maksimal 2000 karakter';
                      } else {
                          this.errors.description = '';
                      }
                  },
                  validateScore() {
                      if (this.recommendedScore) {
                          const score = parseFloat(this.recommendedScore);
                          if (isNaN(score)) {
                              this.errors.recommendedScore = 'Skor harus berupa angka';
                          } else if (score < 0) {
                              this.errors.recommendedScore = 'Skor minimal 0';
                          } else if (score > 100) {
                              this.errors.recommendedScore = 'Skor maksimal 100';
                          } else {
                              this.errors.recommendedScore = '';
                          }
                      } else {
                          this.errors.recommendedScore = '';
                      }
                  },
                  validateNotes() {
                      if (this.notes && this.notes.length > 1000) {
                          this.errors.notes = 'Catatan maksimal 1000 karakter';
                      } else {
                          this.errors.notes = '';
                      }
                  },
                  async submitForm(e) {
                      this.validateDescription();
                      this.validateScore();
                      this.validateNotes();

                      if (this.errors.description || this.errors.recommendedScore || this.errors.notes) {
                          e.preventDefault();
                          showToast('error', 'Mohon perbaiki error pada form!');
                          return;
                      }

                      this.submitting = true;
                      showLoading();
                  },
                  getScoreBadgeColor() {
                      const score = parseFloat(this.recommendedScore);
                      if (isNaN(score)) return 'gray';
                      if (score >= 80) return 'green';
                      if (score >= 60) return 'blue';
                      if (score >= 40) return 'yellow';
                      return 'red';
                  }
              }"
              @submit="submitForm">
            @csrf
            @method('PUT')

            <!-- Hidden Fields -->
            <input type="hidden" name="criteria_id" value="{{ $criteria->id }}">
            <input type="hidden" name="year" value="{{ $year }}">
            <input type="hidden" name="month" value="{{ $month }}">

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800">
                            <strong>Track Record</strong> mencatat performa supplier untuk periode ini.
                            Isi deskripsi dengan detail kondisi aktual, dan berikan skor rekomendasi jika diperlukan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Deskripsi Track Record -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Performa
                    <span class="text-gray-500 font-normal">(Opsional)</span>
                </label>

                <textarea name="description"
                          id="description"
                          rows="6"
                          x-model="description"
                          @input="validateDescription"
                          :class="{
                              'border-red-300 bg-red-50': errors.description,
                              'border-green-300 bg-green-50': description && !errors.description && description.length > 0,
                              'border-gray-300': !description || !errors.description
                          }"
                          class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors resize-none"
                         placeholder="Masukkan deskripsi penilaian supplier sesuai kriteria.
Jelaskan kondisi, penyebab, dan evaluasi singkat secara objektif."
maxlength="2000"></textarea>

                <!-- Frontend Error -->
                <p x-show="errors.description"
                   x-text="errors.description"
                   class="mt-2 text-sm text-red-600"
                   x-transition></p>

                <!-- Backend Error -->
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Character Counter -->
                <div class="mt-2 flex items-center justify-between">
                    <p class="text-xs text-gray-500">
                        <svg class="inline w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Jelaskan kondisi aktual performa supplier untuk periode ini
                    </p>
                    <p class="text-xs text-gray-500">
                        <span x-text="description.length"></span>/2000 karakter
                        <span x-show="description.length > 1800" class="text-orange-600 font-semibold ml-1">
                            (<span x-text="2000 - description.length"></span> tersisa)
                        </span>
                    </p>
                </div>
            </div>

            <!-- Skor Rekomendasi -->
            <div>
                <label for="recommended_score" class="block text-sm font-medium text-gray-700 mb-2">
                    Skor Rekomendasi (0-100)
                    <span class="text-gray-500 font-normal">(Opsional)</span>
                </label>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Input Score -->
                    <div>
                        <input type="number"
                               name="recommended_score"
                               id="recommended_score"
                               step="0.01"
                               min="0"
                               max="100"
                               x-model="recommendedScore"
                               @input="validateScore"
                               :class="{
                                   'border-red-300 bg-red-50': errors.recommendedScore,
                                   'border-green-300 bg-green-50': recommendedScore && !errors.recommendedScore,
                                   'border-gray-300': !recommendedScore || !errors.recommendedScore
                               }"
                               class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
                               placeholder="Contoh: 85.5">

                        <!-- Frontend Error -->
                        <p x-show="errors.recommendedScore"
                           x-text="errors.recommendedScore"
                           class="mt-2 text-sm text-red-600"
                           x-transition></p>

                        <!-- Backend Error -->
                        @error('recommended_score')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-2 text-xs text-gray-500">
                            Nilai antara 0-100 untuk merepresentasikan performa
                        </p>
                    </div>

                    <!-- Score Visualization -->
                    <div x-show="recommendedScore" x-transition class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Preview Skor:</span>
                            <span class="text-2xl font-bold"
                                  :class="{
                                      'text-green-600': getScoreBadgeColor() === 'green',
                                      'text-blue-600': getScoreBadgeColor() === 'blue',
                                      'text-yellow-600': getScoreBadgeColor() === 'yellow',
                                      'text-red-600': getScoreBadgeColor() === 'red',
                                      'text-gray-600': getScoreBadgeColor() === 'gray'
                                  }"
                                  x-text="parseFloat(recommendedScore).toFixed(2)"></span>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full transition-all duration-300"
                                 :class="{
                                     'bg-green-500': getScoreBadgeColor() === 'green',
                                     'bg-blue-500': getScoreBadgeColor() === 'blue',
                                     'bg-yellow-500': getScoreBadgeColor() === 'yellow',
                                     'bg-red-500': getScoreBadgeColor() === 'red',
                                     'bg-gray-500': getScoreBadgeColor() === 'gray'
                                 }"
                                 :style="`width: ${Math.min(100, Math.max(0, parseFloat(recommendedScore) || 0))}%`">
                            </div>
                        </div>

                        <div class="mt-2 flex justify-between text-xs text-gray-500">
                            <span>0</span>
                            <span>50</span>
                            <span>100</span>
                        </div>
                    </div>
                </div>

                <!-- Score Guide -->
                <div class="mt-4 bg-gray-50 rounded-lg p-4">
                    <h4 class="text-xs font-semibold text-gray-700 mb-2">Panduan Skor:</h4>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 text-xs">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded mr-2"></div>
                            <span class="text-gray-600">80-100: Sangat Baik</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded mr-2"></div>
                            <span class="text-gray-600">60-79: Baik</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded mr-2"></div>
                            <span class="text-gray-600">40-59: Cukup</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded mr-2"></div>
                            <span class="text-gray-600">0-39: Kurang</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan Tambahan -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan Tambahan
                    <span class="text-gray-500 font-normal">(Opsional)</span>
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
                          class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors resize-none"
                          placeholder="Catatan internal, rekomendasi tindakan, atau informasi tambahan lainnya..."
                          maxlength="1000"></textarea>

                <!-- Frontend Error -->
                <p x-show="errors.notes"
                   x-text="errors.notes"
                   class="mt-2 text-sm text-red-600"
                   x-transition></p>

                <!-- Backend Error -->
                @error('notes')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Character Counter -->
                <p class="mt-2 text-xs text-gray-500 text-right">
                    <span x-text="notes.length"></span>/1000 karakter
                    <span x-show="notes.length > 900" class="text-orange-600 font-semibold">
                        (<span x-text="1000 - notes.length"></span> tersisa)
                    </span>
                </p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <!-- Placeholder untuk delete button (akan ada di luar form) -->
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('track-records.show', ['supplier' => $supplier, 'year' => $year]) }}"
                       class="inline-flex items-center px-5 py-2.5 border border-gray-300 rounded-lg
                              text-sm font-medium text-gray-700 bg-white hover:bg-gray-50
                              focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                              shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </a>

                    <button type="submit"
                            :disabled="submitting"
                            :class="{ 'opacity-50 cursor-not-allowed': submitting }"
                            class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-medium
                                   text-white bg-indigo-600 hover:bg-indigo-700
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                                   shadow-sm">

                        <!-- Loading Spinner -->
                        <svg x-show="submitting"
                             class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                             fill="none"
                             viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>

                        <!-- Check Icon -->
                        <svg x-show="!submitting"
                             class="w-4 h-4 mr-2"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>

                        <span x-text="submitting ? 'Menyimpan...' : 'Simpan Track Record'"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- 🔥 DELETE FORM - OUTSIDE main form to avoid nested form issue -->
    @if(isset($trackRecord) && $trackRecord->exists && !empty($trackRecord->id) && $trackRecord->hasContent())
    <div class="mt-4 bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-gray-900">Hapus Track Record</h3>
                    <div class="mt-2 text-sm text-gray-600">
                        <p>Menghapus track record akan menghilangkan semua data yang telah diisi untuk periode ini. Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="mt-4">
                        <form action="{{ route('track-records.destroy', $trackRecord->id) }}"
                              method="POST"
                              onsubmit="return confirm('⚠️ PERINGATAN!\n\nYakin ingin menghapus track record ini?\n\nData yang dihapus tidak dapat dikembalikan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg
                                           text-sm font-medium text-white bg-red-600 hover:bg-red-700
                                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500
                                           shadow-sm transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Track Record
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tips -->
    <div class="mt-6 bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-2">Tips Pengisian:</h3>
        <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
            <li>Deskripsi sebaiknya mencakup kondisi aktual, data kuantitatif jika ada, dan perbandingan dengan periode sebelumnya</li>
            <li>Skor rekomendasi dapat digunakan untuk memberikan penilaian objektif berdasarkan data yang dikumpulkan</li>
            <li>Catatan tambahan bisa berisi rekomendasi tindakan, warning, atau informasi yang perlu diperhatikan</li>
            <li>Data ini akan berguna untuk analisis tren dan evaluasi supplier secara menyeluruh</li>
        </ul>
    </div>

</div>
@endsection
