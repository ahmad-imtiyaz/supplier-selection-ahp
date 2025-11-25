@extends('layouts.admin')

@section('title', 'Tambah Supplier')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Supplier Baru</h1>
        <p class="mt-1 text-sm text-gray-500">
            Isi form berikut untuk menambahkan supplier yang akan dinilai.
        </p>
    </div>

    <!-- Form Card -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form action="{{ route('suppliers.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Kode Supplier -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Supplier <span class="text-red-500">*</span>
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
                           placeholder="Contoh: SUP001">

                    @error('code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Supplier -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Supplier <span class="text-red-500">*</span>
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
                           placeholder="Contoh: PT Sumber Makmur">

                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Alamat -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                    Alamat
                </label>

                <textarea name="address" 
                          id="address" 
                          rows="3"
                          @class([
                              'w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors resize-none',
                              'border-red-300 bg-red-50' => $errors->has('address'),
                              'border-gray-300' => !$errors->has('address'),
                          ])
                          placeholder="Alamat lengkap supplier...">{{ old('address') }}</textarea>

                @error('address')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Telepon -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Telepon
                    </label>

                    <input type="text" 
                           name="phone" 
                           id="phone" 
                           value="{{ old('phone') }}"
                           @class([
                               'w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                               'border-red-300 bg-red-50' => $errors->has('phone'),
                               'border-gray-300' => !$errors->has('phone'),
                           ])
                           placeholder="08123456789">

                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>

                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email') }}"
                           @class([
                               'w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                               'border-red-300 bg-red-50' => $errors->has('email'),
                               'border-gray-300' => !$errors->has('email'),
                           ])
                           placeholder="supplier@example.com">

                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Contact Person -->
            <div>
                <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kontak Person
                </label>

                <input type="text" 
                       name="contact_person" 
                       id="contact_person" 
                       value="{{ old('contact_person') }}"
                       @class([
                           'w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                           'border-red-300 bg-red-50' => $errors->has('contact_person'),
                           'border-gray-300' => !$errors->has('contact_person'),
                       ])
                       placeholder="Nama PIC atau perwakilan">

                @error('contact_person')
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
                          placeholder="Catatan tambahan tentang supplier...">{{ old('description') }}</textarea>

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
                        Aktifkan supplier ini
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Supplier yang aktif dapat dinilai dalam assessment</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                
                <a href="{{ route('suppliers.index') }}"
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
                    Simpan Supplier
                </button>

            </div>
        </form>
    </div>

    <!-- Help Text -->
    <div class="mt-6 bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-2">Tips:</h3>
        <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
            <li>Gunakan kode singkat seperti SUP001, SUP002 untuk identifikasi</li>
            <li>Pastikan data kontak akurat untuk komunikasi</li>
            <li>Isi deskripsi untuk catatan penting tentang supplier</li>
        </ul>
    </div>

</div>
@endsection