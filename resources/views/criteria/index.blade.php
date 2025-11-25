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
                  text-white text-sm font-medium rounded-md shadow-sm transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Kriteria
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kode
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Kriteria
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Bobot
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                            <span class="text-sm text-gray-600">
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

                        <!-- Aksi -->
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('criteria.show', $criterion) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                                    Detail
                                </a>

                                <a href="{{ route('criteria.edit', $criterion) }}"
                                   class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition-colors">
                                    Edit
                                </a>

                                <form action="{{ route('criteria.destroy', $criterion) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Yakin ingin menghapus kriteria ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                                        Hapus
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
@endsection