@extends('layouts.admin')

@section('title', 'Tambah Kriteria')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Kriteria Baru</h1>
        <p class="mt-1 text-sm text-gray-500">
            Isi form berikut untuk menambahkan kriteria penilaian supplier.
        </p>
    </div>

    <!-- Form Card -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form action="{{ route('criteria.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Kode Kriteria -->
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                    Kode Kriteria <span class="text-red-500">*</span>
                </label>

                <input type="text" 
                       name="code" 
                       id="code" 
                       value="{{ old('code') }}"
                       @class([
                           'w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                           'border-red-300 bg-red-50' => $errors->has('code'),
                           'border-gray-300' => !$errors->has('code'),
                       ])
                       placeholder="Contoh: C1, C2, C3">

                @error('code')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <p class="mt-2 text-xs text-gray-500">
                    <svg class="inline w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Kode unik untuk mengidentifikasi kriteria
                </p>
            </div>

            <!-- Nama Kriteria -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kriteria <span class="text-red-500">*</span>
                </label>

                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name') }}"
                       @class([
                           'w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                           'border-red-300 bg-red-50' => $errors->has('name'),
                           'border-gray-300' => !$errors->has('name'),
                       ])
                       placeholder="Contoh: Harga, Kualitas Produk">

                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi
                </label>

                <textarea name="description" 
                          id="description" 
                          rows="4"
                          @class([
                              'w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors resize-none',
                              'border-red-300 bg-red-50' => $errors->has('description'),
                              'border-gray-300' => !$errors->has('description'),
                          ])
                          placeholder="Jelaskan detail kriteria ini...">{{ old('description') }}</textarea>

                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Aktif -->
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active"
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="ml-3">
                    <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">
                        Aktifkan kriteria ini
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Kriteria yang aktif akan digunakan dalam penilaian</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                
                <a href="{{ route('criteria.index') }}"
                   class="inline-flex items-center px-5 py-2.5 border border-gray-300 rounded-lg 
                          text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 
                          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                          transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>

                <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-medium 
                               text-white bg-indigo-600 hover:bg-indigo-700
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                               transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Kriteria
                </button>

            </div>
        </form>
    </div>

    <!-- Help Text -->
    <div class="mt-6 bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-2">Tips:</h3>
        <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
            <li>Gunakan kode singkat seperti C1, C2, C3 untuk memudahkan identifikasi</li>
            <li>Nama kriteria sebaiknya jelas dan mudah dipahami</li>
            <li>Deskripsi dapat membantu menjelaskan maksud dari kriteria</li>
        </ul>
    </div>

</div>
@endsection