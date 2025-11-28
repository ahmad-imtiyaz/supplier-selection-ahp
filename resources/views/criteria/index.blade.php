@extends('layouts.admin')

@section('title', 'Daftar Kriteria')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Kriteria</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola kriteria penilaian supplier</p>
        </div>

        <a href="{{ route('criteria.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 
                  text-white text-sm font-medium rounded-md shadow-sm transition-colors w-full sm:w-auto justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Kriteria
        </a>
    </div>

    <!-- Mobile Scroll Hint -->
    <div class="block sm:hidden bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded-r-lg">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs text-yellow-800">
                Geser tabel ke kanan untuk melihat semua kolom
            </p>
        </div>
    </div>

    <!-- Table Container with Horizontal Scroll -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <!-- Wrapper untuk scroll horizontal - PENTING: overflow-x-auto di sini -->
        <div class="overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
            <table class="w-full divide-y divide-gray-200" style="min-width: 800px;">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Kode
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[150px]">
                            Nama Kriteria
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[200px]">
                            Deskripsi
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Bobot
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Status
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($criteria as $criterion)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <!-- Kode -->
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $criterion->code }}
                            </span>
                        </td>

                        <!-- Nama -->
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">
                                {{ $criterion->name }}
                            </span>
                        </td>

                        <!-- Deskripsi -->
                        <td class="px-4 py-4">
                            <span class="text-sm text-gray-600 line-clamp-2">
                                {{ \Illuminate\Support\Str::limit($criterion->description, 50) }}
                            </span>
                        </td>

                        <!-- Bobot -->
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($criterion->weight > 0)
                                <span class="text-sm font-medium text-gray-900">
                                    {{ number_format($criterion->weight * 100, 2) }}%
                                </span>
                            @else
                                <span class="text-sm text-gray-400 italic">Belum dihitung</span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($criterion->is_active)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                             bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                             bg-red-100 text-red-800">
                                    Nonaktif
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <!-- View Button -->
                                <a href="{{ route('criteria.show', $criterion) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors p-1"
                                   title="Lihat Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                <!-- Edit Button -->
                                <a href="{{ route('criteria.edit', $criterion) }}" 
                                   class="text-yellow-600 hover:text-yellow-800 transition-colors p-1"
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                <!-- Delete Button -->
                                <form id="delete-form-{{ $criterion->id }}" 
                                      action="{{ route('criteria.destroy', $criterion) }}" 
                                      method="POST" 
                                      class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            onclick="confirmDelete('delete-form-{{ $criterion->id }}', '{{ addslashes($criterion->name) }}')"
                                            class="text-red-600 hover:text-red-800 transition-colors p-1"
                                            title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    @empty
                    <!-- Empty State -->
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500 text-base font-medium">Belum ada kriteria</p>
                                <p class="text-gray-400 text-sm mt-1">Silakan tambah kriteria baru untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Box Jika Sudah Ada 3+ Kriteria -->
    @if($criteria->count() >= 3)
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-800">
                    Anda sudah memiliki <strong>{{ $criteria->count() }}</strong> kriteria.
                    Selanjutnya, lakukan <strong>Perbandingan Berpasangan (AHP)</strong> untuk menghitung bobot prioritas.
                </p>
            </div>
        </div>
    </div>
    @endif

</div>

<style>
    /* Custom scrollbar untuk tampilan yang lebih baik - Desktop */
    @media (min-width: 640px) {
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    }
    
    /* Mobile: pastikan scroll berfungsi */
    @media (max-width: 639px) {
        .overflow-x-auto {
            overflow-x: scroll !important;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection