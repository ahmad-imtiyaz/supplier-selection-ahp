@extends('layouts.admin')

@section('title', 'Track Record - ' . $supplier->name)

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('track-records.index') }}"
                   class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Track Record Supplier</h1>
            </div>

            <div class="flex items-center gap-3 ml-9">
                <div class="flex-shrink-0 h-12 w-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <span class="text-indigo-600 font-bold text-lg">
                        {{ strtoupper(substr($supplier->name, 0, 2)) }}
                    </span>
                </div>
                <div>
                    <p class="text-lg font-semibold text-gray-900">{{ $supplier->name }}</p>
                    <p class="text-sm text-gray-500">Kode: {{ $supplier->code }}</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Year Selector -->
            <form method="GET" action="{{ route('track-records.show', $supplier) }}" class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Tahun:</label>
                <select name="year"
                        onchange="this.form.submit()"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>

            <!-- Initialize Button -->
            <form method="POST" action="{{ route('track-records.initialize', $supplier) }}" class="inline-block">
                @csrf
                <input type="hidden" name="year" value="{{ $year }}">
                <button type="submit"
                        onclick="return confirm('Inisialisasi akan membuat slot kosong untuk semua kriteria aktif di 12 bulan. Lanjutkan?')"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700
                               text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Inisialisasi Tahun {{ $year }}
                </button>
            </form>

            <!-- Reset Button -->
            <form method="POST" action="{{ route('track-records.reset', $supplier) }}" class="inline-block">
                @csrf
                <input type="hidden" name="year" value="{{ $year }}">
                <button type="submit"
                        onclick="return confirm('PERINGATAN!\n\nSemua track record tahun {{ $year }} akan dihapus!\nTindakan ini tidak dapat dibatalkan.\n\nLanjutkan?')"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700
                               text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset {{ $year }}
                </button>
            </form>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-gray-700">Progress Pengisian {{ $year }}</h3>
            <span class="text-2xl font-bold text-gray-900">
                {{ number_format($completion['percentage'], 1) }}%
            </span>
        </div>

        <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
            <div class="h-4 rounded-full transition-all duration-500
                        {{ $completion['percentage'] >= 80 ? 'bg-green-500' : ($completion['percentage'] >= 50 ? 'bg-blue-500' : ($completion['percentage'] >= 20 ? 'bg-yellow-500' : 'bg-red-500')) }}"
                 style="width: {{ $completion['percentage'] }}%">
            </div>
        </div>

        <p class="text-sm text-gray-600">
            <span class="font-semibold">{{ $completion['completed'] }}</span> dari
            <span class="font-semibold">{{ $completion['total'] }}</span> record terisi
        </p>
    </div>

    <!-- Mobile Scroll Hint -->
    <div class="block lg:hidden bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded-r-lg">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs text-yellow-800">
                Geser tabel ke kanan untuk melihat semua bulan
            </p>
        </div>
    </div>

    <!-- Track Record Matrix -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
            <table class="w-full divide-y divide-gray-200" style="min-width: 1400px;">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="sticky left-0 z-10 bg-gray-50 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200" style="min-width: 180px;">
                            Kriteria
                        </th>
                        @foreach($months as $month)
                            @php
                                $monthNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                            @endphp
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="min-width: 100px;">
                                {{ $monthNames[$month] }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($criterias as $criteria)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <!-- Criteria Name (Sticky) -->
                        <td class="sticky left-0 z-10 bg-white px-4 py-3 border-r border-gray-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-purple-100 rounded flex items-center justify-center">
                                    <span class="text-purple-600 font-bold text-xs">
                                        {{ $criteria->code }}
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ \Illuminate\Support\Str::limit($criteria->name, 20) }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <!-- Monthly Records -->
                        @foreach($months as $month)
                            @php
                                $record = $trackRecords[$criteria->id][$month] ?? null;
                                $hasContent = $record && $record->hasContent();
                            @endphp

                            <td class="px-3 py-3 text-center">
                                <a href="{{ route('track-records.edit', [
                                        'supplier' => $supplier,
                                        'criteria' => $criteria->id,
                                        'year' => $year,
                                        'month' => $month
                                    ]) }}"
                                   class="inline-flex items-center justify-center w-full h-10 rounded-lg transition-all
                                          {{ $hasContent
                                              ? 'bg-green-100 hover:bg-green-200 text-green-700 border border-green-300'
                                              : 'bg-gray-100 hover:bg-gray-200 text-gray-500 border border-gray-300' }}">

                                    @if($hasContent)
                                        <!-- Check Icon -->
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <!-- Edit Icon -->
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    @endif
                                </a>
                            </td>
                        @endforeach
                    </tr>

                    @empty
                    <!-- Empty State -->
                    <tr>
                        <td colspan="13" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500 text-base font-medium">Belum ada kriteria aktif</p>
                                <p class="text-gray-400 text-sm mt-1">Silakan tambahkan dan aktifkan kriteria terlebih dahulu</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Legend -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="text-sm font-semibold text-gray-700 mb-3">Keterangan:</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-green-100 border border-green-300 rounded flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-green-700" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-sm text-gray-600">Sudah terisi</span>
            </div>

            <div class="flex items-center">
                <div class="w-8 h-8 bg-gray-100 border border-gray-300 rounded flex items-center justify-center mr-3">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <span class="text-sm text-gray-600">Belum terisi (klik untuk mengisi)</span>
            </div>
        </div>
    </div>

</div>

<style>
    /* Sticky column shadow effect */
    .sticky {
        box-shadow: 2px 0 4px rgba(0,0,0,0.05);
    }

    /* Custom scrollbar - Desktop */
    @media (min-width: 1024px) {
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

    /* Mobile scroll */
    @media (max-width: 1023px) {
        .overflow-x-auto {
            overflow-x: scroll !important;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection
