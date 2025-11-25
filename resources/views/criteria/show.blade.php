@extends('layouts.admin')

@section('title', 'Detail Kriteria')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6 flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Kriteria</h1>
            <p class="mt-1 text-sm text-gray-500">Informasi lengkap kriteria penilaian</p>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('criteria.edit', $criterion) }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 
                      text-white text-sm font-medium rounded-lg shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            
            <a href="{{ route('criteria.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg 
                      text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 shadow-sm">
                Kembali
            </a>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6 space-y-6">
            
            <!-- Kode & Status -->
            <div class="flex items-start justify-between pb-6 border-b border-gray-200">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $criterion->name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">Kode: <span class="font-semibold text-gray-700">{{ $criterion->code }}</span></p>
                </div>
                
                <div>
                    @if($criterion->is_active)
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

            <!-- Deskripsi -->
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-2">Deskripsi</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    @if($criterion->description)
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $criterion->description }}</p>
                    @else
                        <p class="text-sm text-gray-400 italic">Tidak ada deskripsi</p>
                    @endif
                </div>
            </div>

            <!-- Bobot AHP -->
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-2">Bobot AHP</h3>
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-4">
                    @if($criterion->weight > 0)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-3xl font-bold text-indigo-600">
                                    {{ number_format($criterion->weight * 100, 2) }}%
                                </p>
                                <p class="text-xs text-gray-600 mt-1">Prioritas kriteria dalam pengambilan keputusan</p>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-1/2">
                                <div class="h-4 bg-white rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full" 
                                         style="width: {{ $criterion->weight * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm text-gray-500 mt-2">Bobot belum dihitung</p>
                            <p class="text-xs text-gray-400 mt-1">Lakukan perhitungan AHP untuk mendapatkan bobot</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Metadata -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-6 border-t border-gray-200">
                <div>
                    <h4 class="text-xs font-medium text-gray-500 uppercase mb-1">Dibuat Pada</h4>
                    <p class="text-sm text-gray-900">{{ $criterion->created_at->format('d F Y, H:i') }}</p>
                </div>
                
                <div>
                    <h4 class="text-xs font-medium text-gray-500 uppercase mb-1">Terakhir Diperbarui</h4>
                    <p class="text-sm text-gray-900">{{ $criterion->updated_at->format('d F Y, H:i') }}</p>
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
                    Menghapus kriteria akan menghapus semua data terkait termasuk perbandingan AHP. Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="mt-4">
                    <form action="{{ route('criteria.destroy', $criterion) }}" method="POST" 
                          onsubmit="return confirm('PERINGATAN!\n\nAnda yakin ingin menghapus kriteria ini?\nSemua data terkait akan ikut terhapus!\n\nKetik OK untuk konfirmasi.')">
                        @csrf
                        @method('DELETE')
                        
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg 
                                       text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Kriteria
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection