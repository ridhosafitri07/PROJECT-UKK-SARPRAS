<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    private function getPetugasId()
    {
        $user = Auth::user();
        
        // Cara 1: Cari petugas berdasarkan id_user (jika sudah ada kolom id_user di table petugas)
        $petugas = Petugas::where('id_user', $user->id_user)->first();
        
        // Cara 2: Jika belum ada kolom id_user, cari berdasarkan nama
        if (!$petugas) {
            $petugas = Petugas::where('nama', $user->nama_pengguna)->first();
        }
        
        return $petugas ? $petugas->id_petugas : null;
    }
    
    public function dashboard()
    {
        $petugasId = $this->getPetugasId();
        
        if (!$petugasId) {
            return redirect()->back()->with('error', 'Akun petugas tidak ditemukan. Hubungi administrator.');
        }
        
        // Get statistics - HANYA untuk petugas ini
        $totalTugas = Pengaduan::where('id_petugas', $petugasId)
            ->whereIn('status', ['Disetujui', 'Diproses', 'Selesai'])
            ->count();
            
        $tugasAktif = Pengaduan::where('id_petugas', $petugasId)
            ->where('status', 'Diproses')
            ->count();
            
        $tugasSelesai = Pengaduan::where('id_petugas', $petugasId)
            ->where('status', 'Selesai')
            ->whereMonth('tgl_selesai', date('m'))
            ->whereYear('tgl_selesai', date('Y'))
            ->count();
            
        $perluTindakan = Pengaduan::where('id_petugas', $petugasId)
            ->where('status', 'Disetujui')
            ->count();
        
        // Calculate completion rate
        $totalAll = $tugasAktif + $tugasSelesai;
        $completionRate = $totalAll > 0 ? round(($tugasSelesai / $totalAll) * 100) : 0;
        
        // Get recent tasks - HANYA untuk petugas ini
        $tugasTerbaru = Pengaduan::with('user')
            ->where('id_petugas', $petugasId)
            ->whereIn('status', ['Disetujui', 'Diproses'])
            ->orderBy('tgl_pengajuan', 'desc')
            ->take(5)
            ->get();
        
        return view('petugas.dashboard', compact(
            'totalTugas',
            'tugasAktif',
            'tugasSelesai',
            'perluTindakan',
            'completionRate',
            'tugasTerbaru'
        ));
    }
    
    public function pengaduanIndex()
    {
        $petugasId = $this->getPetugasId();
        
        if (!$petugasId) {
            return redirect()->back()->with('error', 'Akun petugas tidak ditemukan. Hubungi administrator.');
        }
        
        // Filter HANYA pengaduan untuk petugas ini
        $pengaduan = Pengaduan::with('user')
            ->where('id_petugas', $petugasId)
            ->whereIn('status', ['Disetujui', 'Diproses'])
            ->orderBy('tgl_pengajuan', 'desc')
            ->paginate(10);
        
        $statistics = [
            'total' => Pengaduan::where('id_petugas', $petugasId)
                ->whereIn('status', ['Disetujui', 'Diproses'])
                ->count(),
            'disetujui' => Pengaduan::where('id_petugas', $petugasId)
                ->where('status', 'Disetujui')
                ->count(),
            'diproses' => Pengaduan::where('id_petugas', $petugasId)
                ->where('status', 'Diproses')
                ->count(),
        ];
        
        return view('petugas.pengaduan.index', compact('pengaduan', 'statistics'));
    }
    
    public function pengaduanShow(Pengaduan $pengaduan)
    {
        $petugasId = $this->getPetugasId();
        
        // Validasi: hanya bisa lihat pengaduan yang ditugaskan ke petugas ini
        if ($pengaduan->id_petugas != $petugasId) {
            return redirect()->route('petugas.pengaduan.index')
                ->with('error', 'Anda tidak memiliki akses ke pengaduan ini.');
        }
        
        $pengaduan->load('user');
        return view('petugas.pengaduan.show', compact('pengaduan'));
    }
    
    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $petugasId = $this->getPetugasId();
        
        // Validasi: hanya bisa update pengaduan yang ditugaskan ke petugas ini
        if ($pengaduan->id_petugas != $petugasId) {
            return redirect()->route('petugas.pengaduan.index')
                ->with('error', 'Anda tidak memiliki akses ke pengaduan ini.');
        }
        
        $request->validate([
            'status' => 'required|in:Diproses,Selesai',
            'saran_petugas' => 'nullable|string|max:255'
        ]);
        
        DB::beginTransaction();
        try {
            $pengaduan->status = $request->status;
            if ($request->saran_petugas) {
                $pengaduan->saran_petugas = $request->saran_petugas;
            }
            
            if ($request->status === 'Selesai') {
                $pengaduan->tgl_selesai = now();
            }
            
            $pengaduan->save();
            
            DB::commit();
            return redirect()
                ->route('petugas.pengaduan.show', $pengaduan)
                ->with('success', 'Status pengaduan berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui status');
        }
    }
    
    public function riwayatIndex()
    {
        $petugasId = $this->getPetugasId();
        
        if (!$petugasId) {
            return redirect()->back()->with('error', 'Akun petugas tidak ditemukan. Hubungi administrator.');
        }
        
        // Filter HANYA riwayat untuk petugas ini
        $riwayat = Pengaduan::with('user')
            ->where('id_petugas', $petugasId)
            ->where('status', 'Selesai')
            ->orderBy('tgl_selesai', 'desc')
            ->paginate(10);
        
        return view('petugas.riwayat.index', compact('riwayat'));
    }
    
    public function riwayatShow(Pengaduan $pengaduan)
    {
        $petugasId = $this->getPetugasId();
        
        // Validasi
        if ($pengaduan->id_petugas != $petugasId) {
            return redirect()->route('petugas.riwayat.index')
                ->with('error', 'Anda tidak memiliki akses ke pengaduan ini.');
        }
        
        if ($pengaduan->status !== 'Selesai') {
            return redirect()->route('petugas.riwayat.index')
                ->with('error', 'Pengaduan ini belum selesai');
        }
        
        $pengaduan->load('user');
        return view('petugas.riwayat.show', compact('pengaduan'));
    }
}