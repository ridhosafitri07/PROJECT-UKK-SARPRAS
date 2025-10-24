@extends('layouts.petugas')

@section('title', 'Tugas Pengaduan')
@section('header', 'Tugas Pengaduan')
@section('subheader', 'Kelola dan tangani pengaduan yang masuk')

@section('content')
<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-blue-100">Total Tugas</p>
                <p class="text-3xl font-bold mt-2">{{ $statistics['total'] }}</p>
            </div>
            <i class="fas fa-clipboard-list text-4xl text-blue-200"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-yellow-100">Perlu Dikerjakan</p>
                <p class="text-3xl font-bold mt-2">{{ $statistics['disetujui'] }}</p>
            </div>
            <i class="fas fa-exclamation-circle text-4xl text-yellow-200"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-green-100">Sedang Diproses</p>
                <p class="text-3xl font-bold mt-2">{{ $statistics['diproses'] }}</p>
            </div>
            <i class="fas fa-cog text-4xl text-green-200"></i>
        </div>
    </div>
</div>

<!-- Pengaduan List -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-list text-green-500 mr-2"></i>
                Daftar Tugas Pengaduan
            </h3>
            <div class="flex space-x-2">
                <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 flex items-center">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Pengadu
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Judul
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Lokasi
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($pengaduan as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-bold text-gray-900">#{{ $item->id_pengaduan }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-600 flex items-center">
                            <i class="far fa-calendar text-gray-400 mr-2"></i>
                            {{ date('d/m/Y', strtotime($item->tgl_pengajuan)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-green-600 font-bold text-xs">{{ strtoupper(substr($item->user->nama_pengguna, 0, 2)) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $item->user->nama_pengguna }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 font-medium">{{ Str::limit($item->nama_pengaduan, 40) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-600 flex items-center">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                            {{ Str::limit($item->lokasi, 20) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($item->status === 'Disetujui') bg-yellow-100 text-yellow-800
                            @elseif($item->status === 'Diproses') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('petugas.pengaduan.show', $item) }}" 
                           class="text-green-600 hover:text-green-900 inline-flex items-center">
                            <i class="fas fa-eye mr-1"></i>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500 font-medium">Tidak ada tugas pengaduan</p>
                        <p class="text-gray-400 text-sm mt-2">Tugas akan muncul di sini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $pengaduan->links() }}
    </div>
</div>
@endsection