<?php

use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ModelChangeController;
use App\Http\Controllers\SocieteContactController;
use App\Http\Controllers\SocieteController;
use App\Http\Controllers\UserShortcutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware(['GetGlobalVariable'])->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'GetGlobalVariable'])->name('dashboard');
Route::get('/dashboard/paillettes', function () {
    return view('dashboard', ['paillettes' => 'oui']);
})->middleware(['auth', 'verified', 'GetGlobalVariable'])->name('dashboard.paillettes');
Route::middleware(['GetGlobalVariable', 'XSSProtection'])->group(function () {
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
    Route::get('/notifications/fetch', [NotificationController::class, 'fetch'])->name('notifications.fetch');

    Route::middleware('permission:voir_historique')->group(function () {
        Route::get('/logs', [ModelChangeController::class, 'index'])->name('model_changes.index');
    });
    Route::middleware('permission:voir_les_societes')->group(function () {
        Route::get('/societes', [SocieteController::class, 'index'])->name('societes.index');
        Route::get('/societes/client', [SocieteController::class, 'indexClient'])->name('societes.index_client');
        Route::get('/societes/fournisseur', [SocieteController::class, 'indexFournisseur'])->name('societes.index_fournisseur');
        Route::get('/societe/{societe}', [SocieteController::class, 'show'])->name('societes.show');
        Route::get('/societe/{societe}/json', [SocieteController::class, 'showJson'])->name('societes.show_json');
        Route::get('/societe/{societe}/etablissement/{etablissement}', [SocieteController::class, 'show'])->name('societes.etablissement.show');
        Route::patch('/societe/{id}/commentaire/save', [SocieteController::class, 'updateCommentaire'])->name('societes.commentaire');
        Route::patch('/societe/etablissement/{id}/commentaire/save', [EtablissementController::class, 'updateCommentaire'])->name('societes.etablissement.commentaire');

        Route::middleware('permission:gerer_les_societes')->group(function () {
            Route::get('/societes/create', [SocieteController::class, 'create'])->name('societes.create');
            Route::post('/societes/store', [SocieteController::class, 'store'])->name('societes.store');
            Route::get('/societe/{societe}/edit', [SocieteController::class, 'edit'])->name('societes.edit');
            Route::patch('/societe/{societe}/update', [SocieteController::class, 'update'])->name('societes.update');
            Route::delete('/societe/{societe}/delete', [SocieteController::class, 'destroy'])->name('societes.destroy');
            Route::patch('/societe/{societe}/restore', [SocieteController::class, 'restore'])->name('societes.restore');
            Route::get('/societe/{societe}/etablissements/create', [EtablissementController::class, 'create'])->name('etablissements.create');
            Route::post('/societe/etablissement/store', [EtablissementController::class, 'store'])->name('etablissements.store');
            Route::get('/societe/{societe}/etablissement/{etablissement}/edit', [EtablissementController::class, 'edit'])->name('etablissements.edit');
            Route::patch('/societe/etablissement/{etablissement}/update', [EtablissementController::class, 'update'])->name('etablissements.update');
            Route::delete('/societe/etablissement/{etablissement}/delete', [EtablissementController::class, 'destroy'])->name('etablissements.destroy');
        });
        Route::middleware('permission:gerer_les_contacts')->group(function () {
            Route::post('/societe/contact/store', [SocieteContactController::class, 'store'])->name('societes.contacts.store');
            Route::get('/societe/{societe}/contact/{contact}/edit', [SocieteContactController::class, 'edit'])->name('societes.contacts.edit');
            Route::patch('/societe/contact/{contact}/update', [SocieteContactController::class, 'update'])->name('societes.contacts.update');
            Route::delete('/societe/contact/{contact}/delete', [SocieteContactController::class, 'destroy'])->name('societes.contacts.destroy');
        });
    });

    Route::get('/shortcuts', [UserShortcutController::class, 'index'])->name('shortcuts.index');
    Route::post('/shortcuts', [UserShortcutController::class, 'store'])->name('shortcuts.store');
    Route::delete('/shortcuts/{id}', [UserShortcutController::class, 'destroy'])->name('shortcuts.destroy');
    Route::patch('/shortcuts/reorder', [UserShortcutController::class, 'reorder'])->name('shortcuts.reorder');
});
require __DIR__ . '/auth.php';
