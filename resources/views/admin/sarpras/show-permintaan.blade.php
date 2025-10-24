@extends('layouts.admin')

@section('title', 'Detail Permintaan')
@section('header', 'Detail Permintaan Sarpras #' . $permintaan->id_item)
@section('subheader', 'Informasi lengkap permintaan')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.sarpras.permintaan-list') }}" 
       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-50 transition-all">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Daftar
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Informasi Permintaan -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-blue-500 to-indigo-600">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informasi Permintaan
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">ID Permintaan</label>
                        <p class="text-lg font-bold text-gray-800 mt-1">#{{ $permintaan->id_item }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Tanggal Permintaan</label>
                        <p class="text-lg font-bold text-gray-800 mt-1 flex items-center">
                            <i class="far fa-calendar text-blue-500 mr-2"></i>
                            {{ $permintaan->tanggal_permintaan->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Status</label>
                        <div class="mt-2">
                            <span class="px-4 py-2 inline-flex text-sm font-bold rounded-full
                                @if($permintaan->status_permintaan === 'Menunggu Persetujuan') bg-yellow-100 text-yellow-800
                                @elseif($permintaan->status_permintaan === 'Disetujui') bg-green-100 text-green-800
                                @elseif($permintaan->status_permintaan === 'Ditolak') bg-red-100 text-red-800
                                @endif">
                                {{ $permintaan->status_permintaan }}
                            </span>
                        </div>
                    </div>
                    @if($permintaan->tanggal_persetujuan)
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Tanggal Persetujuan</label>
                        <p class="text-lg font-bold text-gray-800 mt-1 flex items-center">
                            <i class="far fa-check-circle text-green-500 mr-2"></i>
                            {{ $permintaan->tanggal_persetujuan->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informasi Barang -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-green-500 to-emerald-600">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-box mr-2"></i>
                    Informasi Barang
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Nama Barang</label>
                        <p class="text-base font-bold text-gray-800 mt-1">{{ $permintaan->nama_barang }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Jumlah</label>
                        <p class="text-base font-bold text-gray-800 mt-1">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full">{{ $permintaan->jumlah }} unit</span>
                        </p>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase">Spesifikasi</label>
                    <p class="text-base text-gray-800 mt-1 leading-relaxed">{{ $permintaan->spesifikasi ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase">Alasan Penggantian</label>
                    <p class="text-base text-gray-800 mt-1 leading-relaxed">{{ $permintaan->alasan_penggantian ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Form Persetujuan -->
        @if($permintaan->status_permintaan === 'Menunggu Persetujuan')
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-purple-500 to-pink-600">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-tasks mr-2"></i>
                    Tindakan
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.sarpras.update-status', $permintaan->id_item) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-3">Pilih Status</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center p-4 bg-green-50 border-2 border-green-200 rounded-lg cursor-pointer hover:bg-green-100 transition-colors">
                                    <input type="radio" name="status" value="Disetujui" class="form-radio text-green-600 h-5 w-5" required>
                                    <span class="ml-3 flex items-center">
                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                        <span class="text-sm font-semibold text-green-800">Setujui</span>
                                    </span>
                                </label>
                                <label class="relative flex items-center p-4 bg-red-50 border-2 border-red-200 rounded-lg cursor-pointer hover:bg-red-100 transition-colors">
                                    <input type="radio" name="status" value="Ditolak" class="form-radio text-red-600 h-5 w-5" required>
                                    <span class="ml-3 flex items-center">
                                        <i class="fas fa-times-circle text-red-600 mr-2"></i>
                                        <span class="text-sm font-semibold text-red-800">Tolak</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="catatan_admin" class="block text-sm font-bold text-gray-700 mb-2">
                                Catatan Admin *
                            </label>
                            <textarea id="catatan_admin" name="catatan_admin" rows="4" 
                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                      placeholder="Tambahkan catatan untuk permintaan ini..." required></textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="window.history.back()"
                                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all flex items-center">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Keputusan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Timeline -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-indigo-500 to-purple-600">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-history mr-2"></i>
                    Timeline
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-paper-plane text-white"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-semibold text-gray-800">Permintaan Dibuat</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $permintaan->tanggal_permintaan->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($permintaan->tanggal_persetujuan)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 
                            @if($permintaan->status_permintaan === 'Disetujui') bg-green-500
                            @else bg-red-500 @endif 
                            rounded-full flex items-center justify-center">
                            <i class="fas fa-{{ $permintaan->status_permintaan === 'Disetujui' ? 'check' : 'times' }} text-white"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-semibold text-gray-800">{{ $permintaan->status_permintaan }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $permintaan->tanggal_persetujuan->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Catatan Admin -->
        @if($permintaan->catatan_admin)
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-yellow-500 to-orange-600">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-sticky-note mr-2"></i>
                    Catatan Admin
                </h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-800 leading-relaxed">{{ $permintaan->catatan_admin }}</p>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-gray-700 to-gray-900">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-bolt mr-2"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="p-4 space-y-2">
                <a href="{{ route('admin.sarpras.permintaan-list') }}" 
                   class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                    <span class="text-sm font-medium text-gray-700 flex items-center">
                        <i class="fas fa-list text-gray-500 mr-2"></i>
                        Lihat Semua Permintaan
                    </span>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="{{ route('admin.sarpras.history') }}" 
                   class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                    <span class="text-sm font-medium text-gray-700 flex items-center">
                        <i class="fas fa-history text-gray-500 mr-2"></i>
                        Riwayat
                    </span>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection