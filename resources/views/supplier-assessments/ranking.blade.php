@extends('layouts.admin')

@section('title', 'Ranking Supplier')

@section('content')
<div class="space-y-6">

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Ranking Supplier</h1>
        <p class="text-gray-600 mt-1">Hasil perhitungan peringkat supplier berdasarkan AHP</p>
    </div>

    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
        <a href="{{ route('supplier-assessments.index') }}" 
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-center">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>

        <!-- Export Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-center w-full sm:w-auto flex items-center justify-center">
                <i class="fas fa-download mr-2"></i>Export
                <i class="fas fa-chevron-down ml-2 text-xs"></i>
            </button>

            <div x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                
                <div class="py-1">

                    <a href="{{ route('supplier-assessments.export.pdf') }}" 
                       class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <i class="fas fa-file-pdf text-red-500 mr-3 w-5"></i>
                        <div>
                            <div class="font-medium">Export PDF</div>
                            <div class="text-xs text-gray-500">Ranking lengkap</div>
                        </div>
                    </a>

                    <a href="{{ route('supplier-assessments.export.excel') }}" 
                       class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <i class="fas fa-file-excel text-green-500 mr-3 w-5"></i>
                        <div>
                            <div class="font-medium">Export Excel</div>
                            <div class="text-xs text-gray-500">Format XLSX</div>
                        </div>
                    </a>

                    <a href="{{ route('supplier-assessments.export.detail') }}" 
                       class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition border-t border-gray-100">
                        <i class="fas fa-file-alt text-blue-500 mr-3 w-5"></i>
                        <div>
                            <div class="font-medium">Detail Perhitungan</div>
                            <div class="text-xs text-gray-500">PDF lengkap dengan formula</div>
                        </div>
                    </a>

                </div>
            </div>
        </div>

        <button onclick="window.print()" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center">
            <i class="fas fa-print mr-2"></i>Cetak
        </button>
    </div>
</div>

    <!-- Podium -->
    @if(count($rankings) >= 3)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <!-- Rank 2 -->
        <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg p-6 text-center order-2 md:order-1">
            <div class="text-6xl mb-2">🥈</div>
            <div class="text-xl font-bold text-gray-700">{{ $rankings[1]['supplier']->name }}</div>
            <div class="text-3xl font-bold text-gray-600 mt-2">{{ number_format($rankings[1]['percentage'], 2) }}%</div>
            <div class="text-sm text-gray-500 mt-1">Peringkat #2</div>
        </div>

        <!-- Rank 1 -->
        <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-lg p-6 text-center order-1 md:order-2">
            <div class="text-6xl mb-2">🥇</div>
            <div class="text-xl font-bold text-yellow-800">{{ $rankings[0]['supplier']->name }}</div>
            <div class="text-4xl font-bold text-yellow-700 mt-2">{{ number_format($rankings[0]['percentage'], 2) }}%</div>
            <div class="text-sm text-yellow-600 mt-1">PEMENANG</div>
        </div>

        <!-- Rank 3 -->
        <div class="bg-gradient-to-br from-orange-100 to-orange-200 rounded-lg p-6 text-center order-3 md:order-3">
            <div class="text-6xl mb-2">🥉</div>
            <div class="text-xl font-bold text-orange-700">{{ $rankings[2]['supplier']->name }}</div>
            <div class="text-3xl font-bold text-orange-600 mt-2">{{ number_format($rankings[2]['percentage'], 2) }}%</div>
            <div class="text-sm text-orange-500 mt-1">Peringkat #3</div>
        </div>

    </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-semibold">Rank</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold">Supplier</th>

                        @foreach($criterias as $criteria)
                        <th class="px-4 py-3 text-center text-xs font-semibold whitespace-nowrap">
                            {{ $criteria->code }}
                            <div class="text-[10px] text-gray-400">W: {{ number_format($criteria->weight, 4) }}</div>
                        </th>
                        @endforeach

                        <th class="px-4 py-3 text-center text-xs font-semibold">Total</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($rankings as $ranking)
                    <tr class="hover:bg-gray-50 {{ $ranking['rank'] <= 3 ? 'bg-yellow-50' : '' }}">

                        <!-- Rank -->
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-full font-bold
                                {{ $ranking['rank'] == 1 ? 'bg-yellow-100 text-yellow-800' : 
                                   ($ranking['rank'] == 2 ? 'bg-gray-100 text-gray-700' : 
                                   ($ranking['rank'] == 3 ? 'bg-orange-100 text-orange-700' : 'bg-blue-50 text-blue-600')) }}">
                                {{ $ranking['rank'] }}
                            </span>
                        </td>

                        <!-- Supplier -->
                        <td class="px-4 py-3">
                            <div class="font-semibold text-gray-900">{{ $ranking['supplier']->name }}</div>
                            <div class="text-xs text-gray-500">{{ $ranking['supplier']->code }}</div>

                            @if(!$ranking['has_all_scores'])
                            <div class="inline-block px-2 py-1 text-[11px] bg-red-100 text-red-700 rounded mt-1">
                                Data Tidak Lengkap
                            </div>
                            @endif
                        </td>

                        <!-- Criteria -->
                        @foreach($criterias as $criteria)
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            @php $criteriaScore = $ranking['criteria_scores'][$criteria->id] ?? null; @endphp
                            @if($criteriaScore)
                                <div>{{ number_format($criteriaScore['raw_score'], 1) }}</div>
                                <div class="text-xs text-blue-600 font-semibold">
                                    {{ number_format($criteriaScore['weighted_score'], 4) }}
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        @endforeach

                        <!-- Total -->
                        <td class="px-4 py-3 text-center">
                            <span class="font-bold text-gray-900">{{ number_format($ranking['percentage'], 2) }}%</span>
                            <div class="text-xs text-gray-500">({{ number_format($ranking['total_score'], 4) }})</div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <!-- Legend -->
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-2">Keterangan:</h3>
        <div class="text-sm text-gray-600 space-y-1">
            <p>• <strong>Nilai Kriteria:</strong> Nilai mentah (0–100)</p>
            <p>• <strong>Nilai Tertimbang:</strong> Setelah dikali bobot</p>
            <p>• <strong>Total Skor:</strong> Jumlah nilai tertimbang</p>
            <p>• <strong>W (Weight):</strong> Bobot AHP tiap kriteria</p>
        </div>
    </div>

</div>

<style>
@media print {
    .no-print { display: none !important; }
}
</style>
@endsection
