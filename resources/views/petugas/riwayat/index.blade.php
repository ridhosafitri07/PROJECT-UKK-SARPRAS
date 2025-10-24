@extends('layouts.petugas')

@section('title', 'Riwayat Pengaduan')
@section('header', 'Riwayat Pengaduan')
@section('subheader', 'Histori pengaduan yang sudah selesai')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-history text-purple-500 mr-2"></i>
                    Riwayat Pekerjaan
                </h3>
                <p class="text-sm text-gray-600 mt-1">Daftar pengaduan yang sudah diselesaikan</p>
            </div>
            <div class="flex space-x-2">
                <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 flex items-center shadow-sm">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
                <button class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 flex items-center shadow-md">
                    <i class="fas fa-file-export mr-2"></i>
                    Export
                </button>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Tgl. Pengajuan
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Pengadu
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Judul
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Lokasi
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Tgl. Selesai
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($riwayat as $item)
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
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-purple-600 font-bold text-xs">{{ strtoupper(substr($item->user->nama_pengguna, 0, 2)) }}</span>
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
                        <span class="text-sm text-gray-600 flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            {{ date('d/m/Y', strtotime($item->tgl_selesai)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('petugas.riwayat.show', $item) }}" 
                           class="text-purple-600 hover:text-purple-900 inline-flex items-center">
                            <i class="fas fa-eye mr-1"></i>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500 font-medium">Belum ada riwayat pengaduan</p>
                        <p class="text-gray-400 text-sm mt-2">Riwayat pekerjaan akan muncul di sini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $riwayat->links() }}
    </div>
</div>
@endsection