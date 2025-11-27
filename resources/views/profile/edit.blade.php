@extends('layouts.app')

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
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm"
                        placeholder="Masukkan nama lengkap"
                    />
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
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
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm"
                        placeholder="email@example.com"
                    />
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2 p-2.5 bg-yellow-50 border border-yellow-200 rounded-md">
                            <p class="text-xs text-yellow-800">
                                Email Anda belum diverifikasi.
                                <button form="send-verification" class="underline font-medium hover:text-yellow-900">
                                    Kirim ulang verifikasi
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-1.5 text-xs text-green-600">
                                    Link verifikasi telah dikirim ke email Anda.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end pt-3 border-t border-gray-100">
                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" 
                           x-show="show" 
                           x-transition
                           x-init="setTimeout(() => show = false, 3000)"
                           class="text-sm text-green-600 mr-4">
                            Profil berhasil diperbarui
                        </p>
                    @endif
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition-colors">
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm pr-10"
                            placeholder="Masukkan password saat ini"
                        />
                        <button type="button" onclick="togglePassword('update_password_current_password')" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-sm">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('current_password', 'updatePassword')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm pr-10"
                            placeholder="Masukkan password baru"
                        />
                        <button type="button" onclick="togglePassword('update_password_password')" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-sm">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password', 'updatePassword')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter</p>
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm pr-10"
                            placeholder="Ulangi password baru"
                        />
                        <button type="button" onclick="togglePassword('update_password_password_confirmation')" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-sm">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password_confirmation', 'updatePassword')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end pt-3 border-t border-gray-100">
                    @if (session('status') === 'password-updated')
                        <p x-data="{ show: true }" 
                           x-show="show" 
                           x-transition
                           x-init="setTimeout(() => show = false, 3000)"
                           class="text-sm text-green-600 mr-4">
                            Password berhasil diperbarui
                        </p>
                    @endif
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition-colors">
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Account -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-red-50 border-b border-red-200">
            <h2 class="text-base font-semibold text-red-800">Hapus Akun</h2>
            <p class="text-sm text-red-700 mt-0.5">Hapus akun secara permanen</p>
        </div>

        <div class="p-6">
            <p class="text-sm text-gray-600 mb-4">
                Setelah akun dihapus, semua data akan dihapus secara permanen. Pastikan untuk mengunduh data penting terlebih dahulu.
            </p>

            <button 
                type="button"
                onclick="openDeleteModal()"
                class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors">
                Hapus Akun
            </button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-5">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Hapus Akun</h3>
            <p class="text-sm text-gray-600 mb-4">
                Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan.
            </p>

            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Masukkan password untuk konfirmasi
                    </label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm"
                        placeholder="Password Anda"
                    />
                    @error('password', 'userDeletion')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-2">
                    <button 
                        type="button" 
                        onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors">
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
        const icon = event.currentTarget.querySelector('i');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
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
</script>
@endpush
@endsection