<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Password - AHP Supplier</title>
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

        <!-- Forgot Password Box -->
        <div class="w-full max-w-md relative z-10">
            <div class="bg-white rounded-2xl shadow-2xl p-8 sm:p-10">
                
                <!-- Back Button -->
                <a href="{{ route('login') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition mb-6 group">
                    <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                    <span class="text-sm font-medium">Kembali ke Login</span>
                </a>

                <!-- Logo & Title -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl mb-4 float-icon shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-key text-3xl text-white"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Lupa Password?</h1>
                    <p class="text-gray-600">Tidak masalah! 🔐</p>
                    <p class="text-sm text-gray-500 mt-3 leading-relaxed">
                        Masukkan email Anda dan kami akan mengirimkan link untuk reset password. Periksa inbox atau folder spam Anda.
                    </p>
                </div>

                <!-- Session Status (Success) -->
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-lg flex items-start" style="background-color: #f0fdf4; border-left: 4px solid #22c55e;">
                        <i class="fas fa-check-circle mt-0.5 mr-3" style="color: #22c55e;"></i>
                        <div class="text-sm" style="color: #166534;">
                            <p class="font-semibold mb-1">Email Berhasil Dikirim!</p>
                            <p>{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

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

                <!-- Forgot Password Form -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

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
                                autofocus
                                class="block w-full pl-12 pr-4 py-3.5 border rounded-xl focus:outline-none focus:ring-2 transition duration-200 @error('email') border-red-300 @else border-gray-200 @enderror"
                                style="background-color: #f9fafb; @error('email') background-color: #fef2f2; @enderror"
                                placeholder="nama@email.com"
                            >
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Pastikan email yang Anda masukkan sudah terdaftar
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full text-white font-bold py-4 px-6 rounded-xl transition duration-300 shadow-lg flex items-center justify-center"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>
                        <span>Kirim Link Reset Password</span>
                    </button>
                </form>

                <!-- Additional Info -->
                <div class="mt-6 p-4 rounded-lg" style="background-color: #eff6ff; border: 1px solid #bfdbfe;">
                    <div class="flex items-start">
                        <i class="fas fa-lightbulb mt-0.5 mr-3" style="color: #2563eb;"></i>
                        <div class="text-sm" style="color: #1e40af;">
                            <p class="font-semibold mb-1">Tips:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li>Link reset password berlaku selama 60 menit</li>
                                <li>Cek folder spam jika tidak menerima email</li>
                                <li>Pastikan email Anda masih aktif</li>
                            </ul>
                        </div>
                    </div>
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