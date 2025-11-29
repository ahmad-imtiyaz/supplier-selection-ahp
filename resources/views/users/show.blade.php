@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('users.index') }}" 
           class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar User
        </a>
    </div>

    <!-- User Profile Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Header Background -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-32 sm:h-40"></div>
        
        <div class="px-4 sm:px-6 pb-6">
            <!-- Avatar & Basic Info - IMPROVED LAYOUT -->
            <div class="flex flex-col sm:flex-row sm:items-end -mt-16 sm:-mt-20 mb-6">
                <!-- Avatar -->
                <div class="flex justify-center sm:justify-start">
                    <div class="h-32 w-32 rounded-full bg-white p-2 shadow-xl">
                        <div class="h-full w-full rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-4xl font-bold text-indigo-600">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- User Info & Badge - IMPROVED SPACING -->
                <div class="mt-6 sm:mt-0 sm:ml-6 flex-1">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <!-- Name & Email -->
                        <div class="text-center sm:text-left">
                            <div class="flex items-center justify-center sm:justify-start gap-2 flex-wrap">
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                                    {{ $user->name }}
                                </h1>
                                @if($user->id === Auth::id())
                                    <span class="inline-flex items-center text-xs sm:text-sm font-medium text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                                        Akun Anda
                                    </span>
                                @endif
                            </div>
                            <p class="text-gray-600 mt-2 text-sm sm:text-base">{{ $user->email }}</p>
                        </div>
                        
                        <!-- Role Badge -->
                        <div class="flex justify-center sm:justify-start">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800 shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Administrator
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800 shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    Regular User
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Information Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
                <!-- Account Information -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900 mb-5 flex items-center">
                        <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        Informasi Akun
                    </h2>
                    
                    <dl class="space-y-5">
                        <div class="flex items-start">
                            <dt class="text-sm font-medium text-gray-500 w-32 flex-shrink-0">User ID</dt>
                            <dd class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">#{{ $user->id }}</dd>
                        </div>
                        
                        <div class="flex items-start">
                            <dt class="text-sm font-medium text-gray-500 w-32 flex-shrink-0">Nama Lengkap</dt>
                            <dd class="text-sm text-gray-900 flex-1">{{ $user->name }}</dd>
                        </div>
                        
                        <div class="flex items-start">
                            <dt class="text-sm font-medium text-gray-500 w-32 flex-shrink-0">Email</dt>
                            <dd class="text-sm text-gray-900 flex-1 break-all">{{ $user->email }}</dd>
                        </div>
                        
                        <div class="flex items-start">
                            <dt class="text-sm font-medium text-gray-500 w-32 flex-shrink-0">Status Email</dt>
                            <dd class="flex-1">
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center text-sm text-green-700 bg-green-50 px-3 py-1 rounded-full">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Terverifikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-sm text-red-700 bg-red-50 px-3 py-1 rounded-full">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Belum Terverifikasi
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Activity Information -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900 mb-5 flex items-center">
                        <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        Aktivitas
                    </h2>
                    
                    <dl class="space-y-5">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-2">Terdaftar Sejak</dt>
                            <dd class="text-sm text-gray-900">
                                <div class="flex items-start flex-col gap-1">
                                    <span class="font-medium">{{ $user->created_at->format('d F Y, H:i') }} WIB</span>
                                    <span class="text-xs text-gray-500">
                                        ({{ $user->created_at->diffForHumans() }})
                                    </span>
                                </div>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-2">Terakhir Diperbarui</dt>
                            <dd class="text-sm text-gray-900">
                                <div class="flex items-start flex-col gap-1">
                                    <span class="font-medium">{{ $user->updated_at->format('d F Y, H:i') }} WIB</span>
                                    <span class="text-xs text-gray-500">
                                        ({{ $user->updated_at->diffForHumans() }})
                                    </span>
                                </div>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-2">Lama Bergabung</dt>
                            <dd class="text-sm text-gray-900">
                                <span class="inline-flex items-center bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full font-medium">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $user->created_at->diffInDays(now()) }} hari
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($user->id !== Auth::id())
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Kelola Akun
                </h3>
                <div class="flex flex-wrap gap-3">
                    @if($user->role === 'user')
                        <form method="POST" action="{{ route('users.promote', $user) }}" class="inline-block">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="inline-flex items-center px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                </svg>
                                Promosikan ke Admin
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('users.demote', $user) }}" class="inline-block">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="inline-flex items-center px-5 py-2.5 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                                Turunkan ke User
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('users.destroy', $user) }}" id="delete-user-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                                onclick="confirmDelete('delete-user-form', '{{ $user->name }}')"
                                class="inline-flex items-center px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Akun
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Additional Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-5">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-sm font-semibold text-blue-800 mb-2">Informasi Role</h3>
                <div class="text-sm text-blue-700 space-y-2">
                    <p class="flex items-start">
                        <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Role <strong class="font-semibold">Admin</strong> memiliki akses penuh ke semua fitur sistem termasuk kelola kriteria, supplier, penilaian, dan perbandingan AHP.
                    </p>
                    <p class="flex items-start">
                        <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Role <strong class="font-semibold">User</strong> memiliki akses terbatas sesuai permission yang diberikan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection