<?php

use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminVerificationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\SKAI07Controller;
use App\Http\Controllers\PinjamanPerumahanController;
use App\Http\Controllers\PenyataGajiController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\SKAI07;
use App\Models\PinjamanPerumahan;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GrafController;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Dashboard Route with Email Verification
Route::middleware(['auth', 'verified'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Dashboard: Hantar pengguna ke dashboard masing-masing berdasarkan peranan
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $skai07 = SKAI07::all(); // Fetch all SKAI07 data

        if ($user->role == 'superadmin') {
            $admins = \App\Models\User::where('role', 'admin_negeri')->where('verified', false)->get();
            return view('superadmin.dashboard', compact('admins', 'skai07'));
        } elseif ($user->role == 'admin_negeri') {
            $pegawai = \App\Models\User::where('role', 'pegawai')->where('negeri', $user->negeri)->get();
            return view('admin.dashboard', compact('pegawai', 'skai07'));
        } else {
            return view('pegawai.dashboard', compact('skai07'));
        }
    })->name('dashboard');
});

// Middleware untuk semua pengguna yang telah login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Update Password Route
    Route::put('/password', [App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');
});

// Hanya Superadmin boleh mengakses halaman ini
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin-dashboard', function () {
        return view('superadmin.dashboard');
    });

    Route::get('/verify-admins', [AdminVerificationController::class, 'index'])->name('verify.admins');
    Route::post('/verify-admin/{id}', [AdminVerificationController::class, 'verify'])->name('verify.admin');
});


// Hanya Admin Negeri boleh mengakses halaman ini
Route::middleware(['auth', 'role:admin_negeri'])->group(function () {
    Route::get('/admin-dashboard', function () {
        return view('admin.dashboard');
    });
});

// Hanya Pegawai boleh mengakses halaman ini
Route::middleware(['auth', 'role:pegawai'])->group(function () {
    Route::get('/pegawai-dashboard', function () {
        return view('pegawai.dashboard');
    });
});

// Auth Routes (Login, Register, Forgot Password)
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// Logout
Route::middleware('auth')->post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Route for SKAI07 form
Route::resource('borang', Skai07Controller::class);

// Route for Pinjaman Perumahan
Route::resource('pinjaman-perumahan', PinjamanPerumahanController::class);

// Get Pegawai Details for Pinjaman Perumahan
Route::get('/pegawai/{id}', [Skai07Controller::class, 'getPegawaiInfo']);

Route::get('/pegawai/{id}', function ($id) {
    // $pegawai = PinjamanPerumahan::find($id);
    $pegawai = PinjamanPerumahan::selectRaw('pinjaman_perumahan.*, penyata_gaji.jantina')
    ->leftJoin('penyata_gaji', 'pinjaman_perumahan.penyata_gaji_id', '=', 'penyata_gaji.id')
    ->where('pinjaman_perumahan.id', $id)
    ->first();
    return response()->json($pegawai);
});

// Middleware for SuperAdmin and Admin Negeri (Penyata Gaji and Pinjaman Perumahan)
Route::middleware(['auth', 'role:superadmin,admin_negeri'])->group(function () {
    // Penyata Gaji Routes
    Route::resource('penyata-gaji', PenyataGajiController::class);
    Route::get('/penyata-gaji/api/search', [PenyataGajiController::class, 'search'])->name('penyata-gaji.search');
});

Route::get('/penyata-gaji/search', [PenyataGajiController::class, 'searchApi']);


// Route for SuperAdmin to view and manage Negeri
Route::get('/negeri/{negeri}', [SuperAdminController::class, 'paparNegeri'])->name('negeri.show');

Route::get('/dashboard/penyata-gaji-stats-by-negeri', [DashboardController::class, 'getPenyataGajiStatsByNegeri'])->name('dashboard.penyata_gaji_stats_by_negeri');

Route::prefix('graf')->middleware('auth')->group(function () {
    Route::get('keterhutangan', [GrafController::class, 'statistikKeterhutangan'])->name('graf.keterhutangan');
    Route::get('kumpulan-perkhidmatan', [GrafController::class, 'kumpulanPerkhidmatan'])->name('graf.kumpulan-perkhidmatan');
    Route::get('senarai-skai07', [GrafController::class, 'senaraiSkai07'])->name('graf.senarai-skai07');
});

// Direct Route for Penyata Gaji and Pinjaman Perumahan Index
// Route::get('/penyata-gaji', [PenyataGajiController::class, 'index'])->name('penyata-gaji.index');
// Route::get('/pinjaman-perumahan', [PinjamanPerumahanController::class, 'index'])->name('pinjaman-perumahan.index');

// Import auth routes
require __DIR__.'/auth.php';


// Home Route
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
