@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard Overview')
@section('subheader', 'Welcome back, ' . auth()->user()->nama_pengguna)

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Pengaduan Card -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-xl p-6 border-l-4 border-blue-500 transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-500 uppercase">Total Pengaduan</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPengaduan ?? 0 }}</p>
                <p class="text-xs text-green-500 mt-2 flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>12% dari bulan lalu</span>
                </p>
            </div>
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-clipboard-list text-3xl text-blue-500"></i>
            </div>
        </div>
    </div>

    <!-- Pending Items Card -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-xl p-6 border-l-4 border-yellow-500 transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-500 uppercase">Menunggu Approval</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $pendingItems ?? 0 }}</p>
                <p class="text-xs text-yellow-600 mt-2 flex items-center">
                    <i class="fas fa-clock mr-1"></i>
                    <span>Butuh perhatian</span>
                </p>
            </div>
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-hourglass-half text-3xl text-yellow-500"></i>
            </div>
        </div>
    </div>

    <!-- Total Users Card -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-xl p-6 border-l-4 border-green-500 transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-500 uppercase">Total Users</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalUsers ?? 0 }}</p>
                <p class="text-xs text-green-500 mt-2 flex items-center">
                    <i class="fas fa-user-plus mr-1"></i>
                    <span>5 users baru</span>
                </p>
            </div>
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-3xl text-green-500"></i>
            </div>
        </div>
    </div>

    <!-- Total Items Card -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-xl p-6 border-l-4 border-purple-500 transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-500 uppercase">Total Items</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalItems ?? 0 }}</p>
                <p class="text-xs text-purple-500 mt-2 flex items-center">
                    <i class="fas fa-box mr-1"></i>
                    <span>Inventory aktif</span>
                </p>
            </div>
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-boxes text-3xl text-purple-500"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Pengaduan Chart -->
    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                Statistik Pengaduan
            </h3>
            <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">2025</span>
        </div>
        <div class="relative" style="height: 300px;">
            <canvas id="pengaduanChart"></canvas>
        </div>
    </div>

    <!-- Items Status Chart -->
    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-chart-pie text-purple-500 mr-2"></i>
                Status Item
            </h3>
            <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Total</span>
        </div>
        <div class="relative" style="height: 300px;">
            <canvas id="itemStatusChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-history text-indigo-500 mr-2"></i>
            Aktivitas Terbaru
        </h3>
        <a href="#" class="text-blue-500 hover:text-blue-700 text-sm font-medium flex items-center">
            Lihat Semua
            <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
    <div class="space-y-4 max-h-96 overflow-y-auto">
        @forelse($recentActivities ?? [] as $activity)
        <div class="flex items-start p-4 bg-gradient-to-r from-gray-50 to-white rounded-lg hover:shadow-md border border-gray-100 transition-all">
            <div class="flex-shrink-0">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full shadow-md
                    @if($activity->type == 'pengaduan') bg-gradient-to-br from-blue-400 to-blue-600 text-white
                    @elseif($activity->type == 'approval') bg-gradient-to-br from-green-400 to-green-600 text-white
                    @else bg-gradient-to-br from-gray-400 to-gray-600 text-white @endif">
                    <i class="fas fa-{{ $activity->type == 'pengaduan' ? 'clipboard-list' : ($activity->type == 'approval' ? 'check-circle' : 'info-circle') }} text-lg"></i>
                </span>
            </div>
            <div class="ml-4 flex-1">
                <p class="text-sm font-semibold text-gray-800">{{ $activity->description ?? 'Aktivitas' }}</p>
                <p class="text-xs text-gray-500 mt-1 flex items-center">
                    <i class="far fa-clock mr-1"></i>
                    {{ isset($activity->created_at) ? \Carbon\Carbon::parse($activity->created_at)->diffForHumans() : now()->diffForHumans() }}
                </p>
            </div>
            <button class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-ellipsis-v"></i>
            </button>
        </div>
        @empty
        <div class="text-center py-12">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500 font-medium">Belum ada aktivitas terbaru</p>
            <p class="text-gray-400 text-sm mt-2">Aktivitas akan muncul di sini</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pengaduan Chart
    const pengaduanCtx = document.getElementById('pengaduanChart');
    if (pengaduanCtx) {
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const pengaduanData = @json($pengaduanStats ?? []);
        
        new Chart(pengaduanCtx, {
            type: 'line',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Pengaduan per Bulan',
                    data: monthNames.map((_, index) => pengaduanData[index + 1] || 0),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,
                                family: 'Inter, sans-serif'
                            },
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }

    // Item Status Chart
    const itemStatusCtx = document.getElementById('itemStatusChart');
    if (itemStatusCtx) {
        const itemStatusData = @json($itemStats ?? []);
        
        new Chart(itemStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Menunggu Persetujuan', 'Disetujui', 'Ditolak'],
                datasets: [{
                    data: [
                        itemStatusData['Menunggu Persetujuan'] || 0,
                        itemStatusData['Disetujui'] || 0,
                        itemStatusData['Ditolak'] || 0
                    ],
                    backgroundColor: [
                        'rgb(234, 179, 8)',
                        'rgb(34, 197, 94)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 12,
                                family: 'Inter, sans-serif'
                            },
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8
                    }
                }
            }
        });
    }
});
</script>
@endpush