@extends('layouts.admin')

@section('title', 'Hasil Perhitungan Bobot AHP')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900">Hasil Perhitungan Bobot Kriteria</h1>
            <p class="mt-1 text-sm text-gray-500">
                Bobot kriteria dihitung menggunakan metode AHP (Analytical Hierarchy Process)
            </p>
        </div>
        <a href="{{ route('criteria-comparisons.index') }}" 
           class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors w-full sm:w-auto">
            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span class="text-sm font-medium">Kembali</span>
        </a>
    </div>

    <!-- Consistency Check -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            <svg class="w-5 h-5 inline-block mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Status Konsistensi
        </h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-sm text-gray-500 mb-1">Consistency Ratio (CR)</div>
                <div class="text-2xl font-bold {{ $result['is_consistent'] ? 'text-green-600' : 'text-red-600' }}">
                    {{ number_format($result['consistency_ratio'], 4) }}
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-sm text-gray-500 mb-1">Threshold</div>
                <div class="text-2xl font-bold text-gray-900">0.1000</div>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4 sm:col-span-2 lg:col-span-1">
                <div class="text-sm text-gray-500 mb-1">Status</div>
                @if($result['is_consistent'])
                    <div class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Konsisten
                    </div>
                @else
                    <div class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Tidak Konsisten
                    </div>
                @endif
            </div>
        </div>

        @if(!$result['is_consistent'])
            <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r">
                <p class="text-sm text-yellow-700">
                    <svg class="w-4 h-4 inline-block mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <strong>Perhatian:</strong> Consistency Ratio melebihi 0.1, menunjukkan bahwa perbandingan mungkin tidak konsisten. 
                    Pertimbangkan untuk meninjau ulang perbandingan kriteria Anda.
                </p>
            </div>
        @endif
    </div>

    <!-- Mobile Scroll Hint -->
    <div class="block sm:hidden bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r">
        <p class="text-sm text-blue-700 flex items-start">
            <svg class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Geser tabel ke kanan untuk melihat data lengkap</span>
        </p>
    </div>

    <!-- Weights Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                <svg class="w-5 h-5 inline-block mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                </svg>
                Bobot Kriteria
            </h2>
        </div>
        
        <div class="overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
            <table class="min-w-full divide-y divide-gray-200" style="min-width: 800px;">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">No</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Kode</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="min-width: 200px;">Nama Kriteria</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Bobot</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="min-width: 180px;">Persentase</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Ranking</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $sortedCriteria = $result['criterias']->map(function($criterion, $index) use ($result) {
                            return [
                                'criterion' => $criterion,
                                'weight' => $result['weights'][$index]
                            ];
                        })->sortByDesc('weight')->values();
                    @endphp

                    @foreach($sortedCriteria as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm font-medium">
                                    {{ $item['criterion']->code }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 line-clamp-2">
                                    {{ $item['criterion']->name }}
                                </div>
                                @if($item['criterion']->description)
                                    <div class="text-sm text-gray-500 mt-1 line-clamp-1">
                                        {{ Str::limit($item['criterion']->description, 60) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ number_format($item['weight'], 4) }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex items-center justify-center">
                                    <div class="w-16 sm:w-24 flex-shrink-0">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" 
                                                 style="width: {{ $item['weight'] * 100 }}%"></div>
                                        </div>
                                    </div>
                                    <span class="ml-2 text-sm font-medium text-gray-900 whitespace-nowrap">
                                        {{ number_format($item['weight'] * 100, 2) }}%
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap">
                                @if($index == 0)
                                    <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                        <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        Tertinggi
                                    </span>
                                @else
                                    <span class="text-sm text-gray-600">#{{ $index + 1 }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-4 sm:px-6 py-4 text-sm font-semibold text-gray-900 text-right">
                            Total:
                        </td>
                        <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-900">
                                {{ number_format(array_sum($result['weights']), 4) }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-900">100.00%</span>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Comparison Matrix -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                <svg class="w-5 h-5 inline-block mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Matriks Perbandingan Berpasangan
            </h2>
        </div>
        
        <div class="overflow-x-auto p-4 sm:p-6" style="-webkit-overflow-scrolling: touch;">
            <table class="min-w-full border-collapse border border-gray-300" style="min-width: 600px;">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-3 sm:px-4 py-2 text-sm font-medium text-gray-700 whitespace-nowrap">
                            Kriteria
                        </th>
                        @foreach($matrixData['criterias'] as $criterion)
                            <th class="border border-gray-300 px-3 sm:px-4 py-2 text-sm font-medium text-gray-700 text-center whitespace-nowrap">
                                {{ $criterion->code }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($matrixData['criterias'] as $i => $criteria1)
                        <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                            <td class="border border-gray-300 px-3 sm:px-4 py-2 font-medium text-sm whitespace-nowrap">
                                {{ $criteria1->code }}
                            </td>
                            @foreach($matrixData['criterias'] as $j => $criteria2)
                                <td class="border border-gray-300 px-3 sm:px-4 py-2 text-center text-sm whitespace-nowrap
                                    {{ $i == $j ? 'bg-blue-50 font-bold' : '' }}">
                                    {{ number_format($matrixData['matrix'][$i][$j], 2) }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
        <div class="text-sm text-gray-500 flex items-start">
            <svg class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Bobot akan otomatis tersimpan dan digunakan untuk penilaian supplier</span>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            <form action="{{ route('criteria-comparisons.calculate') }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span class="text-sm font-medium">Hitung Ulang</span>
                </button>
            </form>
            <a href="{{ route('criteria.index') }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                <span class="text-sm font-medium">Lihat Semua Kriteria</span>
            </a>
        </div>
    </div>
</div>
@endsection