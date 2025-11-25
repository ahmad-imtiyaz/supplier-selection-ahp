@extends('layouts.admin')

@section('title', 'Penilaian Supplier')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Penilaian Supplier</h1>
            <p class="text-gray-600 mt-1">Berikan nilai untuk setiap supplier berdasarkan kriteria yang ada</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('supplier-assessments.ranking') }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-trophy mr-2"></i>Lihat Ranking
            </a>
            <form action="{{ route('supplier-assessments.reset') }}" method="POST" 
                  onsubmit="return confirm('Apakah Anda yakin ingin mereset semua penilaian?')" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-redo mr-2"></i>Reset Semua
                </button>
            </form>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-lg shadow p-6">
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
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    </div>
    @endif

    <!-- Info Box -->
    @if($criterias->isEmpty())
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">
        <i class="fas fa-info-circle mr-2"></i>
        Belum ada kriteria aktif. Silakan tambahkan kriteria terlebih dahulu di menu <a href="{{ route('criteria.index') }}" class="underline font-semibold">Kriteria</a>.
    </div>
    @endif

    @if($suppliers->isEmpty())
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">
        <i class="fas fa-info-circle mr-2"></i>
        Belum ada supplier aktif. Silakan tambahkan supplier terlebih dahulu di menu <a href="{{ route('suppliers.index') }}" class="underline font-semibold">Supplier</a>.
    </div>
    @endif

    <!-- Assessment Matrix -->
    @if(!$criterias->isEmpty() && !$suppliers->isEmpty())
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50">
                            Supplier
                        </th>
                        @foreach($criterias as $criteria)
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div>{{ $criteria->code }}</div>
                            <div class="text-gray-400 font-normal normal-case">{{ $criteria->name }}</div>
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
                        <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white">
                            <div class="font-medium text-gray-900">{{ $supplier->code }}</div>
                            <div class="text-sm text-gray-500">{{ $supplier->name }}</div>
                        </td>
                        @foreach($criterias as $criteria)
                        <td class="px-6 py-4 text-center">
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
                                <div class="flex justify-center gap-1">
                                    <a href="{{ route('supplier-assessments.create', ['supplier' => $supplier->id, 'criteria' => $criteria->id]) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-xs">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('supplier-assessments.destroy', $assessment->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Hapus penilaian ini?')" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @else
                            <a href="{{ route('supplier-assessments.create', ['supplier' => $supplier->id, 'criteria' => $criteria->id]) }}" 
                               class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-600 rounded-lg hover:bg-blue-100 hover:text-blue-600 transition text-sm">
                                <i class="fas fa-plus mr-1"></i>Nilai
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
        <h3 class="text-sm font-semibold text-gray-700 mb-2">Keterangan Nilai:</h3>
        <div class="flex flex-wrap gap-4 text-sm">
            <div class="flex items-center">
                <span class="inline-block w-4 h-4 bg-green-100 border border-green-200 rounded mr-2"></span>
                <span class="text-gray-600">Sangat Baik (≥80)</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-4 h-4 bg-blue-100 border border-blue-200 rounded mr-2"></span>
                <span class="text-gray-600">Baik (60-79)</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-4 h-4 bg-yellow-100 border border-yellow-200 rounded mr-2"></span>
                <span class="text-gray-600">Cukup (40-59)</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-4 h-4 bg-red-100 border border-red-200 rounded mr-2"></span>
                <span class="text-gray-600">Kurang (<40)</span>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection