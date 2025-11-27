<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js x-cloak style -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen" x-data="{ open: false, mobileMenu: false }">
        <!-- Navbar -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex justify-between h-16 w-full">

                        <!-- Kiri: Logo -->
                        <div class="flex items-center">
                            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600">
                                AHP Supplier
                            </a>
                        </div>

                        <!-- Kanan: Hamburger Menu (mobile only) -->
                        <div class="flex items-center sm:hidden">
                            <button @click="mobileMenu = true" class="text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" 
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>

                        <!-- Navigation Desktop -->
                        <div class="hidden sm:flex sm:items-center sm:space-x-8">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                                Dashboard
                            </a>

                            <a href="{{ route('criteria.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('criteria.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                                Kriteria
                            </a>

                            <a href="{{ route('suppliers.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('suppliers.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                                Supplier
                            </a>

                            <a href="{{ route('supplier-assessments.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('supplier-assessments.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                                Penilaian
                            </a>

                            <a href="{{ route('criteria-comparisons.index') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('criteria-comparisons.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                                Perbandingan AHP
                            </a>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="open" 
                                 x-cloak
                                 @click.away="open = false" 
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar Mobile -->
        <div 
            x-show="mobileMenu"
            x-cloak
            class="fixed inset-0 z-40 flex sm:hidden"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <!-- Overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-50" 
                 @click="mobileMenu = false"></div>

            <!-- Sidebar -->
            <div class="relative bg-white w-64 h-full shadow-xl p-4"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
            >
                <!-- Close Button -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold">Menu</h2>
                    <button @click="mobileMenu = false" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-3">
                    <a href="{{ route('dashboard') }}" 
                       class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded">
                        Dashboard
                    </a>

                    <a href="{{ route('criteria.index') }}" 
                       class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded">
                        Kriteria
                    </a>

                    <a href="{{ route('suppliers.index') }}" 
                       class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded">
                        Supplier
                    </a>

                    <a href="{{ route('supplier-assessments.index') }}" 
                       class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded">
                        Penilaian
                    </a>

                    <a href="{{ route('criteria-comparisons.index') }}" 
                       class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded">
                        Perbandingan AHP
                    </a>

                    <hr class="my-3">

                    <!-- User Menu di Mobile -->
                    <a href="{{ route('profile.edit') }}" 
                       class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded">
                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left text-gray-700 hover:bg-gray-100 px-3 py-2 rounded">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <main class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- 🔥 TOAST NOTIFICATION COMPONENT -->
    <div x-data="toastManager()" @toast.window="show($event.detail)" class="fixed top-4 right-4 z-50 space-y-3">
        <template x-for="toast in toasts" :key="toast.id">
            <div 
                x-show="toast.visible"
                x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="translate-x-full opacity-0"
                x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transform transition ease-in duration-200"
                x-transition:leave-start="translate-x-0 opacity-100"
                x-transition:leave-end="translate-x-full opacity-0"
                :class="{
                    'bg-green-50 border-green-500': toast.type === 'success',
                    'bg-red-50 border-red-500': toast.type === 'error',
                    'bg-yellow-50 border-yellow-500': toast.type === 'warning',
                    'bg-blue-50 border-blue-500': toast.type === 'info'
                }"
                class="flex items-start p-4 rounded-lg shadow-lg border-l-4 max-w-md"
            >
                <!-- Icon -->
                <div class="flex-shrink-0">
                    <template x-if="toast.type === 'success'">
                        <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                    <template x-if="toast.type === 'warning'">
                        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                </div>

                <!-- Message -->
                <div class="ml-3 flex-1">
                    <p x-text="toast.message" 
                       :class="{
                           'text-green-800': toast.type === 'success',
                           'text-red-800': toast.type === 'error',
                           'text-yellow-800': toast.type === 'warning',
                           'text-blue-800': toast.type === 'info'
                       }"
                       class="text-sm font-medium">
                    </p>
                </div>

                <!-- Close Button -->
                <button @click="remove(toast.id)" class="ml-3 flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <!-- 🔥 LOADING OVERLAY COMPONENT -->
    <div x-data="{ loading: false }" 
         @loading.window="loading = $event.detail"
         x-show="loading"
         x-cloak
         class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
    >
        <div class="bg-white rounded-lg p-6 flex flex-col items-center">
            <svg class="animate-spin h-12 w-12 text-indigo-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-700 font-medium">Memproses...</p>
        </div>
    </div>

    <!-- 🔥 CONFIRM DIALOG COMPONENT -->
    <div x-data="{ 
            show: false, 
            title: '', 
            message: '', 
            confirmText: 'Hapus',
            cancelText: 'Batal',
            confirmClass: 'bg-red-600 hover:bg-red-700',
            onConfirm: null,
            
            open(options) {
                this.title = options.title || 'Konfirmasi';
                this.message = options.message || 'Apakah Anda yakin?';
                this.confirmText = options.confirmText || 'Hapus';
                this.cancelText = options.cancelText || 'Batal';
                this.confirmClass = options.confirmClass || 'bg-red-600 hover:bg-red-700';
                this.onConfirm = options.onConfirm || null;
                this.show = true;
            },
            
            confirm() {
                if (this.onConfirm && typeof this.onConfirm === 'function') {
                    this.onConfirm();
                }
                this.show = false;
            },
            
            cancel() {
                this.show = false;
            }
        }"
        @confirm-dialog.window="open($event.detail)"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto">
        
        <!-- Backdrop -->
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
             @click="cancel()"></div>

        <!-- Dialog -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                 @click.away="cancel()">
                
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        
                        <!-- Icon -->
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900" x-text="title"></h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" x-text="message"></p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                    <button type="button"
                            @click="confirm()"
                            :class="confirmClass"
                            class="inline-flex w-full justify-center rounded-lg px-4 py-2.5 text-sm font-semibold text-white shadow-sm sm:w-auto transition-colors">
                        <span x-text="confirmText"></span>
                    </button>
                    <button type="button"
                            @click="cancel()"
                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                        <span x-text="cancelText"></span>
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- 🔥 GLOBAL SCRIPTS -->
    <script>
        // Toast Manager
        function toastManager() {
            return {
                toasts: [],
                counter: 0,
                show(data) {
                    const id = this.counter++;
                    this.toasts.push({
                        id: id,
                        type: data.type || 'info',
                        message: data.message,
                        visible: true
                    });

                    setTimeout(() => {
                        this.remove(id);
                    }, data.duration || 5000);
                },
                remove(id) {
                    const index = this.toasts.findIndex(t => t.id === id);
                    if (index !== -1) {
                        this.toasts[index].visible = false;
                        setTimeout(() => {
                            this.toasts.splice(index, 1);
                        }, 300);
                    }
                }
            }
        }

        // Show Toast Helper
        function showToast(type, message, duration = 5000) {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: { type, message, duration }
            }));
        }

        // Show Loading
        function showLoading() {
            window.dispatchEvent(new CustomEvent('loading', { detail: true }));
        }

        // Hide Loading
        function hideLoading() {
            window.dispatchEvent(new CustomEvent('loading', { detail: false }));
        }

        // 🔥 Confirm Delete Helper
        function confirmDelete(formId, itemName = 'item ini') {
            window.dispatchEvent(new CustomEvent('confirm-dialog', {
                detail: {
                    title: 'Konfirmasi Hapus',
                    message: `Apakah Anda yakin ingin menghapus ${itemName}? Tindakan ini tidak dapat dibatalkan.`,
                    confirmText: 'Ya, Hapus',
                    cancelText: 'Batal',
                    confirmClass: 'bg-red-600 hover:bg-red-700',
                    onConfirm: () => {
                        showLoading();
                        document.getElementById(formId).submit();
                    }
                }
            }));
        }

        // Auto-show toast from session
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showToast('success', '{{ session('success') }}');
            @endif

            @if(session('error'))
                showToast('error', '{{ session('error') }}');
            @endif

            @if(session('warning'))
                showToast('warning', '{{ session('warning') }}');
            @endif

            @if(session('info'))
                showToast('info', '{{ session('info') }}');
            @endif

            @if($errors->any())
                showToast('error', '{{ $errors->first() }}');
            @endif
        });
    </script>

    @stack('scripts')
</body>
</html>