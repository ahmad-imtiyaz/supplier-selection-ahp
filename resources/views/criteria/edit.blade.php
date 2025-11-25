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
        <form action="{{ route('criteria.update', $criterion) }}" method="POST" class="p-6 space-y-6">
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
                       value="{{ old('code', $criterion->code) }}"
                       @class([
                           'w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500',
                           'border-red-300 bg-red-50' => $errors->has('code'),
                           'border-gray-300' => !$errors->has('code'),
                       ])
                       placeholder="Contoh: C1, C2, C3">

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
                       value="{{ old('name', $criterion->name) }}"
                       @class([
                           'w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500',
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
                              'w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none',
                              'border-red-300 bg-red-50' => $errors->has('description'),
                              'border-gray-300' => !$errors->has('description'),
                          ])
                          placeholder="Jelaskan detail kriteria ini...">{{ old('description', $criterion->description) }}</textarea>

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
                        class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-medium 
                               text-white bg-indigo-600 hover:bg-indigo-700
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                               shadow-sm">
                    Perbarui Kriteria
                </button>

            </div>
        </form>
    </div>

</div>
@endsection