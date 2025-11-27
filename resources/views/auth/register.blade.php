<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - AHP Supplier</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .gradient-bg {
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #4facfe);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .float-icon {
            animation: float 3s ease-in-out infinite;
        }

        .circle-blur {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            pointer-events: none;
        }
    </style>
</head>
<body class="antialiased">
    <!-- Background with Gradient -->
    <div class="min-h-screen gradient-bg flex items-center justify-center px-4 py-12 relative overflow-hidden">
        
        <!-- Decorative Circles -->
        <div class="circle-blur" style="width: 288px; height: 288px; top: 40px; left: -80px; filter: blur(48px);"></div>
        <div class="circle-blur" style="width: 384px; height: 384px; bottom: 40px; right: -80px; filter: blur(48px);"></div>
        <div class="circle-blur" style="width: 256px; height: 256px; top: 50%; left: 50%; transform: translate(-50%, -50%); filter: blur(32px);"></div>

        <!-- Register Box -->
        <div class="w-full max-w-md relative z-10">
            <div class="bg-white rounded-2xl shadow-2xl p-8 sm:p-10">
                
                <!-- Logo & Title -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl mb-4 float-icon shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-user-plus text-3xl text-white"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Buat Akun Baru</h1>
                    <p class="text-gray-600">Daftar untuk mulai menggunakan sistem</p>
                    <p class="text-sm text-gray-500 mt-1">Isi data Anda dengan lengkap</p>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-lg" style="background-color: #fef2f2; border-left: 4px solid #ef4444;">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle mt-0.5 mr-3" style="color: #ef4444;"></i>
                            <div class="text-sm" style="color: #991b1b;">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input 
                                id="name" 
                                type="text" 
                                name="name" 
                                value="{{ old('name') }}"
                                required 
                                autofocus 
                                autocomplete="name"
                                class="block w-full pl-12 pr-4 py-3.5 border rounded-xl focus:outline-none focus:ring-2 transition duration-200 @error('name') border-red-300 @else border-gray-200 @enderror"
                                style="background-color: #f9fafb; @error('name') background-color: #fef2f2; @enderror"
                                placeholder="Masukkan nama lengkap"
                            >
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                required 
                                autocomplete="username"
                                class="block w-full pl-12 pr-4 py-3.5 border rounded-xl focus:outline-none focus:ring-2 transition duration-200 @error('email') border-red-300 @else border-gray-200 @enderror"
                                style="background-color: #f9fafb; @error('email') background-color: #fef2f2; @enderror"
                                placeholder="nama@email.com"
                            >
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Password
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                id="password" 
                                :type="show ? 'text' : 'password'"
                                name="password" 
                                required 
                                autocomplete="new-password"
                                class="block w-full pl-12 pr-12 py-3.5 border rounded-xl focus:outline-none focus:ring-2 transition duration-200 @error('password') border-red-300 @else border-gray-200 @enderror"
                                style="background-color: #f9fafb; @error('password') background-color: #fef2f2; @enderror"
                                placeholder="Minimal 8 karakter"
                            >
                            <button 
                                type="button"
                                @click="show = !show"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition"
                            >
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            Konfirmasi Password
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                id="password_confirmation" 
                                :type="show ? 'text' : 'password'"
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                class="block w-full pl-12 pr-12 py-3.5 border rounded-xl focus:outline-none focus:ring-2 transition duration-200 border-gray-200"
                                style="background-color: #f9fafb;"
                                placeholder="Ulangi password"
                            >
                            <button 
                                type="button"
                                @click="show = !show"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition"
                            >
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Register Button -->
                    <button 
                        type="submit"
                        class="w-full text-white font-bold py-4 px-6 rounded-xl transition duration-300 shadow-lg flex items-center justify-center"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"
                    >
                        <span>Daftar Sekarang</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-semibold transition" style="color: #667eea;">
                            Masuk di sini
                        </a>
                    </p>
                </div>

                <!-- Footer -->
                <div class="mt-8 pt-6 text-center" style="border-top: 1px solid #e5e7eb;">
                    <p class="text-xs text-gray-500">&copy; {{ date('Y') }} AHP Supplier System. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>