<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ModelChangeController;
use App\Http\Controllers\SocieteController;
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
    Route::post('/notifications/{id}/lu', [NotificationController::class, 'lu'])->name('notifications.lu');
    Route::post('/notifications/lu-all', [NotificationController::class, 'luAll'])->name('notifications.luall');
    Route::post('/notifications/{id}/non-lu', [NotificationController::class, 'nonLu'])->name('notifications.nonlu');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/lus', [NotificationController::class, 'indexLus'])->name('notifications.lus');
    Route::get('/notification/{id}', [NotificationController::class, 'detail'])->name('notifications.detail');
    Route::post('/notifications/transfer', [NotificationController::class, 'transfer'])->name('notifications.transfer');

    Route::middleware('permission:voir_historique')->group(function () {
        Route::get('/logs', [ModelChangeController::class, 'index'])->name('model_changes.index');
    });
    Route::middleware('permission:gerer_les_societes')->group(function () {
        Route::get('/societes', [SocieteController::class, 'index'])->name('societes.index');
        Route::get('/societes/client', [SocieteController::class, 'indexClient'])->name('societes.index_client');
        Route::get('/societes/fournisseur', [SocieteController::class, 'indexFournisseur'])->name('societes.index_fournisseur');
        Route::get('/societe/create', [SocieteController::class, 'create'])->name('societes.create');
        Route::post('/societe/store', [SocieteController::class, 'store'])->name('societes.store');
        Route::get('/societe/{societe}', [SocieteController::class, 'show'])->name('societes.show');
        Route::get('/societe/{societe}/edit', [SocieteController::class, 'edit'])->name('societes.edit');
        Route::patch('/societe/{societe}/update', [SocieteController::class, 'update'])->name('societes.update');
        Route::delete('/societe/{societe}/delete', [SocieteController::class, 'destroy'])->name('societes.destroy');
        Route::patch('/societe/{societe}/restore', [SocieteController::class, 'restore'])->name('societes.restore');

    });
});
require __DIR__ . '/auth.php';
