@extends('layouts.admin')

@section('title', 'Detail Supplier')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6 flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Supplier</h1>
            <p class="mt-1 text-sm text-gray-500">Informasi lengkap supplier</p>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('suppliers.edit', $supplier) }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 
                      text-white text-sm font-medium rounded-lg shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            
            <a href="{{ route('suppliers.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg 
                      text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 shadow-sm">
                Kembali
            </a>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6 space-y-6">
            
            <!-- Header Info -->
            <div class="flex items-start justify-between pb-6 border-b border-gray-200">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $supplier->name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">Kode: <span class="font-semibold text-gray-700">{{ $supplier->code }}</span></p>
                </div>
                
                <div>
                    @if($supplier->is_active)
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full 
                                     bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Aktif
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full 
                                     bg-red-100 text-red-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Nonaktif
                        </span>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-3">Informasi Kontak</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <!-- Alamat -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Alamat</p>
                                @if($supplier->address)
                                    <p class="text-sm text-gray-900">{{ $supplier->address }}</p>
                                @else
                                    <p class="text-sm text-gray-400 italic">Tidak ada data</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Telepon -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Telepon</p>
                                @if($supplier->phone)
                                    <p class="text-sm text-gray-900">{{ $supplier->phone }}</p>
                                @else
                                    <p class="text-sm text-gray-400 italic">Tidak ada data</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Email</p>
                                @if($supplier->email)
                                    <p class="text-sm text-gray-900">{{ $supplier->email }}</p>
                                @else
                                    <p class="text-sm text-gray-400 italic">Tidak ada data</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Contact Person -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Kontak Person</p>
                                @if($supplier->contact_person)
                                    <p class="text-sm text-gray-900">{{ $supplier->contact_person }}</p>
                                @else
                                    <p class="text-sm text-gray-400 italic">Tidak ada data</p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-2">Deskripsi</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    @if($supplier->description)
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $supplier->description }}</p>
                    @else
                        <p class="text-sm text-gray-400 italic">Tidak ada deskripsi</p>
                    @endif
                </div>
            </div>

            <!-- Metadata -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-6 border-t border-gray-200">
                <div>
                    <h4 class="text-xs font-medium text-gray-500 uppercase mb-1">Dibuat Pada</h4>
                    <p class="text-sm text-gray-900">{{ $supplier->created_at->format('d F Y, H:i') }}</p>
                </div>
                
                <div>
                    <h4 class="text-xs font-medium text-gray-500 uppercase mb-1">Terakhir Diperbarui</h4>
                    <p class="text-sm text-gray-900">{{ $supplier->updated_at->format('d F Y, H:i') }}</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Delete Section -->
    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-red-800">Zona Berbahaya</h3>
                <p class="text-sm text-red-700 mt-1">
                    Menghapus supplier akan menghapus semua data penilaian terkait. Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="mt-4">
                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" 
                          onsubmit="return confirm('PERINGATAN!\n\nAnda yakin ingin menghapus supplier ini?\nSemua data penilaian terkait akan ikut terhapus!\n\nKetik OK untuk konfirmasi.')">
                        @csrf
                        @method('DELETE')
                        
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg 
                                       text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Supplier
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection