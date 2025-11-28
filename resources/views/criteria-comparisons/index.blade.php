@extends('layouts.admin')

@section('title', 'Perbandingan Kriteria AHP')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900">Perbandingan Kriteria (AHP)</h1>
            <p class="mt-1 text-sm text-gray-500">
                Lakukan perbandingan berpasangan antar kriteria menggunakan skala Saaty
            </p>
        </div>
        @if($progress['completed'] > 0)
        <div class="flex flex-col sm:flex-row gap-2 lg:flex-shrink-0">
            <a href="{{ route('criteria-comparisons.result') }}" 
               class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
                Lihat Hasil
            </a>
            <form action="{{ route('criteria-comparisons.reset') }}" method="POST" 
                  onsubmit="return confirm('Yakin ingin reset semua perbandingan?')"
                  class="w-full sm:w-auto">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                    </svg>
                    Reset
                </button>
            </form>
        </div>
        @endif
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-700">Progress Perbandingan</span>
            <span class="text-sm font-medium text-gray-900">
                {{ $progress['completed'] }} / {{ $progress['total'] }} 
                ({{ $progress['percentage'] }}%)
            </span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" 
                 style="width: {{ $progress['percentage'] }}%"></div>
        </div>
    </div>

    @if($criterias->count() < 2)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm text-yellow-700">
                        Minimal 2 kriteria aktif diperlukan untuk melakukan perbandingan.
                        <a href="{{ route('criteria.create') }}" class="font-medium underline">
                            Tambah kriteria baru
                        </a>
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- Mobile Scroll Hint -->
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

        <!-- Comparison Matrix -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
                <table class="w-full divide-y divide-gray-200" style="min-width: 700px;">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                Kriteria
                            </th>
                            @foreach($criterias as $criterion)
                                <th class="px-3 sm:px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[100px]">
                                    {{ $criterion->code }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($criterias as $i => $criteria1)
                            <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors">
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $criteria1->code }}
                                        </div>
                                        <div class="text-xs text-gray-500 line-clamp-1">
                                            {{ $criteria1->name }}
                                        </div>
                                    </div>
                                </td>
                                @foreach($criterias as $j => $criteria2)
                                    <td class="px-3 sm:px-4 py-4 text-center">
                                        @php
                                            $cell = $matrix[$criteria1->id][$criteria2->id];
                                        @endphp
                                        
                                        @if($cell['editable'])
                                            @if($cell['value'])
                                                <div class="flex flex-col items-center gap-1.5">
                                                    <span class="text-sm font-medium text-gray-900 whitespace-nowrap">
                                                        {{ $cell['display'] }}
                                                    </span>
                                                    <div class="flex gap-1.5">
                                                        <a href="{{ route('criteria-comparisons.create', [
                                                            'criteria1' => $criteria1->id, 
                                                            'criteria2' => $criteria2->id
                                                        ]) }}" 
                                                           class="text-blue-600 hover:text-blue-800 p-1"
                                                           title="Edit">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                        </a>
                                                        <form action="{{ route('criteria-comparisons.destroy', $cell['comparison']->id) }}" 
                                                              method="POST" class="inline"
                                                              onsubmit="return confirm('Hapus perbandingan ini?')">
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
                                                <a href="{{ route('criteria-comparisons.create', [
                                                    'criteria1' => $criteria1->id, 
                                                    'criteria2' => $criteria2->id
                                                ]) }}" 
                                                   class="inline-block px-2.5 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-xs font-medium whitespace-nowrap">
                                                    Bandingkan
                                                </a>
                                            @endif
                                        @else
                                            <span class="text-sm {{ $cell['value'] == 1 ? 'font-bold text-gray-900' : 'text-gray-600' }}">
                                                {{ $cell['display'] }}
                                            </span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Saaty Scale Reference -->
        <div class="bg-blue-50 rounded-lg p-4 sm:p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                Skala Perbandingan Saaty
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3 text-xs sm:text-sm">
                <div class="bg-white rounded px-3 py-2">
                    <span class="font-semibold text-blue-600">1</span> 
                    <span class="text-gray-700">- Sama penting</span>
                </div>
                <div class="bg-white rounded px-3 py-2">
                    <span class="font-semibold text-blue-600">3</span> 
                    <span class="text-gray-700">- Sedikit lebih penting</span>
                </div>
                <div class="bg-white rounded px-3 py-2">
                    <span class="font-semibold text-blue-600">5</span> 
                    <span class="text-gray-700">- Jelas lebih penting</span>
                </div>
                <div class="bg-white rounded px-3 py-2">
                    <span class="font-semibold text-blue-600">7</span> 
                    <span class="text-gray-700">- Sangat lebih penting</span>
                </div>
                <div class="bg-white rounded px-3 py-2">
                    <span class="font-semibold text-blue-600">9</span> 
                    <span class="text-gray-700">- Mutlak lebih penting</span>
                </div>
                <div class="bg-white rounded px-3 py-2">
                    <span class="font-semibold text-blue-600">2,4,6,8</span> 
                    <span class="text-gray-700">- Nilai antara</span>
                </div>
            </div>
        </div>

        @if($progress['completed'] == $progress['total'] && $progress['total'] > 0)
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-green-700 font-medium">
                            Semua perbandingan sudah lengkap! Silakan hitung bobot kriteria.
                        </p>
                    </div>
                    <form action="{{ route('criteria-comparisons.calculate') }}" method="POST" class="w-full sm:w-auto flex-shrink-0">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                            Hitung Bobot
                        </button>
                    </form>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection