@extends('layouts.admin')

@section('title', 'Track Record Supplier')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Track Record Supplier</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola rekam jejak performa supplier per bulan untuk setiap kriteria</p>
        </div>

        <!-- 🆕 FIXED: Year Selector dengan Form -->
        <form method="GET" action="{{ route('track-records.index') }}" class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Filter Tahun:</label>
            <select name="year"
                    onchange="this.form.submit()"
                    class="px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @foreach($availableYears as $y)
                    <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-800">
                    <strong>Track Record</strong> digunakan untuk mencatat performa supplier per bulan pada setiap kriteria.
                    Progress di bawah menampilkan data untuk tahun <strong>{{ $selectedYear }}</strong>.
                </p>
            </div>
        </div>
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

    <!-- Table Container -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
            <table class="w-full divide-y divide-gray-200" style="min-width: 800px;">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Kode
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[200px]">
                            Nama Supplier
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[150px]">
                            Kontak
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Progress {{ $selectedYear }}
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($suppliers as $supplier)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <!-- Kode -->
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $supplier->code }}
                            </span>
                        </td>

                        <!-- Nama -->
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <span class="text-indigo-600 font-bold text-sm">
                                        {{ strtoupper(substr($supplier->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $supplier->name }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <!-- Kontak -->
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">
                                @if($supplier->phone)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        {{ $supplier->phone }}
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </div>
                        </td>

                        <!-- Progress -->
                        <td class="px-4 py-4 whitespace-nowrap">
                            @php
                                $stats = $supplier->completion_stats;
                            @endphp

                            <div class="flex items-center justify-center">
                                <div class="w-full max-w-xs">
                                    <!-- Progress Bar -->
                                    <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                                        <div class="h-2 rounded-full transition-all duration-300
                                                    {{ $stats['percentage'] >= 80 ? 'bg-green-500' : ($stats['percentage'] >= 50 ? 'bg-blue-500' : ($stats['percentage'] >= 20 ? 'bg-yellow-500' : 'bg-red-500')) }}"
                                             style="width: {{ $stats['percentage'] }}%">
                                        </div>
                                    </div>

                                    <!-- Text -->
                                    <div class="text-xs text-center">
                                        <span class="font-semibold text-gray-700">{{ $stats['completed'] }}/{{ $stats['total'] }}</span>
                                        <span class="text-gray-500">({{ number_format($stats['percentage'], 1) }}%)</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <!-- 🆕 FIXED: Pass selected year to detail page -->
                            <a href="{{ route('track-records.show', ['supplier' => $supplier, 'year' => $selectedYear]) }}"
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700
                                      text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Kelola Track Record
                            </a>
                        </td>
                    </tr>

                    @empty
                    <!-- Empty State -->
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-gray-500 text-base font-medium">Belum ada supplier aktif</p>
                                <p class="text-gray-400 text-sm mt-1">Silakan tambahkan dan aktifkan supplier terlebih dahulu</p>
                                <a href="{{ route('suppliers.create') }}"
                                   class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700
                                          text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Supplier
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<style>
    /* Custom scrollbar - Desktop */
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

    /* Mobile scroll */
    @media (max-width: 639px) {
        .overflow-x-auto {
            overflow-x: scroll !important;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection
