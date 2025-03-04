<?php

use App\Http\Controllers\Mono\DashboardController;
use App\Http\Controllers\Mono\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Mono\PegawaiController;
use App\Http\Controllers\Mono\ItemController;
use App\Http\Controllers\Mono\UnitController;
use App\Http\Controllers\Mono\MenuGroupController;
use App\Http\Controllers\Mono\MenuDetailController;
use App\Http\Controllers\Mono\UserController;
use App\Http\Controllers\Mono\RolePermissionController;
use App\Http\Controllers\Mono\PermissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// Route untuk Semua Role
Route::middleware('auth', 'role:superadmin|admin|user')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Route untuk Superadmin
Route::middleware(['auth', 'role:superadmin'])->group(function () {

    // Route User
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::get('/getPermission/{id}', [UserController::class, 'getPermission'])->name('getPermission');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/profile/{id}', [UserController::class, 'profile'])->name('profile');
    });

    // Route Role
    Route::prefix('role')->name('role.')->group(function () {
        Route::get('/', [RolePermissionController::class, 'index'])->name('index');
        Route::post('/store', [RolePermissionController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [RolePermissionController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [RolePermissionController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [RolePermissionController::class, 'destroy'])->name('destroy');
        Route::get('/profile/{id}', [RolePermissionController::class, 'profile'])->name('profile');
        Route::get('/permissions', [RolePermissionController::class, 'getPermissions']);
    });

    // Route Permission
    Route::prefix('permission')->name('permission.')->group(function () {
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
    Route::prefix('menu-groups')->name('menu-groups.')->group(function () {
        Route::get('/', [MenuGroupController::class, 'index'])->name('index');
        Route::post('/store', [MenuGroupController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MenuGroupController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [MenuGroupController::class, 'update'])->name('update');
    });

    // Route Menu Details
    Route::prefix('menu-details')->name('menu-details.')->group(function () {
        Route::get('/', [MenuDetailController::class, 'index'])->name('index');
        Route::post('/store', [MenuDetailController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MenuDetailController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [MenuDetailController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [MenuDetailController::class, 'destroy'])->name('destroy');
    });

    // Route Trash
    Route::get('/deleted/data', [AdminController::class, 'getDeletedRecords'])->name('deleted.data');
    Route::post('/deleted/restore', [AdminController::class, 'restoreRecord'])->name('deleted.restore');

});

Route::middleware('auth', 'role:superadmin|admin')->group(function () {

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

    // Route Item
    Route::prefix('item')->name('item.')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [ItemController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ItemController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ItemController::class, 'destroy'])->name('destroy');
        Route::get('/profile/{id}', [ItemController::class, 'profile'])->name('profile');
    });
});

require __DIR__.'/auth.php';
