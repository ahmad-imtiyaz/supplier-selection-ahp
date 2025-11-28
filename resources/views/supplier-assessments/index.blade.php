@extends('layouts.admin')

@section('title', 'Penilaian Supplier')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900">Penilaian Supplier</h1>
            <p class="text-gray-600 mt-1 text-sm">Berikan nilai untuk setiap supplier berdasarkan kriteria yang ada</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 lg:flex-shrink-0">
            <a href="{{ route('supplier-assessments.ranking') }}" 
               class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                Lihat Ranking
            </a>
            <form action="{{ route('supplier-assessments.reset') }}" method="POST" 
                  onsubmit="return confirm('Apakah Anda yakin ingin mereset semua penilaian?')" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                    </svg>
                    Reset Semua
                </button>
            </form>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-medium text-gray-700">Progress Penilaian</span>
            <span class="text-sm font-medium text-gray-700">
                {{ $progress['completed'] }} / {{ $progress['total'] }}
            </span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" 
                 style="width: {{ $progress['percentage'] }}%"></div>
        </div>
        <p class="text-xs text-gray-500 mt-1">
            {{ number_format($progress['percentage'], 1) }}% selesai
        </p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-start">
        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="text-sm">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-start">
        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span class="text-sm">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Info Box -->
    @if($criterias->isEmpty())
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg flex items-start">
        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <span class="text-sm">
            Belum ada kriteria aktif. Silakan tambahkan kriteria terlebih dahulu di menu <a href="{{ route('criteria.index') }}" class="underline font-semibold">Kriteria</a>.
        </span>
    </div>
    @endif

    @if($suppliers->isEmpty())
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg flex items-start">
        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <span class="text-sm">
            Belum ada supplier aktif. Silakan tambahkan supplier terlebih dahulu di menu <a href="{{ route('suppliers.index') }}" class="underline font-semibold">Supplier</a>.
        </span>
    </div>
    @endif

    <!-- Mobile Scroll Hint -->
    @if(!$criterias->isEmpty() && !$suppliers->isEmpty())
    <div class="block lg:hidden bg-blue-50 border-l-4 border-blue-400 p-3 rounded-r-lg">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-blue-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs text-blue-800">
                Geser tabel ke kanan untuk melihat semua kriteria
            </p>
        </div>
    </div>
    @endif

    <!-- Assessment Matrix -->
    @if(!$criterias->isEmpty() && !$suppliers->isEmpty())
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
            <table class="w-full divide-y divide-gray-200" style="min-width: 800px;">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Supplier
                        </th>
                        @foreach($criterias as $criteria)
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[140px]">
                            <div class="font-semibold">{{ $criteria->code }}</div>
                            <div class="text-gray-400 font-normal normal-case text-xs mt-1 line-clamp-2">{{ $criteria->name }}</div>
                            <div class="text-xs text-blue-600 font-semibold mt-1">
                                Bobot: {{ number_format($criteria->weight * 100, 2) }}%
                            </div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($suppliers as $supplier)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900 text-sm">{{ $supplier->code }}</div>
                            <div class="text-xs text-gray-500 line-clamp-1">{{ $supplier->name }}</div>
                        </td>
                        @foreach($criterias as $criteria)
                        <td class="px-4 sm:px-6 py-4 text-center">
                            @php
                                $assessment = $assessments[$supplier->id][$criteria->id] ?? null;
                            @endphp
                            
                            @if($assessment)
                            <div class="space-y-2">
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $assessment->score >= 80 ? 'bg-green-100 text-green-800' : 
                                       ($assessment->score >= 60 ? 'bg-blue-100 text-blue-800' : 
                                       ($assessment->score >= 40 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                    {{ number_format($assessment->score, 1) }}
                                </div>
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('supplier-assessments.create', ['supplier' => $supplier->id, 'criteria' => $criteria->id]) }}" 
                                       class="text-blue-600 hover:text-blue-800 p-1" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('supplier-assessments.destroy', $assessment->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Hapus penilaian ini?')" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @else
                            <a href="{{ route('supplier-assessments.create', ['supplier' => $supplier->id, 'criteria' => $criteria->id]) }}" 
                               class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-600 rounded-lg hover:bg-blue-100 hover:text-blue-600 transition text-xs font-medium">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                </svg>
                                Nilai
                            </a>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Legend -->
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Keterangan Nilai:</h3>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 text-sm">
            <div class="flex items-center">
                <span class="inline-block w-4 h-4 bg-green-100 border border-green-200 rounded mr-2 flex-shrink-0"></span>
                <span class="text-gray-600 text-xs sm:text-sm">Sangat Baik (≥80)</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-4 h-4 bg-blue-100 border border-blue-200 rounded mr-2 flex-shrink-0"></span>
                <span class="text-gray-600 text-xs sm:text-sm">Baik (60-79)</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-4 h-4 bg-yellow-100 border border-yellow-200 rounded mr-2 flex-shrink-0"></span>
                <span class="text-gray-600 text-xs sm:text-sm">Cukup (40-59)</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-4 h-4 bg-red-100 border border-red-200 rounded mr-2 flex-shrink-0"></span>
                <span class="text-gray-600 text-xs sm:text-sm">Kurang (<40)</span>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection