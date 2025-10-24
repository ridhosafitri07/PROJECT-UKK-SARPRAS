@extends('layouts.admin')

@section('title', 'Manajemen Sarana Prasarana')
@section('header', 'Manajemen Sarana Prasarana')
@section('subheader', 'Kelola permintaan dan inventori sarpras')

@section('content')
<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-blue-100 uppercase">Total Permintaan</p>
                <p class="text-3xl font-bold mt-2">{{ $total_permintaan }}</p>
                <p class="text-xs text-blue-100 mt-2 flex items-center">
                    <i class="fas fa-chart-line mr-1"></i>
                    Semua permintaan
                </p>
            </div>
            <div class="w-16 h-16 bg-blue-400 rounded-full flex items-center justify-center">
                <i class="fas fa-clipboard-list text-3xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-yellow-100 uppercase">Menunggu</p>
                <p class="text-3xl font-bold mt-2">{{ $menunggu_persetujuan }}</p>
                <p class="text-xs text-yellow-100 mt-2 flex items-center">
                    <i class="fas fa-clock mr-1"></i>
                    Perlu direview
                </p>
            </div>
            <div class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center">
                <i class="fas fa-hourglass-half text-3xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-green-100 uppercase">Disetujui</p>
                <p class="text-3xl font-bold mt-2">{{ $disetujui }}</p>
                <p class="text-xs text-green-100 mt-2 flex items-center">
                    <i class="fas fa-check mr-1"></i>
                    Telah diapprove
                </p>
            </div>
            <div class="w-16 h-16 bg-green-400 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-3xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-red-100 uppercase">Ditolak</p>
                <p class="text-3xl font-bold mt-2">{{ $ditolak }}</p>
                <p class="text-xs text-red-100 mt-2 flex items-center">
                    <i class="fas fa-times mr-1"></i>
                    Tidak disetujui
                </p>
            </div>
            <div class="w-16 h-16 bg-red-400 rounded-full flex items-center justify-center">
                <i class="fas fa-times-circle text-3xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Permintaan Terbaru -->
<div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-list-alt text-blue-500 mr-2"></i>
                    Permintaan Terbaru
                </h3>
                <p class="text-sm text-gray-600 mt-1">5 permintaan terbaru yang masuk</p>
            </div>
            <a href="{{ route('admin.sarpras.permintaan-list') }}" 
               class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 flex items-center shadow-md transform hover:scale-105">
                <i class="fas fa-th-list mr-2"></i>
                Lihat Semua
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Barang
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Jumlah
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($permintaan_terbaru as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="far fa-calendar text-gray-400 mr-2"></i>
                            {{ $item->tanggal_permintaan->format('d/m/Y') }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-box text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $item->nama_barang }}</p>
                                <p class="text-xs text-gray-500">{{ Str::limit($item->spesifikasi ?? '-', 30) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full">
                            {{ $item->jumlah }} unit
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($item->status_permintaan === 'Menunggu Persetujuan') bg-yellow-100 text-yellow-800
                            @elseif($item->status_permintaan === 'Disetujui') bg-green-100 text-green-800
                            @elseif($item->status_permintaan === 'Ditolak') bg-red-100 text-red-800
                            @endif">
                            {{ $item->status_permintaan }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.sarpras.show-permintaan', $item->id_item) }}" 
                           class="text-blue-600 hover:text-blue-900 inline-flex items-center font-medium">
                            <i class="fas fa-eye mr-1"></i>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500 font-medium">Tidak ada permintaan terbaru</p>
                        <p class="text-gray-400 text-sm mt-2">Permintaan baru akan muncul di sini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Links -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow">
        <div class="p-6 bg-gradient-to-r from-blue-500 to-indigo-600">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-bolt mr-2"></i>
                Menu Cepat
            </h3>
            <p class="text-blue-100 text-sm mt-1">Akses cepat ke fitur utama</p>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                <a href="{{ route('admin.sarpras.permintaan-list') }}" 
                   class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg hover:shadow-md transition-all group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-clipboard-list text-white"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Daftar Permintaan</p>
                            <p class="text-xs text-gray-600">Lihat semua permintaan</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-blue-500 group-hover:translate-x-2 transition-transform"></i>
                </a>
                
                <a href="{{ route('admin.sarpras.history') }}" 
                   class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg hover:shadow-md transition-all group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-history text-white"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Riwayat Permintaan</p>
                            <p class="text-xs text-gray-600">History approval</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-green-500 group-hover:translate-x-2 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow">
        <div class="p-6 bg-gradient-to-r from-purple-500 to-pink-600">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-chart-bar mr-2"></i>
                Statistik Bulan Ini
            </h3>
            <p class="text-purple-100 text-sm mt-1">Ringkasan aktivitas</p>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-inbox text-blue-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Permintaan Masuk</span>
                    </div>
                    <span class="text-lg font-bold text-blue-600">{{ $total_permintaan }}</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Disetujui</span>
                    </div>
                    <span class="text-lg font-bold text-green-600">{{ $disetujui }}</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Pending</span>
                    </div>
                    <span class="text-lg font-bold text-yellow-600">{{ $menunggu_persetujuan }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection