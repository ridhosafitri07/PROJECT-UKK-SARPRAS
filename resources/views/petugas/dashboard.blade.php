@extends('layouts.petugas')

@section('title', 'Dashboard Petugas')
@section('header', 'Dashboard Petugas')
@section('subheader', 'Selamat datang, ' . auth()->user()->nama_pengguna)

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Tugas Card -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-xl p-6 border-l-4 border-green-500 transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-500 uppercase">Total Tugas</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalTugas ?? 0 }}</p>
                <p class="text-xs text-green-500 mt-2 flex items-center">
                    <i class="fas fa-tasks mr-1"></i>
                    <span>Semua pengaduan</span>
                </p>
            </div>
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-clipboard-list text-3xl text-green-500"></i>
            </div>
        </div>
    </div>

    <!-- Tugas Aktif Card -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-xl p-6 border-l-4 border-blue-500 transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-500 uppercase">Tugas Aktif</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $tugasAktif ?? 0 }}</p>
                <p class="text-xs text-blue-600 mt-2 flex items-center">
                    <i class="fas fa-spinner mr-1"></i>
                    <span>Sedang dikerjakan</span>
                </p>
            </div>
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-cog text-3xl text-blue-500"></i>
            </div>
        </div>
    </div>

    <!-- Tugas Selesai Card -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-xl p-6 border-l-4 border-purple-500 transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-500 uppercase">Tugas Selesai</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $tugasSelesai ?? 0 }}</p>
                <p class="text-xs text-purple-500 mt-2 flex items-center">
                    <i class="fas fa-check-double mr-1"></i>
                    <span>Bulan ini</span>
                </p>
            </div>
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-3xl text-purple-500"></i>
            </div>
        </div>
    </div>

    <!-- Pending Review Card -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-xl p-6 border-l-4 border-yellow-500 transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-500 uppercase">Perlu Tindakan</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $perluTindakan ?? 0 }}</p>
                <p class="text-xs text-yellow-600 mt-2 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <span>Segera tangani</span>
                </p>
            </div>
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-bell text-3xl text-yellow-500"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Tugas Terbaru -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-clipboard-check text-green-500 mr-2"></i>
                        Tugas Terbaru
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Pengaduan yang perlu ditangani</p>
                </div>
                <a href="{{ route('petugas.pengaduan.index') }}" 
                   class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 flex items-center shadow-md transform hover:scale-105">
                    <i class="fas fa-list mr-2"></i>
                    Lihat Semua
                </a>
            </div>
        </div>
        
        <div class="p-6">
            <div class="space-y-4">
                @forelse($tugasTerbaru ?? [] as $tugas)
                <div class="flex items-start p-4 bg-gradient-to-r from-gray-50 to-white rounded-lg hover:shadow-md border border-gray-100 transition-all">
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-full shadow-md
                            @if($tugas->status == 'Disetujui') bg-gradient-to-br from-blue-400 to-blue-600 text-white
                            @elseif($tugas->status == 'Diproses') bg-gradient-to-br from-green-400 to-green-600 text-white
                            @else bg-gradient-to-br from-gray-400 to-gray-600 text-white @endif">
                            <i class="fas fa-{{ $tugas->status == 'Disetujui' ? 'check' : ($tugas->status == 'Diproses' ? 'cog' : 'info-circle') }} text-lg"></i>
                        </span>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-bold text-gray-800">{{ $tugas->nama_pengaduan }}</h4>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @if($tugas->status == 'Disetujui') bg-blue-100 text-blue-800
                                @elseif($tugas->status == 'Diproses') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $tugas->status }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-600 mt-1">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            {{ $tugas->lokasi }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1 flex items-center">
                            <i class="far fa-clock mr-1"></i>
                            {{ \Carbon\Carbon::parse($tugas->tgl_pengajuan)->diffForHumans() }}
                        </p>
                        <div class="mt-3">
                            <a href="{{ route('petugas.pengaduan.show', $tugas) }}" 
                               class="text-xs text-green-600 hover:text-green-800 font-semibold">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500 font-medium">Belum ada tugas terbaru</p>
                    <p class="text-gray-400 text-sm mt-2">Tugas akan muncul di sini</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-6">
        <!-- Performance Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-purple-500 to-pink-600">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-chart-line mr-2"></i>
                    Performa Bulan Ini
                </h3>
                <p class="text-purple-100 text-sm mt-1">Statistik pekerjaan Anda</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Selesai</span>
                        </div>
                        <span class="text-lg font-bold text-green-600">{{ $tugasSelesai ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-spinner text-blue-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Proses</span>
                        </div>
                        <span class="text-lg font-bold text-blue-600">{{ $tugasAktif ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Pending</span>
                        </div>
                        <span class="text-lg font-bold text-yellow-600">{{ $perluTindakan ?? 0 }}</span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Completion Rate</span>
                        <span class="text-sm font-bold text-green-600">{{ $completionRate ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all" 
                             style="width: {{ $completionRate ?? 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-blue-500 to-indigo-600">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-bolt mr-2"></i>
                    Quick Actions
                </h3>
                <p class="text-blue-100 text-sm mt-1">Akses cepat</p>
            </div>
            <div class="p-4 space-y-2">
                <a href="{{ route('petugas.pengaduan.index') }}" 
                   class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                    <span class="text-sm font-medium text-gray-700 flex items-center">
                        <i class="fas fa-tasks text-green-500 mr-2"></i>
                        Lihat Semua Tugas
                    </span>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="{{ route('petugas.riwayat.index') }}" 
                   class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                    <span class="text-sm font-medium text-gray-700 flex items-center">
                        <i class="fas fa-history text-purple-500 mr-2"></i>
                        Riwayat Pekerjaan
                    </span>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection