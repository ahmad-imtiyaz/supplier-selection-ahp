@extends('layouts.admin')

@section('title', 'Profil Pengguna')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">
    <!-- Header Simple -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Profil Pengguna</h1>
        <p class="text-gray-600 mt-1">Kelola informasi akun Anda</p>
    </div>

    <!-- Update Profile Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-base font-semibold text-gray-800">Informasi Profil</h2>
            <p class="text-sm text-gray-600 mt-0.5">Perbarui nama dan email akun Anda</p>
        </div>

        <div class="p-6">
            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('patch')

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Lengkap
                    </label>
                    <input 
                        id="name" 
                        name="name" 
                        type="text" 
                        value="{{ old('name', $user->name) }}" 
                        required 
                        autofocus 
                        autocomplete="name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm"
                        placeholder="Masukkan nama lengkap"
                    />
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Alamat Email
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        value="{{ old('email', $user->email) }}" 
                        required 
                        autocomplete="username"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm"
                        placeholder="email@example.com"
                    />
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                            <p class="text-xs text-yellow-800">
                                Email Anda belum diverifikasi.
                                <button form="send-verification" class="underline font-medium hover:text-yellow-900 transition-colors">
                                    Kirim ulang verifikasi
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-1.5 text-xs text-green-600 font-medium">
                                    Link verifikasi telah dikirim ke email Anda.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" 
                           x-show="show" 
                           x-transition
                           x-init="setTimeout(() => show = false, 3000)"
                           class="text-sm text-green-600 font-medium">
                            ✓ Profil berhasil diperbarui
                        </p>
                    @endif
                    <button 
                        type="submit" 
                        class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Update Password -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-base font-semibold text-gray-800">Perbarui Password</h2>
            <p class="text-sm text-gray-600 mt-0.5">Gunakan password yang kuat untuk keamanan akun</p>
        </div>

        <div class="p-6">
            <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('put')

                <!-- Current Password -->
                <div>
                    <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Password Saat Ini
                    </label>
                    <div class="relative">
                        <input 
                            id="update_password_current_password" 
                            name="current_password" 
                            type="password" 
                            autocomplete="current-password"
                            class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm"
                            placeholder="Masukkan password saat ini"
                        />
                        <button type="button" onclick="togglePassword('update_password_current_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('current_password', 'updatePassword')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Password Baru
                    </label>
                    <div class="relative">
                        <input 
                            id="update_password_password" 
                            name="password" 
                            type="password" 
                            autocomplete="new-password"
                            class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm"
                            placeholder="Masukkan password baru"
                        />
                        <button type="button" onclick="togglePassword('update_password_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password', 'updatePassword')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1.5 text-xs text-gray-500">Minimal 8 karakter, kombinasi huruf dan angka</p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <input 
                            id="update_password_password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            autocomplete="new-password"
                            class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm"
                            placeholder="Ulangi password baru"
                        />
                        <button type="button" onclick="togglePassword('update_password_password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation', 'updatePassword')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    @if (session('status') === 'password-updated')
                        <p x-data="{ show: true }" 
                           x-show="show" 
                           x-transition
                           x-init="setTimeout(() => show = false, 3000)"
                           class="text-sm text-green-600 font-medium">
                            ✓ Password berhasil diperbarui
                        </p>
                    @endif
                    <button 
                        type="submit" 
                        class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Account -->
    <div class="bg-white rounded-lg shadow-sm border border-red-200 overflow-hidden">
        <div class="px-6 py-4 bg-red-50 border-b border-red-200">
            <h2 class="text-base font-semibold text-red-800">Hapus Akun</h2>
            <p class="text-sm text-red-700 mt-0.5">Hapus akun secara permanen</p>
        </div>

        <div class="p-6">
            <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">
                            Setelah akun dihapus, semua data akan dihapus secara permanen. Pastikan untuk mengunduh data penting terlebih dahulu.
                        </p>
                    </div>
                </div>
            </div>

            <button 
                type="button"
                onclick="openDeleteModal()"
                class="w-full sm:w-auto px-5 py-2.5 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all">
                Hapus Akun
            </button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Konfirmasi Hapus Akun</h3>
            <p class="text-sm text-gray-600 text-center mb-6">
                Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan.
            </p>

            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Masukkan password untuk konfirmasi
                    </label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                        placeholder="Password Anda"
                    />
                    @error('password', 'userDeletion')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                    <button 
                        type="button" 
                        onclick="closeDeleteModal()"
                        class="w-full sm:w-auto px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all">
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="w-full sm:w-auto px-5 py-2.5 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all">
                        Ya, Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle Password Visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const button = event.currentTarget;
        const svg = button.querySelector('svg');
        
        if (field.type === 'password') {
            field.type = 'text';
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
        } else {
            field.type = 'password';
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        }
    }

    // Delete Modal Functions
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Auto-open modal if there are deletion errors
    @if($errors->userDeletion->isNotEmpty())
        openDeleteModal();
    @endif

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });

    // Close modal when clicking outside
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>
@endpush
@endsection