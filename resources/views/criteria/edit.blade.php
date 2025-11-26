@extends('layouts.admin')

@section('title', 'Edit Kriteria')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Kriteria</h1>
        <p class="mt-1 text-sm text-gray-500">
            Perbarui informasi kriteria penilaian.
        </p>
    </div>

    <!-- Form Card -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form action="{{ route('criteria.update', $criterion) }}" 
              method="POST" 
              class="p-6 space-y-6"
              x-data="{
                  code: '{{ old('code', $criterion->code) }}',
                  name: '{{ old('name', $criterion->name) }}',
                  description: '{{ old('description', $criterion->description) }}',
                  submitting: false,
                  errors: {
                      code: '',
                      name: '',
                      description: ''
                  },
                  validateCode() {
                      this.code = this.code.toUpperCase().replace(/[^A-Z0-9]/g, '');
                      if (!this.code) {
                          this.errors.code = 'Kode kriteria wajib diisi';
                      } else if (this.code.length < 2) {
                          this.errors.code = 'Kode minimal 2 karakter';
                      } else if (this.code.length > 10) {
                          this.errors.code = 'Kode maksimal 10 karakter';
                      } else {
                          this.errors.code = '';
                      }
                  },
                  validateName() {
                      if (!this.name) {
                          this.errors.name = 'Nama kriteria wajib diisi';
                      } else if (this.name.length < 3) {
                          this.errors.name = 'Nama minimal 3 karakter';
                      } else if (this.name.length > 255) {
                          this.errors.name = 'Nama maksimal 255 karakter';
                      } else {
                          this.errors.name = '';
                      }
                  },
                  validateDescription() {
                      if (this.description && this.description.length > 1000) {
                          this.errors.description = 'Deskripsi maksimal 1000 karakter';
                      } else {
                          this.errors.description = '';
                      }
                  },
                  async submitForm(e) {
                      this.validateCode();
                      this.validateName();
                      this.validateDescription();
                      
                      if (this.errors.code || this.errors.name || this.errors.description) {
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
            @method('PUT')

            <!-- Kode Kriteria -->
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                    Kode Kriteria <span class="text-red-500">*</span>
                </label>

                <input type="text" 
                       name="code" 
                       id="code" 
                       x-model="code"
                       @input="validateCode"
                       @blur="validateCode"
                       :class="{
                           'border-red-300 bg-red-50': errors.code || {{ $errors->has('code') ? 'true' : 'false' }},
                           'border-green-300 bg-green-50': code && !errors.code && !{{ $errors->has('code') ? 'true' : 'false' }},
                           'border-gray-300': !code || (!errors.code && !{{ $errors->has('code') ? 'true' : 'false' }})
                       }"
                       class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors uppercase"
                       placeholder="Contoh: C1, C2, C3"
                       maxlength="10">

                <!-- Frontend Error -->
                <p x-show="errors.code" 
                   x-text="errors.code" 
                   class="mt-2 text-sm text-red-600"
                   x-transition></p>

                <!-- Backend Error -->
                @error('code')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <p class="mt-2 text-xs text-gray-500">Kode unik untuk mengidentifikasi kriteria</p>
            </div>

            <!-- Nama Kriteria -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kriteria <span class="text-red-500">*</span>
                </label>

                <input type="text" 
                       name="name" 
                       id="name" 
                       x-model="name"
                       @input="validateName"
                       @blur="validateName"
                       :class="{
                           'border-red-300 bg-red-50': errors.name || {{ $errors->has('name') ? 'true' : 'false' }},
                           'border-green-300 bg-green-50': name && !errors.name && !{{ $errors->has('name') ? 'true' : 'false' }},
                           'border-gray-300': !name || (!errors.name && !{{ $errors->has('name') ? 'true' : 'false' }})
                       }"
                       class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
                       placeholder="Contoh: Harga, Kualitas Produk"
                       maxlength="255">

                <!-- Frontend Error -->
                <p x-show="errors.name" 
                   x-text="errors.name" 
                   class="mt-2 text-sm text-red-600"
                   x-transition></p>

                <!-- Backend Error -->
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Character Counter -->
                <p class="mt-2 text-xs text-gray-500">
                    <span x-text="name.length"></span>/255 karakter
                </p>
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi
                </label>

                <textarea name="description" 
                          id="description" 
                          rows="4"
                          x-model="description"
                          @input="validateDescription"
                          :class="{
                              'border-red-300 bg-red-50': errors.description,
                              'border-green-300 bg-green-50': description && !errors.description && description.length > 0,
                              'border-gray-300': !description || !errors.description
                          }"
                          class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors resize-none"
                          placeholder="Jelaskan detail kriteria ini..."
                          maxlength="1000"></textarea>

                <!-- Frontend Error -->
                <p x-show="errors.description" 
                   x-text="errors.description" 
                   class="mt-2 text-sm text-red-600"
                   x-transition></p>

                <!-- Character Counter -->
                <p class="mt-2 text-xs text-gray-500">
                    <span x-text="description.length"></span>/1000 karakter
                    <span x-show="description.length > 900" class="text-orange-600 font-semibold">
                        (<span x-text="1000 - description.length"></span> tersisa)
                    </span>
                </p>
            </div>

            <!-- Status Aktif -->
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active"
                           value="1"
                           {{ old('is_active', $criterion->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="ml-3">
                    <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">
                        Aktifkan kriteria ini
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Kriteria yang aktif akan digunakan dalam penilaian</p>
                </div>
            </div>

            <!-- Bobot Info (Read Only) -->
            @if($criterion->weight > 0)
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800">
                            Bobot AHP saat ini: <strong>{{ number_format($criterion->weight * 100, 2) }}%</strong>
                        </p>
                        <p class="text-xs text-blue-600 mt-1">
                            Bobot akan diperbarui saat dilakukan perhitungan AHP ulang
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                
                <a href="{{ route('criteria.index') }}"
                   class="inline-flex items-center px-5 py-2.5 border border-gray-300 rounded-lg 
                          text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 
                          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                          shadow-sm">
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

                    <span x-text="submitting ? 'Menyimpan...' : 'Perbarui Kriteria'"></span>
                </button>

            </div>
        </form>
    </div>

</div>
@endsection