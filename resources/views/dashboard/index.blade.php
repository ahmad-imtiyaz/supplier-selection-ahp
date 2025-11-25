@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-1">Selamat datang, {{ Auth::user()->name }}!</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Kriteria Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Kriteria</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['active_criteria'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">dari {{ $stats['total_criteria'] }} kriteria</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Supplier Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Supplier</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['active_suppliers'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">dari {{ $stats['total_suppliers'] }} supplier</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Penilaian Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Penilaian</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_assessments'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">data penilaian</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- AHP Status Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 {{ $ahpCompleted ? 'border-green-500' : 'border-yellow-500' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Status AHP</p>
                    <p class="text-xl font-bold {{ $ahpCompleted ? 'text-green-600' : 'text-yellow-600' }} mt-1">
                        {{ $ahpCompleted ? 'Selesai' : 'Belum Selesai' }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['criteria_comparisons'] }} perbandingan</p>
                </div>
                <div class="bg-{{ $ahpCompleted ? 'green' : 'yellow' }}-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-{{ $ahpCompleted ? 'green' : 'yellow' }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('criteria.create') }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition group">
                <div class="bg-blue-600 rounded-lg p-3 mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Tambah Kriteria</p>
                    <p class="text-xs text-gray-500">Buat kriteria baru</p>
                </div>
            </a>

            <a href="{{ route('suppliers.create') }}" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition group">
                <div class="bg-green-600 rounded-lg p-3 mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Tambah Supplier</p>
                    <p class="text-xs text-gray-500">Daftarkan supplier baru</p>
                </div>
            </a>

            <a href="{{ route('criteria-comparisons.index') }}" class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition group">
                <div class="bg-purple-600 rounded-lg p-3 mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Perbandingan AHP</p>
                    <p class="text-xs text-gray-500">Hitung bobot kriteria</p>
                </div>
            </a>

            <a href="{{ route('supplier-assessments.index') }}" class="flex items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition group">
                <div class="bg-orange-600 rounded-lg p-3 mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Penilaian Supplier</p>
                    <p class="text-xs text-gray-500">Nilai supplier</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Progress & Rankings Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Assessment Progress -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Progress Penilaian</h2>
            
            @if($assessmentProgress['total'] > 0)
                <div class="space-y-4">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Penilaian Selesai</span>
                        <span class="font-semibold">{{ $assessmentProgress['completed'] }} / {{ $assessmentProgress['total'] }}</span>
                    </div>
                    
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-4 rounded-full transition-all duration-500" 
                             style="width: {{ $assessmentProgress['percentage'] }}%"></div>
                    </div>
                    
                    <p class="text-sm text-gray-600">
                        <span class="font-semibold text-blue-600">{{ number_format($assessmentProgress['percentage'], 1) }}%</span> 
                        dari total penilaian telah selesai
                    </p>

                    @if($assessmentProgress['percentage'] < 100)
                        <a href="{{ route('supplier-assessments.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Lanjutkan Penilaian
                        </a>
                    @else
                        <div class="flex items-center text-green-600 text-sm font-semibold">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Semua penilaian telah selesai!
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500 mb-4">Belum ada penilaian supplier</p>
                    <a href="{{ route('supplier-assessments.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        Mulai Penilaian →
                    </a>
                </div>
            @endif
        </div>

        <!-- Top 5 Rankings -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Top 5 Supplier</h2>
                @if(count($latestRankings) > 0)
                    <a href="{{ route('supplier-assessments.ranking') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lihat Semua →
                    </a>
                @endif
            </div>

            @if(count($latestRankings) > 0)
                <div class="space-y-3">
                    @foreach($latestRankings as $ranking)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full font-bold
                                {{ $ranking['rank'] == 1 ? 'bg-yellow-100 text-yellow-800' : 
                                   ($ranking['rank'] == 2 ? 'bg-gray-100 text-gray-700' : 
                                   ($ranking['rank'] == 3 ? 'bg-orange-100 text-orange-700' : 'bg-blue-50 text-blue-600')) }}">
                                #{{ $ranking['rank'] }}
                            </div>
                            <div class="ml-3 flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $ranking['supplier']->name }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $ranking['supplier']->code }}</p>
                            </div>
                            <div class="ml-3 flex-shrink-0">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                    {{ number_format($ranking['percentage'], 2) }}%
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="text-gray-500 mb-2">Belum ada ranking</p>
                    <p class="text-xs text-gray-400">Selesaikan penilaian untuk melihat ranking</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Criteria Weights Chart -->
    @if($criteriaWeights->isNotEmpty())
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Bobot Kriteria</h2>
        <div class="space-y-3">
            @foreach($criteriaWeights as $criteria)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-700 font-medium">{{ $criteria['name'] }}</span>
                        <span class="text-gray-900 font-semibold">{{ number_format($criteria['weight'], 2) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full transition-all duration-500" 
                             style="width: {{ $criteria['weight'] }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4 pt-4 border-t border-gray-200">
            <a href="{{ route('criteria-comparisons.result') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                Lihat Detail Perhitungan AHP →
            </a>
        </div>
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-yellow-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <h3 class="text-yellow-900 font-semibold mb-1">Bobot Kriteria Belum Dihitung</h3>
                <p class="text-yellow-800 text-sm mb-3">Silakan lakukan perbandingan AHP untuk menghitung bobot kriteria terlebih dahulu.</p>
                <a href="{{ route('criteria-comparisons.index') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition text-sm font-medium">
                    Mulai Perbandingan AHP
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- System Info -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold mb-2">Sistem Pendukung Keputusan</h2>
                <p class="text-indigo-100 text-sm">Menggunakan metode Analytical Hierarchy Process (AHP) untuk pemilihan supplier terbaik</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-24 h-24 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>
@endsection