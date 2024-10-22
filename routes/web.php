<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard/paillettes', function () {
    return view('dashboard', ['paillettes' => 'oui']);
})->middleware(['auth', 'verified'])->name('dashboard.paillettes');

Route::middleware('auth')->group(function () {
    Route::get('/profile/{id}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile-admin', [ProfileController::class, 'updateAdmin'])->name('profile.update_admin');
    Route::delete('/profile/{user}/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user}/restore', [ProfileController::class, 'restore'])->name('profile.restore');

    Route::middleware('permission:gerer_les_utilisateurs')->group(function () {
        Route::get('/profiles', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile/create', [RoleController::class, 'store'])->name('role.store');
    });
    Route::middleware('permission:gerer_les_permissions')->group(function () {
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions');
        Route::get('/permissions/{role}', [PermissionController::class, 'index'])->name('permissions.index');
        Route::post('/permission/role/create', [RoleController::class, 'store'])->name('permissions.role.store');
        Route::put('/permissions/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    });
    Route::middleware('permission:gerer_les_postes')->group(function () {
        Route::get('/postes', [RoleController::class, 'index'])->name('roles');
        Route::get('/postes/{role}', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/postes/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::patch('/postes/{role}/update', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/postes/{role}/delete', [RoleController::class, 'destroy'])->name('roles.destroy');
        Route::patch('/postes/{role}/restore', [RoleController::class, 'restore'])->name('roles.restore');
    });
});
require __DIR__ . '/auth.php';
