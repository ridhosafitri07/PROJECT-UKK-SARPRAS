<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\TemporaryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPengaduanController extends Controller
{
    public function index()
    {
        $pengaduan = Pengaduan::with('user')
            ->orderBy('tgl_pengajuan', 'desc')
            ->paginate(10);

        $statistics = [
            'total' => Pengaduan::count(),
            'diajukan' => Pengaduan::where('status', 'Diajukan')->count(),
            'diproses' => Pengaduan::whereIn('status', ['Disetujui', 'Diproses'])->count(),
            'selesai' => Pengaduan::where('status', 'Selesai')->count(),
            'ditolak' => Pengaduan::where('status', 'Ditolak')->count(),
        ];

        return view('admin.pengaduan.index', compact('pengaduan', 'statistics'));
    }

    public function show(Pengaduan $pengaduan)
    {
        $pengaduan->load(['user', 'temporary_items']);
        return view('admin.pengaduan.show', compact('pengaduan'));
    }

    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak,Diproses,Selesai',
            'catatan_admin' => 'required|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            $pengaduan->status = $request->status;
            $pengaduan->catatan_admin = $request->catatan_admin;
            
            if ($request->status === 'Disetujui') {
                $pengaduan->tgl_verifikasi = now();
            } elseif ($request->status === 'Selesai') {
                $pengaduan->tgl_selesai = now();
            }
            
            $pengaduan->save();

            if ($request->status === 'Disetujui' && $pengaduan->temporary_items->count() > 0) {
                foreach ($pengaduan->temporary_items as $item) {
                    $item->status_permintaan = 'Disetujui';
                    $item->tanggal_persetujuan = now();
                    $item->catatan_admin = $request->catatan_admin;
                    $item->save();
                }
            }

            DB::commit();
            return redirect()
                ->route('admin.pengaduan.show', $pengaduan)
                ->with('success', 'Status pengaduan berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui status: ' . $e->getMessage());
        }
    }
}
