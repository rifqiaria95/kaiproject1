<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Mono\ItemController;
use App\Http\Controllers\Mono\UnitController;
use App\Http\Controllers\Mono\UserController;
use App\Http\Controllers\Mono\AdminController;
use App\Http\Controllers\Mono\DivisiController;
use App\Http\Controllers\Mono\GudangController;
use App\Http\Controllers\Mono\VendorController;
use App\Http\Controllers\Mono\JabatanController;
use App\Http\Controllers\Mono\PegawaiController;
use App\Http\Controllers\Mono\KategoriController;
use App\Http\Controllers\Mono\DashboardController;
use App\Http\Controllers\Mono\MenuGroupController;
use App\Http\Controllers\Mono\PelangganController;
use App\Http\Controllers\Mono\DepartemenController;
use App\Http\Controllers\Mono\MenuDetailController;
use App\Http\Controllers\Mono\PermissionController;
use App\Http\Controllers\Mono\PerusahaanController;
use App\Http\Controllers\Mono\RolePermissionController;

Route::get('/', function () {
    return view('auth.login');
});

// Route untuk Semua Role
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route User
    Route::prefix('satuan')->name('satuan.')->group(function () {
        Route::get('/', [UnitController::class, 'index'])->name('index');
        Route::post('/store', [UnitController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UnitController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [UnitController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UnitController::class, 'destroy'])->name('destroy');
        Route::get('/profile/{id}', [UnitController::class, 'profile'])->name('profile');
    });

    // Route Pegawai
    Route::prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/', [PegawaiController::class, 'index'])->name('index');
        Route::post('/store', [PegawaiController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PegawaiController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PegawaiController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PegawaiController::class, 'destroy'])->name('destroy');
        Route::get('/profile/{id}', [PegawaiController::class, 'profile'])->name('profile');
        Route::get('/get-kota/{id_provinsi}', [PegawaiController::class, 'getKotaByProvinsi'])->name('pegawai.get-kota');
    });

    // Route Jabatan
    Route::prefix('jabatan')->name('jabatan.')->group(function () {
        Route::get('/', [JabatanController::class, 'index'])->name('index');
        Route::post('/store', [JabatanController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [JabatanController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [JabatanController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [JabatanController::class, 'destroy'])->name('destroy');
    });

    // Route Divisi
    Route::prefix('divisi')->name('divisi.')->group(function () {
        Route::get('/', [DivisiController::class, 'index'])->name('index');
        Route::post('/store', [DivisiController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [DivisiController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [DivisiController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [DivisiController::class, 'destroy'])->name('destroy');
    });

    // Route Departemen
    Route::prefix('departemen')->name('departemen.')->group(function () {
        Route::get('/', [DepartemenController::class, 'index'])->name('index');
        Route::post('/store', [DepartemenController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [DepartemenController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [DepartemenController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [DepartemenController::class, 'destroy'])->name('destroy');
    });

    // Route Perusahaan
    Route::prefix('perusahaan')->name('perusahaan.')->group(function () {
        Route::get('/', [PerusahaanController::class, 'index'])->name('index');
        Route::post('/store', [PerusahaanController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PerusahaanController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PerusahaanController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PerusahaanController::class, 'destroy'])->name('destroy');
    });

    // Route Cabang
    Route::prefix('cabang')->name('cabang.')->group(function () {
        Route::get('/', [CabangController::class, 'index'])->name('index');
        Route::post('/store', [CabangController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CabangController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CabangController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CabangController::class, 'destroy'])->name('destroy');
        Route::get('/get-perusahaan', [CabangController::class, 'getPerusahaan'])->name('get.perusahaan');
    });

    // Route Item
    Route::prefix('item')->name('item.')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('index');
        Route::post('/store', [ItemController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ItemController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ItemController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ItemController::class, 'destroy'])->name('destroy');
        Route::get('/profile/{id}', [ItemController::class, 'profile'])->name('profile');
    });

    // Route Gudang
    Route::prefix('gudang')->name('gudang.')->group(function () {
        Route::get('/', [GudangController::class, 'index'])->name('index');
        Route::post('/store', [GudangController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [GudangController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [GudangController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [GudangController::class, 'destroy'])->name('destroy');
    });

    // Route Kategori
    Route::prefix('kategori')->name('kategori.')->group(function () {
        Route::get('/', [KategoriController::class, 'index'])->name('index');
        Route::post('/store', [KategoriController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [KategoriController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [KategoriController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [KategoriController::class, 'destroy'])->name('destroy');
    });

    // Route Pelanggan
    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/', [PelangganController::class, 'index'])->name('index');
        Route::post('/store', [PelangganController::class, 'store'])->name('store');
        Route::get('/edit/{id:uuid}', [PelangganController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PelangganController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PelangganController::class, 'destroy'])->name('destroy');
        Route::get('/get-kota/{id_provinsi}', [PelangganController::class, 'getKotaByProvinsi'])->name('pelanggan.get-kota');
        Route::get('/profile/{id}', [PelangganController::class, 'profile'])->name('profile');
    });

    // Route Pelanggan
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/', [VendorController::class, 'index'])->name('index');
        Route::post('/store', [VendorController::class, 'store'])->name('store');
        Route::get('/edit/{id:uuid}', [VendorController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [VendorController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [VendorController::class, 'destroy'])->name('destroy');
        Route::get('/get-kota/{id_provinsi}', [VendorController::class, 'getKotaByProvinsi'])->name('vendor.get-kota');
        Route::get('/profile/{id}', [VendorController::class, 'profile'])->name('profile');
    });
});

// Route untuk Superadmin
Route::middleware(['auth', 'role:superadmin'])->group(function () {

    // Route User
    Route::prefix('admin/users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::get('/getPermission/{id}', [UserController::class, 'getPermission'])->name('getPermission');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/profile/{id}', [UserController::class, 'profile'])->name('profile');
    });

    // Route Role
    Route::prefix('admin/role')->name('role.')->group(function () {
        Route::get('/', [RolePermissionController::class, 'index'])->name('index');
        Route::post('/store', [RolePermissionController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [RolePermissionController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [RolePermissionController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [RolePermissionController::class, 'destroy'])->name('destroy');
        Route::get('/profile/{id}', [RolePermissionController::class, 'profile'])->name('profile');
        Route::get('/permissions', [RolePermissionController::class, 'getPermissions']);
    });

    // Route Permission
    Route::prefix('admin/permission')->name('permission.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::post('/store', [PermissionController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PermissionController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PermissionController::class, 'destroy'])->name('destroy');
        Route::get('/profile/{id}', [PermissionController::class, 'profile'])->name('profile');
        Route::get('/permissions', [PermissionController::class, 'getPermissions']);
        Route::get('/get-menu-groups', [PermissionController::class, 'getMenuGroups'])->name('get.menu.groups');
        Route::get('/get-menu-details', [PermissionController::class, 'getMenuDetails'])->name('get.menu.details');

    });

    // Route Menu Group
    Route::prefix('admin/menu-group')->name('menu-group.')->group(function () {
        Route::get('/', [MenuGroupController::class, 'index'])->name('index');
        Route::post('/store', [MenuGroupController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MenuGroupController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [MenuGroupController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [MenuGroupController::class, 'destroy'])->name('destroy');
    });

    // Route Menu Details
    Route::prefix('admin/menu-detail')->name('menu-detail.')->group(function () {
        Route::get('/', [MenuDetailController::class, 'index'])->name('index');
        Route::post('/store', [MenuDetailController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MenuDetailController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [MenuDetailController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [MenuDetailController::class, 'destroy'])->name('destroy');
    });

    // Route Trash
    Route::get('/deleted/data', [AdminController::class, 'getDeletedRecords'])->name('deleted.data');
    Route::post('/deleted/restore', [AdminController::class, 'restoreRecord'])->name('deleted.restore');
    Route::post('/deleted/delete', [AdminController::class, 'deleteRecord'])->name('deleted.delete');
});

require __DIR__.'/auth.php';
