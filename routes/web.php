<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\SarprasController;
use App\Http\Controllers\AdminPengaduanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Petugas\PetugasController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard router
    Route::get('/dashboard', function () {
        return redirect('/' . auth()->user()->role . '/dashboard');
    })->name('dashboard');

    // ===== ADMIN ROUTES =====
    Route::prefix('admin')->middleware(['auth', \App\Http\Middleware\CheckRole::class . ':admin'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

        // Pengaduan Management Routes
        Route::prefix('pengaduan')->group(function () {
            Route::get('/', [AdminPengaduanController::class, 'index'])->name('admin.pengaduan.index');
            Route::get('/{pengaduan}', [AdminPengaduanController::class, 'show'])->name('admin.pengaduan.show');
            Route::put('/{pengaduan}/status', [AdminPengaduanController::class, 'updateStatus'])->name('admin.pengaduan.update-status');
        });

        // Sarpras Management Routes
        Route::prefix('sarpras')->group(function () {
            Route::get('/', [SarprasController::class, 'index'])->name('admin.sarpras.index');
            Route::get('/permintaan', [SarprasController::class, 'permintaanList'])->name('admin.sarpras.permintaan-list');
            Route::get('/permintaan/{id}', [SarprasController::class, 'showPermintaan'])->name('admin.sarpras.show-permintaan');
            Route::put('/permintaan/{id}/status', [SarprasController::class, 'updateStatus'])->name('admin.sarpras.update-status');
            Route::get('/history', [SarprasController::class, 'history'])->name('admin.sarpras.history');
            Route::get('/history/export', [SarprasController::class, 'exportHistory'])->name('admin.sarpras.export-history');
        });

        // User Management Routes
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class, ['as' => 'admin']);
    });

    // ===== PETUGAS ROUTES =====
    Route::prefix('petugas')->middleware(['auth', \App\Http\Middleware\CheckRole::class . ':petugas'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [PetugasController::class, 'dashboard'])
            ->name('petugas.dashboard');
        
        // Pengaduan Management
        Route::prefix('pengaduan')->group(function () {
            Route::get('/', [PetugasController::class, 'pengaduanIndex'])->name('petugas.pengaduan.index');
            Route::get('/{pengaduan}', [PetugasController::class, 'pengaduanShow'])->name('petugas.pengaduan.show');
            Route::put('/{pengaduan}/status', [PetugasController::class, 'updateStatus'])->name('petugas.pengaduan.update-status');
        });
        
        // Riwayat
        Route::prefix('riwayat')->group(function () {
            Route::get('/', [PetugasController::class, 'riwayatIndex'])->name('petugas.riwayat.index');
            Route::get('/{pengaduan}', [PetugasController::class, 'riwayatShow'])->name('petugas.riwayat.show');
        });
    });

    // ===== PENGGUNA ROUTES =====
    Route::prefix('pengguna')->middleware(['auth', \App\Http\Middleware\CheckRole::class . ':pengguna'])->group(function () {
        Route::get('/dashboard', function () {
            $user = auth()->user();
            $query = \App\Models\Pengaduan::where('id_user', $user->id_user);
            $totalPengaduan = $query->count();
            
            $data = [
                'totalPengaduan' => $totalPengaduan,
                'selesaiCount' => \App\Models\Pengaduan::where('id_user', $user->id_user)->where('status', 'Selesai')->count(),
                'prosesCount' => \App\Models\Pengaduan::where('id_user', $user->id_user)->whereIn('status', ['Diajukan', 'Disetujui', 'Diproses'])->count(),
                'recentPengaduans' => \App\Models\Pengaduan::where('id_user', $user->id_user)
                    ->orderBy('tgl_pengajuan', 'desc')
                    ->take(5)
                    ->get()
            ];
            
            return view('pengguna.dashboard', $data);
        })->name('pengguna.dashboard');

        // Pengaduan routes
        Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
        Route::get('/pengaduan/create', [PengaduanController::class, 'create'])->name('pengaduan.create');
        Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
        Route::get('/pengaduan/{pengaduan}', action: [PengaduanController::class, 'show'])->name('pengaduan.show');
        Route::get('/pengaduan/{pengaduan}/edit', [PengaduanController::class, 'edit'])->name('pengaduan.edit');
        Route::put('/pengaduan/{pengaduan}', [PengaduanController::class, 'update'])->name('pengaduan.update');
        Route::delete('/pengaduan/{pengaduan}', [PengaduanController::class, 'destroy'])->name('pengaduan.destroy');
    });
});