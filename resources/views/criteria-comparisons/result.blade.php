@extends('layouts.admin')

@section('title', 'Hasil Perhitungan Bobot AHP')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Hasil Perhitungan Bobot Kriteria</h1>
            <p class="mt-1 text-sm text-gray-500">
                Bobot kriteria dihitung menggunakan metode AHP (Analytical Hierarchy Process)
            </p>
        </div>
        <a href="{{ route('criteria-comparisons.index') }}" 
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Consistency Check -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-check-circle mr-2"></i>Status Konsistensi
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
            
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-sm text-gray-500 mb-1">Status</div>
                @if($result['is_consistent'])
                    <div class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                        <i class="fas fa-check-circle mr-2"></i>Konsisten
                    </div>
                @else
                    <div class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                        <i class="fas fa-exclamation-circle mr-2"></i>Tidak Konsisten
                    </div>
                @endif
            </div>
        </div>

        @if(!$result['is_consistent'])
            <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-400">
                <p class="text-sm text-yellow-700">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Perhatian:</strong> Consistency Ratio melebihi 0.1, menunjukkan bahwa perbandingan mungkin tidak konsisten. 
                    Pertimbangkan untuk meninjau ulang perbandingan kriteria Anda.
                </p>
            </div>
        @endif
    </div>

    <!-- Weights Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-balance-scale mr-2"></i>Bobot Kriteria
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kriteria</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Bobot</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Persentase</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Ranking</th>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm font-medium">
                                    {{ $item['criterion']->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $item['criterion']->name }}
                                </div>
                                @if($item['criterion']->description)
                                    <div class="text-sm text-gray-500 mt-1">
                                        {{ Str::limit($item['criterion']->description, 60) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ number_format($item['weight'], 4) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center">
                                    <div class="w-24">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" 
                                                 style="width: {{ $item['weight'] * 100 }}%"></div>
                                        </div>
                                    </div>
                                    <span class="ml-2 text-sm font-medium text-gray-900">
                                        {{ number_format($item['weight'] * 100, 2) }}%
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($index == 0)
                                    <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                        <i class="fas fa-crown mr-1"></i>Tertinggi
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
                        <td colspan="3" class="px-6 py-4 text-sm font-semibold text-gray-900 text-right">
                            Total:
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-bold text-gray-900">
                                {{ number_format(array_sum($result['weights']), 4) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
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
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-table mr-2"></i>Matriks Perbandingan Berpasangan
            </h2>
        </div>
        
        <div class="overflow-x-auto p-6">
            <table class="min-w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">
                            Kriteria
                        </th>
                        @foreach($matrixData['criterias'] as $criterion)
                            <th class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 text-center">
                                {{ $criterion->code }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($matrixData['criterias'] as $i => $criteria1)
                        <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                            <td class="border border-gray-300 px-4 py-2 font-medium text-sm">
                                {{ $criteria1->code }}
                            </td>
                            @foreach($matrixData['criterias'] as $j => $criteria2)
                                <td class="border border-gray-300 px-4 py-2 text-center text-sm
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
    <div class="flex justify-between items-center">
        <div class="text-sm text-gray-500">
            <i class="fas fa-info-circle mr-1"></i>
            Bobot akan otomatis tersimpan dan digunakan untuk penilaian supplier
        </div>
        <div class="flex gap-2">
            <form action="{{ route('criteria-comparisons.calculate') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-redo mr-2"></i>Hitung Ulang
                </button>
            </form>
            <a href="{{ route('criteria.index') }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-list mr-2"></i>Lihat Semua Kriteria
            </a>
        </div>
    </div>
</div>
@endsection