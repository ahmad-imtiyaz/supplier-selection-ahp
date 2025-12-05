@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 rounded-2xl shadow-2xl">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        
        <div class="relative px-8 py-12 md:py-16">
            <div class="max-w-4xl">
                <!-- Badge -->
                <div class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-full mb-6">
                    <svg class="w-5 h-5 text-white mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <span class="text-white text-sm font-semibold">Decision Support System</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">
                    Selamat Datang, <span class="text-yellow-300">{{ Auth::user()->name }}</span>!
                </h1>
                
                <p class="text-xl text-indigo-100 mb-8 leading-relaxed max-w-3xl">
                    Sistem Pendukung Keputusan Pemilihan Supplier menggunakan metode 
                    <span class="font-semibold text-white">Analytical Hierarchy Process (AHP)</span> 
                    untuk membantu Anda menentukan supplier terbaik secara objektif dan terstruktur.
                </p>

                <!-- Feature Pills -->
                <div class="flex flex-wrap gap-3 mb-8">
                    <div class="flex items-center px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg">
                        <svg class="w-5 h-5 text-yellow-300 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-white text-sm font-medium">Objektif & Terukur</span>
                    </div>
                    <div class="flex items-center px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg">
                        <svg class="w-5 h-5 text-yellow-300 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                        </svg>
                        <span class="text-white text-sm font-medium">Multi Kriteria</span>
                    </div>
                    <div class="flex items-center px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg">
                        <svg class="w-5 h-5 text-yellow-300 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-white text-sm font-medium">Hasil Akurat</span>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap gap-4">
                    <a href="#quick-actions" class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 rounded-lg hover:bg-indigo-50 transition shadow-lg font-semibold">
                        Mulai Sekarang
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <a href="#how-it-works" class="inline-flex items-center px-6 py-3 bg-transparent border-2 border-white text-white rounded-lg hover:bg-white hover:text-indigo-600 transition font-semibold">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Cara Kerja
                    </a>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="hidden lg:block absolute right-8 top-1/2 transform -translate-y-1/2">
                <div class="relative">
                    <div class="w-64 h-64 bg-white bg-opacity-10 rounded-full blur-3xl"></div>
                    <svg class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 h-48 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

  <!-- How It Works Section -->
<div id="how-it-works" class="bg-white rounded-2xl shadow-lg p-8 md:p-10">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-bold text-gray-900 mb-3">Bagaimana Sistem Bekerja?</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">
            Metode AHP membantu Anda membuat keputusan dengan membandingkan kriteria secara berpasangan 
            untuk mendapatkan bobot yang objektif.
        </p>
    </div>

    <div class="grid md:grid-cols-5 gap-6">

        <!-- Step 1: Input Supplier -->
        <div class="relative">
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-6 text-white h-full transform hover:scale-105 transition duration-300 shadow-lg">
                <div class="bg-white bg-opacity-20 rounded-full w-12 h-12 flex items-center justify-center mb-4">
                    <span class="text-2xl font-bold">1</span>
                </div>
                <h3 class="text-lg font-bold mb-2">Input Supplier</h3>
                <p class="text-indigo-100 text-sm">
                    Masukkan daftar supplier yang akan dinilai dan pastikan mereka sesuai dengan kategori penilaian.
                </p>
            </div>
            <div class="hidden md:block absolute top-1/2 -right-3 transform -translate-y-1/2">
                <svg class="w-6 h-6 text-indigo-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        <!-- Step 2: Definisi Kriteria -->
        <div class="relative">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white h-full transform hover:scale-105 transition duration-300 shadow-lg">
                <div class="bg-white bg-opacity-20 rounded-full w-12 h-12 flex items-center justify-center mb-4">
                    <span class="text-2xl font-bold">2</span>
                </div>
                <h3 class="text-lg font-bold mb-2">Definisi Kriteria</h3>
                <p class="text-blue-100 text-sm">
                    Tentukan kriteria penilaian seperti harga, kualitas, ketepatan pengiriman, dan pelayanan.
                </p>
            </div>
            <div class="hidden md:block absolute top-1/2 -right-3 transform -translate-y-1/2">
                <svg class="w-6 h-6 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        <!-- Step 3: Perbandingan AHP -->
        <div class="relative">
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white h-full transform hover:scale-105 transition duration-300 shadow-lg">
                <div class="bg-white bg-opacity-20 rounded-full w-12 h-12 flex items-center justify-center mb-4">
                    <span class="text-2xl font-bold">3</span>
                </div>
                <h3 class="text-lg font-bold mb-2">Perbandingan AHP</h3>
                <p class="text-purple-100 text-sm">
                    Lakukan perbandingan berpasangan antar kriteria untuk menentukan tingkat kepentingan relatif.
                </p>
            </div>
            <div class="hidden md:block absolute top-1/2 -right-3 transform -translate-y-1/2">
                <svg class="w-6 h-6 text-purple-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        <!-- Step 4: Penilaian Supplier -->
        <div class="relative">
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white h-full transform hover:scale-105 transition duration-300 shadow-lg">
                <div class="bg-white bg-opacity-20 rounded-full w-12 h-12 flex items-center justify-center mb-4">
                    <span class="text-2xl font-bold">4</span>
                </div>
                <h3 class="text-lg font-bold mb-2">Penilaian Supplier</h3>
                <p class="text-orange-100 text-sm">
                    Beri nilai pada setiap supplier berdasarkan kriteria yang telah diprioritaskan.
                </p>
            </div>
            <div class="hidden md:block absolute top-1/2 -right-3 transform -translate-y-1/2">
                <svg class="w-6 h-6 text-orange-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        <!-- Step 5: Hasil & Ranking -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white h-full transform hover:scale-105 transition duration-300 shadow-lg">
            <div class="bg-white bg-opacity-20 rounded-full w-12 h-12 flex items-center justify-center mb-4">
                <span class="text-2xl font-bold">5</span>
            </div>
            <h3 class="text-lg font-bold mb-2">Hasil & Ranking</h3>
            <p class="text-green-100 text-sm">
                Sistem menghasilkan peringkat supplier terbaik berdasarkan nilai total AHP.
            </p>
        </div>

    </div>
</div>


    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Kriteria Card -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 transform hover:scale-105 transition duration-300">
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
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 transform hover:scale-105 transition duration-300">
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
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 transform hover:scale-105 transition duration-300">
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
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 {{ $ahpCompleted ? 'border-green-500' : 'border-yellow-500' }} transform hover:scale-105 transition duration-300">
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
    <div id="quick-actions" class="bg-white rounded-2xl shadow-lg p-8">
        <div class="flex items-center mb-6">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-2 mr-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Quick Actions</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('criteria.create') }}" class="group relative overflow-hidden flex items-center p-5 bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-xl transition-all duration-300 shadow-md hover:shadow-xl">
                <div class="absolute top-0 right-0 w-20 h-20 bg-blue-600 opacity-10 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-300"></div>
                <div class="bg-blue-600 rounded-lg p-3 mr-4 shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900 group-hover:text-blue-700 transition">Tambah Kriteria</p>
                    <p class="text-xs text-gray-600">Buat kriteria baru</p>
                </div>
            </a>

            <a href="{{ route('suppliers.create') }}" class="group relative overflow-hidden flex items-center p-5 bg-gradient-to-br from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-xl transition-all duration-300 shadow-md hover:shadow-xl">
                <div class="absolute top-0 right-0 w-20 h-20 bg-green-600 opacity-10 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-300"></div>
                <div class="bg-green-600 rounded-lg p-3 mr-4 shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900 group-hover:text-green-700 transition">Tambah Supplier</p>
                    <p class="text-xs text-gray-600">Daftarkan supplier baru</p>
                </div>
            </a>

            <a href="{{ route('criteria-comparisons.index') }}" class="group relative overflow-hidden flex items-center p-5 bg-gradient-to-br from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 rounded-xl transition-all duration-300 shadow-md hover:shadow-xl">
                <div class="absolute top-0 right-0 w-20 h-20 bg-purple-600 opacity-10 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-300"></div>
                <div class="bg-purple-600 rounded-lg p-3 mr-4 shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900 group-hover:text-purple-700 transition">Perbandingan AHP</p>
                    <p class="text-xs text-gray-600">Hitung bobot kriteria</p>
                </div>
            </a>

            <a href="{{ route('supplier-assessments.index') }}" class="group relative overflow-hidden flex items-center p-5 bg-gradient-to-br from-orange-50 to-orange-100 hover:from-orange-100 hover:to-orange-200 rounded-xl transition-all duration-300 shadow-md hover:shadow-xl">
                <div class="absolute top-0 right-0 w-20 h-20 bg-orange-600 opacity-10 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-300"></div>
                <div class="bg-orange-600 rounded-lg p-3 mr-4 shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900 group-hover:text-orange-700 transition">Penilaian Supplier</p>
                    <p class="text-xs text-gray-600">Nilai supplier</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Progress & Rankings Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Assessment Progress -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex items-center mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg p-2 mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Progress Penilaian</h2>
            </div>
            
            @if($assessmentProgress['total'] > 0)
                <div class="space-y-4">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Penilaian Selesai</span>
                        <span class="font-semibold">{{ $assessmentProgress['completed'] }} / {{ $assessmentProgress['total'] }}</span>
                    </div>
                    
                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-4 rounded-full transition-all duration-500 relative" 
                             style="width: {{ $assessmentProgress['percentage'] }}%">
                            <div class="absolute inset-0 bg-white opacity-20 animate-pulse"></div>
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-600">
                        <span class="font-semibold text-blue-600">{{ number_format($assessmentProgress['percentage'], 1) }}%</span> 
                        dari total penilaian telah selesai
                    </p>

                    @if($assessmentProgress['percentage'] < 100)
                        <a href="{{ route('supplier-assessments.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 text-sm font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Lanjutkan Penilaian
                        </a>
                    @else
                        <div class="flex items-center text-green-600 text-sm font-semibold bg-green-50 rounded-lg p-4">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Semua penilaian telah selesai!
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 mb-4 font-medium">Belum ada penilaian supplier</p>
                    <a href="{{ route('supplier-assessments.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold">
                        Mulai Penilaian 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            @endif
        </div>

        <!-- Top 5 Rankings -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-yellow-500 to-orange-600 rounded-lg p-2 mr-3">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Top 5 Supplier</h2>
                </div>
                @if(count($latestRankings) > 0)
                    <a href="{{ route('supplier-assessments.ranking') }}" class="text-sm text-blue-600 hover:text-blue-700 font-semibold inline-flex items-center">
                        Lihat Semua 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif
            </div>

            @if(count($latestRankings) > 0)
                <div class="space-y-3">
                    @foreach($latestRankings as $ranking)
                        <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-white hover:from-gray-100 hover:to-gray-50 rounded-xl transition-all duration-300 shadow-sm hover:shadow-md border border-gray-100">
                            <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full font-bold text-lg
                                {{ $ranking['rank'] == 1 ? 'bg-gradient-to-br from-yellow-400 to-yellow-500 text-white shadow-lg' : 
                                   ($ranking['rank'] == 2 ? 'bg-gradient-to-br from-gray-300 to-gray-400 text-white shadow-md' : 
                                   ($ranking['rank'] == 3 ? 'bg-gradient-to-br from-orange-400 to-orange-500 text-white shadow-md' : 'bg-blue-50 text-blue-600')) }}">
                                #{{ $ranking['rank'] }}
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate">
                                    {{ $ranking['supplier']->name }}
                                </p>
                                <p class="text-xs text-gray-500 flex items-center mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $ranking['supplier']->code }}
                                </p>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-md">
                                    {{ number_format($ranking['percentage'], 2) }}%
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 mb-2 font-medium">Belum ada ranking</p>
                    <p class="text-xs text-gray-400">Selesaikan penilaian untuk melihat ranking</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Criteria Weights Chart -->
    @if($criteriaWeights->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="flex items-center mb-6">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-2 mr-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Bobot Kriteria</h2>
        </div>
        <div class="space-y-4">
            @foreach($criteriaWeights as $index => $criteria)
                <div class="transform hover:scale-102 transition-transform duration-300">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-700 font-semibold flex items-center">
                            <span class="inline-flex items-center justify-center w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full text-xs font-bold mr-2">
                                {{ $index + 1 }}
                            </span>
                            {{ $criteria['name'] }}
                        </span>
                        <span class="text-gray-900 font-bold">{{ number_format($criteria['weight'], 2) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden shadow-inner">
                        <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 h-4 rounded-full transition-all duration-700 relative" 
                             style="width: {{ $criteria['weight'] }}%">
                            <div class="absolute inset-0 bg-white opacity-20 animate-pulse"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('criteria-comparisons.result') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold">
                Lihat Detail Perhitungan AHP 
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
    @else
    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-200 rounded-2xl p-8 shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-yellow-900 font-bold text-lg mb-2">Bobot Kriteria Belum Dihitung</h3>
                <p class="text-yellow-800 text-sm mb-4">Silakan lakukan perbandingan AHP untuk menghitung bobot kriteria terlebih dahulu. Metode AHP akan membantu Anda menentukan prioritas kriteria secara objektif.</p>
                <a href="{{ route('criteria-comparisons.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-600 to-orange-600 text-white rounded-lg hover:from-yellow-700 hover:to-orange-700 transition-all duration-300 text-sm font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Mulai Perbandingan AHP
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- System Info Footer -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 rounded-2xl shadow-2xl p-8 md:p-10">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        
        <div class="relative flex items-center justify-between flex-wrap gap-6">
            <div class="flex-1 min-w-[250px]">
                <div class="flex items-center mb-3">
                    <div class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Sistem Pendukung Keputusan</h2>
                </div>
                <p class="text-indigo-100 text-sm leading-relaxed">
                    Menggunakan metode <span class="font-semibold text-white">Analytical Hierarchy Process (AHP)</span> untuk pemilihan supplier terbaik dengan pendekatan yang terstruktur dan objektif.
                </p>
                <div class="flex flex-wrap gap-2 mt-4">
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs text-white font-medium">
                        Multi-Criteria Decision Making
                    </span>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs text-white font-medium">
                        Pairwise Comparison
                    </span>
                    <span class="px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs text-white font-medium">
                        Consistency Check
                    </span>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="relative">
                    <div class="w-32 h-32 bg-white bg-opacity-10 rounded-full blur-2xl absolute"></div>
                    <svg class="relative w-32 h-32 text-white opacity-30" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Smooth Scroll Script -->
<script>
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
@endsection