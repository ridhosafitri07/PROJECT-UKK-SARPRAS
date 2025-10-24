<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Item;
use App\Models\Pengaduan;
use App\Models\TemporaryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total counts
        $totalUsers = User::count();
        $totalItems = Item::count();
        $totalPengaduan = Pengaduan::count();
        $pendingItems = TemporaryItem::where('status_permintaan', 'Menunggu Persetujuan')->count();

        // Get monthly pengaduan statistics
        $pengaduanStats = [];
        for ($i = 1; $i <= 12; $i++) {
            $pengaduanStats[$i] = Pengaduan::whereYear('tgl_pengajuan', date('Y'))
                ->whereMonth('tgl_pengajuan', $i)
                ->count();
        }

        // Get item status statistics
        $itemStats = [
            'Menunggu Persetujuan' => TemporaryItem::where('status_permintaan', 'Menunggu Persetujuan')->count(),
            'Disetujui' => TemporaryItem::where('status_permintaan', 'Disetujui')->count(),
            'Ditolak' => TemporaryItem::where('status_permintaan', 'Ditolak')->count(),
        ];

        // Get recent activities
        $recentActivities = collect();

        // Get recent pengaduan
        $recentPengaduan = Pengaduan::with(['user'])
            ->orderBy('tgl_pengajuan', 'desc')
            ->take(5)
            ->get()
            ->map(function ($pengaduan) {
                return (object)[
                    'type' => 'pengaduan',
                    'description' => "Pengaduan baru dari " . $pengaduan->user->nama_pengguna,
                    'created_at' => $pengaduan->tgl_pengajuan
                ];
            });
        
        $recentActivities = $recentActivities->concat($recentPengaduan);

        // Get recent item approvals
        $recentApprovals = TemporaryItem::where('status_permintaan', 'Disetujui')
            ->orderBy('tanggal_persetujuan', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return (object)[
                    'type' => 'approval',
                    'description' => "Item " . $item->nama_barang_baru . " telah disetujui",
                    'created_at' => $item->tanggal_persetujuan
                ];
            });
        
        $recentActivities = $recentActivities->concat($recentApprovals)
            ->sortByDesc('created_at')
            ->take(5);

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalItems',
            'totalPengaduan',
            'pendingItems',
            'pengaduanStats',
            'itemStats',
            'recentActivities'
        ));
    }
}