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
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header with Gradient -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8">
            <div class="flex items-center space-x-4">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center text-3xl font-bold text-blue-600 shadow-lg">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
                
                <!-- User Info -->
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-white mb-1">{{ $user->name }}</h1>
                    <p class="text-blue-100 text-sm">{{ $user->email }}</p>
                    <div class="mt-3 flex items-center space-x-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/20 text-white backdrop-blur-sm">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            {{ ucfirst($user->role) }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-400/30 text-white' : 'bg-red-400/30 text-white' }} backdrop-blur-sm">
                            <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-white' : 'bg-white' }} mr-1.5"></span>
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex-shrink-0">
                    <a href="{{ route('users.edit', $user) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit User
                    </a>
                </div>
            </div>
        </div>

        <!-- User Details -->
        <div class="px-6 py-5 border-b border-gray-200">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Nama Lengkap</dt>
                    <dd class="text-base text-gray-900">{{ $user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Email</dt>
                    <dd class="text-base text-gray-900">{{ $user->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Role</dt>
                    <dd>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Status</dt>
                    <dd>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-green-600' : 'bg-red-600' }} mr-1.5"></span>
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Bergabung Sejak</dt>
                    <dd class="text-base text-gray-900">{{ $user->created_at->format('d M Y, H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Terakhir Diupdate</dt>
                    <dd class="text-base text-gray-900">{{ $user->updated_at->format('d M Y, H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Activity Log Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Riwayat Aktivitas</h2>
                        <p class="text-sm text-gray-500">Track semua aktivitas user di sistem</p>
                    </div>
                </div>
                
                <!-- Statistics -->
                <div class="flex items-center space-x-6 text-sm">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ $activityStats['total'] ?? 0 }}</div>
                        <div class="text-xs text-gray-500">Total Aktivitas</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $activityStats['create'] ?? 0 }}</div>
                        <div class="text-xs text-gray-500">Create</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $activityStats['update'] ?? 0 }}</div>
                        <div class="text-xs text-gray-500">Update</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600">{{ $activityStats['delete'] ?? 0 }}</div>
                        <div class="text-xs text-gray-500">Delete</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="px-6 py-4 bg-white border-b border-gray-200">
            <div class="flex items-center space-x-2 overflow-x-auto">
                <button onclick="filterActivities('all')" 
                        class="filter-tab active px-4 py-2 rounded-lg text-sm font-medium transition-all whitespace-nowrap">
                    Semua
                </button>
                <button onclick="filterActivities('Supplier')" 
                        class="filter-tab px-4 py-2 rounded-lg text-sm font-medium transition-all whitespace-nowrap">
                    <svg class="w-4 h-4 inline mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    Supplier
                </button>
                <button onclick="filterActivities('Criteria')" 
                        class="filter-tab px-4 py-2 rounded-lg text-sm font-medium transition-all whitespace-nowrap">
                    <svg class="w-4 h-4 inline mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Kriteria
                </button>
                <button onclick="filterActivities('CriteriaComparison')" 
                        class="filter-tab px-4 py-2 rounded-lg text-sm font-medium transition-all whitespace-nowrap">
                    <svg class="w-4 h-4 inline mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    AHP
                </button>
                <button onclick="filterActivities('SupplierAssessment')" 
                        class="filter-tab px-4 py-2 rounded-lg text-sm font-medium transition-all whitespace-nowrap">
                    <svg class="w-4 h-4 inline mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    Penilaian
                </button>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="px-6 py-4">
            @if($activities->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-gray-500 text-sm">Belum ada aktivitas yang tercatat</p>
                </div>
            @else
                <div class="space-y-4" id="activity-list">
                    @foreach($activities as $log)
                        <div class="activity-item border-l-4 {{ $log->action_badge_color }} bg-gray-50 rounded-r-lg p-4 hover:shadow-md transition-all duration-200" 
                             data-model="{{ $log->model }}">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-3 flex-1">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg {{ $log->action_badge_color }} bg-opacity-10 flex items-center justify-center">
                                        {!! $log->action_icon !!}
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <!-- Action & Model -->
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $log->action_badge_color }} bg-opacity-10">
                                                {{ $log->action_text }}
                                            </span>
                                            <span class="text-xs text-gray-500">•</span>
                                            <span class="text-xs font-medium text-gray-700">{{ $log->model_name }}</span>
                                        </div>
                                        
                                        <!-- Description -->
                                        <p class="text-sm text-gray-900 mb-2">{{ $log->description }}</p>
                                        
                                        <!-- Changes (if any) -->
                                        @if($log->hasValueChanges())
                                            <div class="mt-3 space-y-2">
                                                @foreach($log->changes_summary as $change)
                                                    <div class="flex items-start space-x-2 text-xs bg-white rounded-lg p-3 border border-gray-200">
                                                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                        </svg>
                                                        <div class="flex-1 min-w-0">
                                                            <span class="font-medium text-gray-700">{{ $change['field'] }}:</span>
                                                            <div class="flex items-center space-x-2 mt-1">
                                                                <span class="inline-flex items-center px-2 py-1 rounded bg-red-50 text-red-700 border border-red-200">
                                                                    {{ $change['old'] }}
                                                                </span>
                                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                                </svg>
                                                                <span class="inline-flex items-center px-2 py-1 rounded bg-green-50 text-green-700 border border-green-200">
                                                                    {{ $change['new'] }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        
                                        <!-- IP & Browser Info -->
                                        @if($log->ip_address || $log->user_agent)
                                            <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                                @if($log->ip_address)
                                                    <span class="flex items-center">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ $log->ip_address }}
                                                    </span>
                                                @endif
                                                @if($log->user_agent)
                                                    <span class="flex items-center truncate max-w-xs">
                                                        <svg class="w-3.5 h-3.5 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ Str::limit($log->user_agent, 50) }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Timestamp -->
                                <div class="flex-shrink-0 text-right ml-4">
                                    <div class="text-xs font-medium text-gray-900">{{ $log->time_ago }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $log->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $log->created_at->format('H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    {{ $activities->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Filter Script -->
<script>
function filterActivities(model) {
    // Update active tab
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('active', 'bg-blue-600', 'text-white', 'shadow-md');
        tab.classList.add('text-gray-600', 'hover:bg-gray-100');
    });
    
    event.target.classList.remove('text-gray-600', 'hover:bg-gray-100');
    event.target.classList.add('active', 'bg-blue-600', 'text-white', 'shadow-md');
    
    // Filter activities
    const items = document.querySelectorAll('.activity-item');
    items.forEach(item => {
        if (model === 'all' || item.dataset.model === model) {
            item.style.display = 'block';
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, 10);
        } else {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';
            setTimeout(() => {
                item.style.display = 'none';
            }, 200);
        }
    });
}

// Style untuk smooth transition
document.addEventListener('DOMContentLoaded', function() {
    const items = document.querySelectorAll('.activity-item');
    items.forEach(item => {
        item.style.transition = 'all 0.2s ease-in-out';
    });
    
    // Active tab styling
    const activeTabs = document.querySelectorAll('.filter-tab.active');
    activeTabs.forEach(tab => {
        tab.classList.add('bg-blue-600', 'text-white', 'shadow-md');
        tab.classList.remove('text-gray-600', 'hover:bg-gray-100');
    });
    
    const inactiveTabs = document.querySelectorAll('.filter-tab:not(.active)');
    inactiveTabs.forEach(tab => {
        tab.classList.add('text-gray-600', 'hover:bg-gray-100');
    });
});
</script>

<!-- Additional CSS for better styling -->
<style>
.filter-tab.active {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
}

.filter-tab:not(.active) {
    color: #4b5563;
}

.filter-tab:not(.active):hover {
    background-color: #f3f4f6;
}

.activity-item {
    transition: all 0.2s ease-in-out;
}

.activity-item:hover {
    transform: translateX(4px);
}

/* Color classes for actions */
.border-green-500 { border-left-color: #10b981; }
.border-blue-500 { border-left-color: #3b82f6; }
.border-yellow-500 { border-left-color: #f59e0b; }
.border-red-500 { border-left-color: #ef4444; }
.border-purple-500 { border-left-color: #8b5cf6; }
.border-indigo-500 { border-left-color: #6366f1; }

.bg-green-500 { background-color: #10b981; }
.bg-blue-500 { background-color: #3b82f6; }
.bg-yellow-500 { background-color: #f59e0b; }
.bg-red-500 { background-color: #ef4444; }
.bg-purple-500 { background-color: #8b5cf6; }
.bg-indigo-500 { background-color: #6366f1; }

.text-green-500 { color: #10b981; }
.text-blue-500 { color: #3b82f6; }
.text-yellow-500 { color: #f59e0b; }
.text-red-500 { color: #ef4444; }
.text-purple-500 { color: #8b5cf6; }
.text-indigo-500 { color: #6366f1; }
</style>
@endsection