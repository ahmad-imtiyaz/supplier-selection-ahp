@extends('layouts.admin')

@section('title', 'Daftar Supplier')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Supplier</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola data supplier untuk penilaian</p>
        </div>

        <a href="{{ route('suppliers.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 
                  text-white text-sm font-medium rounded-md shadow-sm transition-colors w-full sm:w-auto justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Supplier
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
        <div class="flex">
            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <p class="ml-3 text-sm text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

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

    <!-- Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Kode
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[180px]">
                            Nama Supplier
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap min-w-[150px]">
                            Kontak
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            PIC
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Status
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
                        <td class="px-4 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $supplier->name }}
                            </div>
                            @if($supplier->address)
                            <div class="text-xs text-gray-500 mt-1 line-clamp-1">
                                {{ \Illuminate\Support\Str::limit($supplier->address, 40) }}
                            </div>
                            @endif
                        </td>

                        <!-- Kontak -->
                        <td class="px-4 py-4">
                            @if($supplier->phone)
                            <div class="text-sm text-gray-900 whitespace-nowrap">
                                <svg class="inline w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $supplier->phone }}
                            </div>
                            @endif
                            @if($supplier->email)
                            <div class="text-xs text-gray-500 mt-1 truncate max-w-[200px]">
                                <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $supplier->email }}
                            </div>
                            @endif
                        </td>

                        <!-- PIC -->
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($supplier->contact_person)
                                <span class="text-sm text-gray-900">{{ $supplier->contact_person }}</span>
                            @else
                                <span class="text-sm text-gray-400 italic">-</span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($supplier->is_active)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                             bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                             bg-red-100 text-red-800">
                                    Nonaktif
                                </span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                
                                <!-- View Button -->
                                <a href="{{ route('suppliers.show', $supplier) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors p-1"
                                   title="Lihat Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                <!-- Edit Button -->
                                <a href="{{ route('suppliers.edit', $supplier) }}" 
                                   class="text-yellow-600 hover:text-yellow-800 transition-colors p-1"
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                <!-- Delete Button -->
                                <form id="delete-form-{{ $supplier->id }}" 
                                      action="{{ route('suppliers.destroy', $supplier) }}" 
                                      method="POST" 
                                      class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            onclick="confirmDelete('delete-form-{{ $supplier->id }}', '{{ addslashes($supplier->name) }}')"
                                            class="text-red-600 hover:text-red-800 transition-colors p-1"
                                            title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                    @empty
                    <!-- Empty State -->
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p class="text-gray-500 text-base font-medium">Belum ada supplier</p>
                                <p class="text-gray-400 text-sm mt-1">Silakan tambah supplier baru untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Box -->
    @if($suppliers->count() >= 3)
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
        <div class="flex">
            <svg class="h-5 w-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="ml-3">
                <p class="text-sm text-blue-800">
                    Anda sudah memiliki <strong>{{ $suppliers->count() }}</strong> supplier.
                    Pastikan kriteria sudah diatur sebelum melakukan penilaian AHP.
                </p>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection