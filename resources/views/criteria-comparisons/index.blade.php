@extends('layouts.admin')

@section('title', 'Perbandingan Kriteria AHP')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Perbandingan Kriteria (AHP)</h1>
            <p class="mt-1 text-sm text-gray-500">
                Lakukan perbandingan berpasangan antar kriteria menggunakan skala Saaty
            </p>
        </div>
        <div class="flex gap-2">
            @if($progress['completed'] > 0)
                <a href="{{ route('criteria-comparisons.result') }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-calculator mr-2"></i>Lihat Hasil Perhitungan
                </a>
                <form action="{{ route('criteria-comparisons.reset') }}" method="POST" 
                      onsubmit="return confirm('Yakin ingin reset semua perbandingan?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-lg shadow p-6">
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
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <i class="fas fa-exclamation-triangle text-yellow-400 mr-3"></i>
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
        <!-- Comparison Matrix -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Kriteria
                            </th>
                            @foreach($criterias as $criterion)
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    {{ $criterion->code }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($criterias as $i => $criteria1)
                            <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $criteria1->code }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $criteria1->name }}
                                        </div>
                                    </div>
                                </td>
                                @foreach($criterias as $j => $criteria2)
                                    <td class="px-4 py-4 text-center">
                                        @php
                                            $cell = $matrix[$criteria1->id][$criteria2->id];
                                        @endphp
                                        
                                        @if($cell['editable'])
                                            @if($cell['value'])
                                                <div class="flex flex-col items-center gap-1">
                                                    <span class="text-sm font-medium text-gray-900">
                                                        {{ $cell['display'] }}
                                                    </span>
                                                    <div class="flex gap-1">
                                                        <a href="{{ route('criteria-comparisons.create', [
                                                            'criteria1' => $criteria1->id, 
                                                            'criteria2' => $criteria2->id
                                                        ]) }}" 
                                                           class="text-xs text-blue-600 hover:text-blue-800">
                                                            Edit
                                                        </a>
                                                        <form action="{{ route('criteria-comparisons.destroy', $cell['comparison']->id) }}" 
                                                              method="POST" class="inline"
                                                              onsubmit="return confirm('Hapus perbandingan ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-xs text-red-600 hover:text-red-800">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @else
                                                <a href="{{ route('criteria-comparisons.create', [
                                                    'criteria1' => $criteria1->id, 
                                                    'criteria2' => $criteria2->id
                                                ]) }}" 
                                                   class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-xs">
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
        <div class="bg-blue-50 rounded-lg p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Skala Perbandingan Saaty
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                <div><span class="font-semibold">1</span> - Sama penting</div>
                <div><span class="font-semibold">3</span> - Sedikit lebih penting</div>
                <div><span class="font-semibold">5</span> - Jelas lebih penting</div>
                <div><span class="font-semibold">7</span> - Sangat lebih penting</div>
                <div><span class="font-semibold">9</span> - Mutlak lebih penting</div>
                <div><span class="font-semibold">2,4,6,8</span> - Nilai antara</div>
            </div>
        </div>

        @if($progress['completed'] == $progress['total'] && $progress['total'] > 0)
            <div class="bg-green-50 border-l-4 border-green-400 p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-400 mr-3 text-xl"></i>
                        <p class="text-sm text-green-700 font-medium">
                            Semua perbandingan sudah lengkap! Silakan hitung bobot kriteria.
                        </p>
                    </div>
                    <form action="{{ route('criteria-comparisons.calculate') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-calculator mr-2"></i>Hitung Bobot
                        </button>
                    </form>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection