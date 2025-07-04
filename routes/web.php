<?php

use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\CdeController;
use App\Http\Controllers\CdeNoteController;
use App\Http\Controllers\DdpController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\MailtemplateController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\MatierePrixController;
use App\Http\Controllers\ModelChangeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReferenceDataController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SocieteContactController;
use App\Http\Controllers\SocieteController;
use App\Http\Controllers\StandardController;
use App\Http\Controllers\UserShortcutController;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AffaireController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->middleware(['GetGlobalVariable'])->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'GetGlobalVariable'])->name('dashboard');
Route::get('/dashboard/paillettes', function () {
    return view('dashboard', ['paillettes' => 'oui']);
})->middleware(['auth', 'verified', 'GetGlobalVariable'])->name('dashboard.paillettes');

Route::middleware(['GetGlobalVariable', 'XSSProtection', 'auth'])->group(function () {

    Route::get('/administration', [AdministrationController::class, 'index'])->name('administration.index');
    Route::get('/administration/info', [AdministrationController::class, 'info'])->name('administration.info');
    Route::get('/icons', function () {
        return view('administration.icons');
    })->name('administration.icons');
    Route::get('/administration/info/{entite}', [AdministrationController::class, 'info'])->name('administration.info_entite');
    Route::patch('/administration/info/{entite}/update', [AdministrationController::class, 'update'])->name('administration.update');


    Route::middleware('permission:gerer_les_utilisateurs')->group(function () {
        route::get('/administration/settings', [AppSettingController::class, 'settings'])->name('administration.appsettings.index');
        Route::patch('/administration/settings/update', [AppSettingController::class, 'update'])->name('administration.appsettings.update');
    });


    Route::get('/documentation', [DocumentationController::class, 'index'])->name('documentation.index');
    Route::get('/documentation/download/{format}', [DocumentationController::class, 'download'])->name('documentation.download');
    Route::get('/documentation/images/{filename}', [DocumentationController::class, 'serveImage'])->name('documentation.images');

    Route::get('/profile/{id}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile-admin', [ProfileController::class, 'updateAdmin'])->name('profile.update_admin');
    Route::delete('/profile/{user}/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user}/restore', [ProfileController::class, 'restore'])->name('profile.restore');

    Route::post('/notifications/{id}/lu', [NotificationController::class, 'lu'])->name('notifications.lu');
    Route::post('/notifications/lu-all', [NotificationController::class, 'luAll'])->name('notifications.luall');
    Route::post('/notifications/{id}/non-lu', [NotificationController::class, 'nonLu'])->name('notifications.nonlu');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/lus', [NotificationController::class, 'indexLus'])->name('notifications.lus');
    Route::get('/notification/{id}', [NotificationController::class, 'detail'])->name('notifications.detail');
    Route::post('/notifications/transfer', [NotificationController::class, 'transfer'])->name('notifications.transfer');
    Route::get('/notifications/fetch', [NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::get('/notifications/modal', [NotificationController::class, 'modal'])->name('notifications.modal');

    Route::get('/shortcuts', [UserShortcutController::class, 'index'])->name('shortcuts.index');
    Route::post('/shortcuts', [UserShortcutController::class, 'store'])->name('shortcuts.store');
    Route::delete('/shortcuts/{id}', [UserShortcutController::class, 'destroy'])->name('shortcuts.destroy');
    Route::patch('/shortcuts/update-order', [UserShortcutController::class, 'updateOrder'])->name('shortcuts.updateOrder');


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


    Route::middleware('permission:voir_historique')->group(function () {
        Route::get('/logs', [ModelChangeController::class, 'index'])->name('model_changes.index');
    });


    // Routes pour les données de référence
    Route::middleware(['permission:gerer_les_donnees_de_reference'])->group(function () {
        Route::get('/administration/reference-data', [ReferenceDataController::class, 'index'])->name('reference-data.index');
        Route::get('/administration/reference-data/modal', [ReferenceDataController::class, 'loadModal'])->name('reference-data.modal');

        // Familles
        Route::post('/administration/reference-data/famille', [ReferenceDataController::class, 'storeFamille'])->name('reference-data.famille.store');
        Route::patch('/administration/reference-data/famille/{famille}', [ReferenceDataController::class, 'updateFamille'])->name('reference-data.famille.update');
        Route::delete('/administration/reference-data/famille/{famille}', [ReferenceDataController::class, 'destroyFamille'])->name('reference-data.famille.destroy');

        // Sous-familles
        Route::post('/administration/reference-data/sous-famille', [ReferenceDataController::class, 'storeSousFamille'])->name('reference-data.sous-famille.store');
        Route::patch('/administration/reference-data/sous-famille/{sousFamille}', [ReferenceDataController::class, 'updateSousFamille'])->name('reference-data.sous-famille.update');
        Route::delete('/administration/reference-data/sous-famille/{sousFamille}', [ReferenceDataController::class, 'destroySousFamille'])->name('reference-data.sous-famille.destroy');

        // Formes juridiques
        Route::post('/administration/reference-data/forme-juridique', [ReferenceDataController::class, 'storeFormeJuridique'])->name('reference-data.forme-juridique.store');
        Route::patch('/administration/reference-data/forme-juridique/{formeJuridique}', [ReferenceDataController::class, 'updateFormeJuridique'])->name('reference-data.forme-juridique.update');
        Route::delete('/administration/reference-data/forme-juridique/{formeJuridique}', [ReferenceDataController::class, 'destroyFormeJuridique'])->name('reference-data.forme-juridique.destroy');

        // Dossiers standards
        Route::post('/administration/reference-data/dossier-standard', [ReferenceDataController::class, 'storeDossierStandard'])->name('reference-data.dossier-standard.store');
        Route::patch('/administration/reference-data/dossier-standard/{dossierStandard}', [ReferenceDataController::class, 'updateDossierStandard'])->name('reference-data.dossier-standard.update');
        Route::delete('/administration/reference-data/dossier-standard/{dossierStandard}', [ReferenceDataController::class, 'destroyDossierStandard'])->name('reference-data.dossier-standard.destroy');

        // Pays
        Route::post('/administration/reference-data/pays', [ReferenceDataController::class, 'storePays'])->name('reference-data.pays.store');
        Route::patch('/administration/reference-data/pays/{pays}', [ReferenceDataController::class, 'updatePays'])->name('reference-data.pays.update');
        Route::delete('/administration/reference-data/pays/{pays}', [ReferenceDataController::class, 'destroyPays'])->name('reference-data.pays.destroy');

        // Codes APE
        Route::post('/administration/reference-data/code-ape', [ReferenceDataController::class, 'storeCodeApe'])->name('reference-data.code-ape.store');
        Route::patch('/administration/reference-data/code-ape/{codeApe}', [ReferenceDataController::class, 'updateCodeApe'])->name('reference-data.code-ape.update');
        Route::delete('/administration/reference-data/code-ape/{codeApe}', [ReferenceDataController::class, 'destroyCodeApe'])->name('reference-data.code-ape.destroy');

        // Conditions de paiement
        Route::post('/administration/reference-data/condition-paiement', [ReferenceDataController::class, 'storeConditionPaiement'])->name('reference-data.condition-paiement.store');
        Route::patch('/administration/reference-data/condition-paiement/{conditionPaiement}', [ReferenceDataController::class, 'updateConditionPaiement'])->name('reference-data.condition-paiement.update');
        Route::delete('/administration/reference-data/condition-paiement/{conditionPaiement}', [ReferenceDataController::class, 'destroyConditionPaiement'])->name('reference-data.condition-paiement.destroy');

        // Matériaux
        Route::post('/administration/reference-data/material', [ReferenceDataController::class, 'storeMaterial'])->name('reference-data.material.store');
        Route::patch('/administration/reference-data/material/{material}', [ReferenceDataController::class, 'updateMaterial'])->name('reference-data.material.update');
        Route::delete('/administration/reference-data/material/{material}', [ReferenceDataController::class, 'destroyMaterial'])->name('reference-data.material.destroy');

        // Unités
        Route::post('/administration/reference-data/unite', [ReferenceDataController::class, 'storeUnite'])->name('reference-data.unite.store');
        Route::patch('/administration/reference-data/unite/{unite}', [ReferenceDataController::class, 'updateUnite'])->name('reference-data.unite.update');
        Route::delete('/administration/reference-data/unite/{unite}', [ReferenceDataController::class, 'destroyUnite'])->name('reference-data.unite.destroy');
    });


    Route::middleware('permission:voir_les_societes')->group(function () {
        Route::get('/societes', [SocieteController::class, 'index'])->name('societes.index');
        Route::get('/societes/client', [SocieteController::class, 'indexClient'])->name('societes.index_client');
        Route::get('/societes/fournisseur', [SocieteController::class, 'indexFournisseur'])->name('societes.index_fournisseur');
        Route::get('/societes/fournisseurs/quickSearch', [SocieteController::class, 'quickSearchFournisseur'])->name('societes.quickSearchFournisseur');
        Route::get('/societe/{societe}', [SocieteController::class, 'show'])->name('societes.show');
        Route::get('/societe/{societe}/json', [SocieteController::class, 'showJson'])->name('societes.show_json');
        Route::get('/societe/{societe}/etablissement/{etablissement}', [SocieteController::class, 'show'])->name('societes.etablissement.show');
        Route::get('/societe/{societe}/etablissements/json', [SocieteController::class, 'showEtablissementsJson'])->name('societes.etablissement.show_json');
        Route::patch('/societe/{id}/commentaire/save', [SocieteController::class, 'updateCommentaire'])->name('societes.commentaire');
        Route::patch('/societe/etablissement/{id}/commentaire/save', [EtablissementController::class, 'updateCommentaire'])->name('societes.etablissement.commentaire');
        Route::get('/societes/{societeId}/etablissements/{etablissementId}/contacts/json', [SocieteContactController::class, 'showJson'])->name('societes.contacts.show_json');

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
            Route::get('/societes/contacts/quickCreate', [SocieteContactController::class, 'quickCreate'])->name('societes.contacts.quickCreate');
            Route::get('/societe/{societe}/contact/{contact}/edit', [SocieteContactController::class, 'edit'])->name('societes.contacts.edit');
            Route::patch('/societe/contact/{contact}/update', [SocieteContactController::class, 'update'])->name('societes.contacts.update');
            Route::delete('/societe/contact/{contact}/delete', [SocieteContactController::class, 'destroy'])->name('societes.contacts.destroy');
        });
    });


    Route::middleware('permission:voir_les_matieres')->group(function () {
        Route::get('/matieres', [MatiereController::class, 'index'])->name('matieres.index');
        Route::get('/matieres/search', [MatiereController::class, 'searchResult'])->name('matieres.search');
        Route::get('/matieres/quickcreate/{modalId}', [MatiereController::class, 'quickCreate'])->name('matieres.quickCreate');
        Route::POST('/matieres/quickcreate/{modalId}', [MatiereController::class, 'quickStore'])->name('matieres.quickStore');
        Route::get('/matieres/quickSearch', [MatiereController::class, 'quickSearch'])->name('matieres.quickSearch');
        Route::get('/matieres/famille/{famille}/sous-familles/json', [MatiereController::class, 'sousFamillesJson'])->name('matieres.sous_familles.json');
        Route::post('/matieres/sous-famille/store', [MatiereController::class, 'storeSousFamille'])->name('matieres.sous_familles.store');
        Route::post('/matieres/familles', [MatiereController::class, 'storeFamille'])->name('matieres.familles.store');
        Route::post('/matieres/sous-familles', [MatiereController::class, 'storeSousFamille'])->name('matieres.sous_familles.store');
        Route::get('/matieres/import', [MatiereController::class, 'importForm'])->name('matieres.import.form');
        Route::post('/matieres/import/preview', [MatiereController::class, 'importExcel'])->name('matieres.import.preview');
        Route::post('/matieres/import/store', [MatiereController::class, 'importExcelStore'])->name('matieres.import.store');
        Route::get('/matieres/import/example', [MatiereController::class, 'importExample'])->name('matieres.import.example');
        Route::get('/matieres/{matiere}/fournisseurs/json', [MatiereController::class, 'fournisseursJson'])->name('matieres.fournisseurs.json');
        Route::get('/matieres/standards', [StandardController::class, 'index'])->name('standards.index');
        Route::get('/matieres/{matiere}', [MatiereController::class, 'show'])->name('matieres.show');

        // Routes pour la gestion des prix (utilise le nouveau contrôleur)
        Route::get('/matieres/{matiere}/prix/{fournisseur}', [MatierePrixController::class, 'show'])->name('matieres.show_prix');
        Route::post('/matieres/{matiere}/prix/{fournisseur}/store', [MatierePrixController::class, 'store'])->name('matieres.show_prix.store');
        Route::put('/matieres/{matiere}/prix/{fournisseur}/{prix}', [MatierePrixController::class, 'update'])
            ->name('matieres.show_prix.update');
        Route::delete('/matieres/{matiere}/prix/{fournisseur}/{prix}', [MatierePrixController::class, 'delete'])
            ->name('matieres.show_prix.delete');

        Route::post('/matieres/{matiere}/retirer', [MatiereController::class, 'retirerMatiere'])->name('matieres.retirer');
        Route::post('/matieres/{matiere}/ajouter', [MatiereController::class, 'ajouterMatiere'])->name('matieres.ajouter');
        Route::post('/matieres/{matiere}/ajuster', [MatiereController::class, 'ajusterMatiere'])->name('matieres.ajuster');
        Route::get('/matieres/{matiere}/edit', [MatiereController::class, 'edit'])->name('matieres.edit');
        Route::patch('/matieres/{matiere}/update', [MatiereController::class, 'update'])->name('matieres.update');
        Route::delete('/matieres/{matiere}', [MatiereController::class, 'destroy'])->name('matieres.destroy');
        Route::get('/matieres/{id}/mouvements', [MatiereController::class, 'mouvements'])->name('matieres.mouvements');
        Route::delete('/matieres/{matiere}/mouvements/{mouvement}', [MatiereController::class, 'supprimerMouvement'])
            ->name('matieres.mouvement.supprimer');
        Route::put('/matieres/{matiere}/mouvements/{mouvement}', [MatiereController::class, 'modifierMouvement'])
            ->name('matieres.mouvement.modifier');
        Route::post('/matieres/{matiere}/fournisseur/store', [MatiereController::class, 'storeFournisseur'])->name('matieres.fournisseurs.store');
        Route::delete('/matieres/{matiere}/fournisseurs/{fournisseur}', [MatiereController::class, 'detacherFournisseur'])->name('matieres.fournisseurs.detacher');

        Route::delete('/matieres/standards/delete', [StandardController::class, 'destroy'])->name('standards.destroy');
        Route::delete('/matieres/standards/deleteDossier', [StandardController::class, 'destroyDossier'])->name('standards.destroy_dossier');
        Route::get('/matieres/standards/create', [StandardController::class, 'create'])->name('standards.create');
        Route::post('/matieres/standards/create', [StandardController::class, 'store'])->name('standards.store');
        Route::post('/matieres/standards/createDossier', [StandardController::class, 'storeDossier'])->name('standards.store_dossier');
        Route::get('/matieres/standards/{dossier}/standards/json', [StandardController::class, 'showStandardsJson'])->name('standards.show_json');
        Route::get('/matieres/standards/{dossier}/{standard}/versions/json', [StandardController::class, 'showVersionsJson'])->name('standards.show_versions_json');
        Route::get('/matieres/standards/{dossier}/{standard}', [StandardController::class, 'show'])->name('standards.show');
    });


    Route::middleware('permission:gerer_mail_templates')->group(function () {
        Route::get('/mailtemplates', [MailtemplateController::class, 'index'])->name('mailtemplates.index');
        Route::get('/mailtemplates/{mailtemplate}/edit', [MailTemplateController::class, 'edit'])->name('mailtemplates.edit');
        Route::patch('/mailtemplates/{mailtemplate}/update', [MailTemplateController::class, 'update'])->name(name: 'mailtemplates.update');
        Route::post('/mailtemplates/upload-signature', [MailtemplateController::class, 'uploadSignature'])->name('mailtemplates.uploadSignature');
    });


    Route::middleware('permission:voir_les_ddp_et_cde')->group(function () {
        Route::get('/administration/cde-notes/{entite}', [CdeNoteController::class, 'index'])->name('administration.cdeNote.index');
        Route::get('/administration/cde-note/{entite}/create', [CdeNoteController::class, 'create'])->name('administration.cdeNote.create');
        Route::get('/administration/cde-note/{note}', [CdeNoteController::class, 'show'])->name('administration.cdeNote.show');
        Route::post('/administration/cde-note/store', [CdeNoteController::class, 'store'])->name('administration.cdeNote.store');
        Route::patch('/administration/cde-note/{note}/update', [CdeNoteController::class, 'update'])->name('administration.cdeNote.update');
        Route::delete('/administration/cde-note/{note}/destroy', [CdeNoteController::class, 'destroy'])->name('administration.cdeNote.destroy');
        Route::patch('/administration/cde-note/update-order', [CdeNoteController::class, 'updateOrder'])->name('administration.cdeNote.updateOrder');
        Route::get('/ddp&cde', [DdpController::class, 'indexDdp_cde'])->name('ddp_cde.index');
        Route::get('/ddp', [DdpController::class, 'index'])->name('ddp.index');
        Route::get('/colddp', [DdpController::class, 'indexColDdp'])->name('ddp.index_col_ddp');
        Route::get('/colddp/small', [DdpController::class, 'indexColDdpSmall'])->name('ddp.index_col_ddp_small');
        Route::get('/ddp/create', [DdpController::class, 'create'])->name('ddp.create');
        Route::post('/ddp/save', [DdpController::class, 'save'])->name('ddp.save');
        Route::post('/ddp/get-last-code/{entite}', [DdpController::class, 'getLastCode'])->name('ddp.get_last_code');
        Route::patch('/ddp/{id}/commentaire/save', [DdpController::class, 'updateCommentaire'])->name('ddp.commentaire');
        Route::get('/ddp/{ddp}/annuler', [DdpController::class, 'annuler'])->name('ddp.annuler');
        Route::get('/ddp/{ddp}/reprendre', [DdpController::class, 'reprendre'])->name('ddp.reprendre');
        Route::delete('/ddp/{ddp}/destroy', [DdpController::class, 'destroy'])->name('ddp.destroy');
        Route::get('/ddp/{ddp}/validate', [DdpController::class, 'validation'])->name('ddp.validation');
        Route::post('/ddp/{ddp}/validate', [DdpController::class, 'validate'])->name('ddp.validate');
        Route::get('/ddp/{ddp}/annuler-validation', [DdpController::class, 'cancelValidate'])->name('ddp.cancel_validate');
        Route::post('/ddp/{ddp}/save-retours', [DdpController::class, 'saveRetours'])->name('ddp.save_retours');
        Route::get('/ddp/{ddp}/pdfs', [DdpController::class, 'pdfs'])->name('ddp.pdfs');
        Route::get('/ddp/{ddp}/pdfs/download', [DdpController::class, 'pdfsDownload'])->name('ddp.pdfs.download');
        Route::get('/ddp/{ddp}/pdf/{annee}/{nom}', [DdpController::class, 'pdfshow'])->name('ddp.pdfshow');
        Route::get('/ddp/{ddp}/pdf/{annee}/{nom}/download', [DdpController::class, 'pdfDownload'])->name('ddp.pdfdownload');
        Route::get('/ddp/{ddp}', [DdpController::class, 'show'])->name('ddp.show');
        Route::post('/ddp/{ddp}/sendmails', [DdpController::class, 'sendMails'])->name('ddp.sendmails');
        Route::get('/ddp/{ddp}/skipmails', [DdpController::class, 'skipMails'])->name('ddp.skipmails');
        Route::get('/ddp/{ddp}/terminer', [DdpController::class, 'terminer'])->name('ddp.terminer');
        Route::get('/ddp/{ddp}/annuler_terminer', [DdpController::class, 'annuler_terminer'])->name('ddp.annuler_terminer');
        Route::get('/ddp/{ddp}/{societe_contact}/commander', [DdpController::class, 'commander'])->name('ddp.commander');




        Route::get('/cde', [CdeController::class, 'index'])->name('cde.index');
        Route::get('/colcde', [CdeController::class, 'indexColCde'])->name('ddp.index_col_cde');
        Route::get('/colcde/small', [CdeController::class, 'indexColCdeSmall'])->name('ddp.index_col_cde_small');
        Route::get('/cde/create', [CdeController::class, 'create'])->name('cde.create');
        Route::post('/cde/save', [CdeController::class, 'save'])->name('cde.save');
        Route::post('/cde/get-last-code/{entite}', [CdeController::class, 'getLastCode'])->name('cde.get_last_code');
        Route::get('/cde/{cde}', [CdeController::class, 'show'])->name('cde.show');
        Route::get('/cde/{cde}/annuler', [CdeController::class, 'annuler'])->name('cde.annuler');
        Route::get('/cde/{cde}/reprendre', [CdeController::class, 'reprendre'])->name('cde.reprendre');
        Route::patch('/cde/{id}/commentaire/save', [CdeController::class, 'updateCommentaire'])->name('cde.commentaire');
        Route::delete('/cde/{cde}/destroy', [CdeController::class, 'destroy'])->name('cde.destroy');
        Route::get('/cde/{cde}/validate', [CdeController::class, 'validation'])->name('cde.validation');
        Route::post('/cde/{cde}/validate', [CdeController::class, 'validate'])->name('cde.validate');
        Route::get('/cde/{cde}/annuler-validation', [CdeController::class, 'cancelValidate'])->name('cde.cancel_validate');
        Route::post('/cde/{cde}/save-retours', [CdeController::class, 'saveRetours'])->name('cde.save_retours');
        Route::get('/cde/{cde}/reset', [CdeController::class, 'reset'])->name('cde.reset');
        Route::get('/cde/{ddp}/pdf/download/sans-prix', [CdeController::class, 'pdfDownloadSansPrix'])->name('cde.pdfs.pdfdownload_sans_prix');
        Route::get('/cde/{cde}/pdfs/download', [CdeController::class, 'downloadPdfs'])->name('cde.pdfs.download');
        Route::get('/cde/{cde}/pdfshow/{annee}/{nom}', [CdeController::class, 'showPdf'])->name('cde.pdfshow');
        Route::get('/cde/{cde}/skipmails', [CdeController::class, 'skipMails'])->name('cde.skipmails');
        Route::post('/cde/{cde}/sendmails', [CdeController::class, 'sendMails'])->name('cde.sendmails');
        Route::get('/cde/{cde}/terminer', [CdeController::class, 'terminer'])->name('cde.terminer');
        Route::get('/cde/{cde}/annuler_terminer', [CdeController::class, 'annulerTerminer'])->name('cde.annuler_terminer');
        Route::get('/cde/{cde}/terminer_controler', [CdeController::class, 'terminerControler'])->name('cde.terminer_controler');
        Route::get('/cde/{cde}/annuler_terminer_controler', [CdeController::class, 'annulerTerminerControler'])->name('cde.annuler_terminer_controler');
        Route::post('/cde/{cde}/stock/store', [CdeController::class, 'storeStock'])->name('cde.stock.store');
        Route::post('/cde/{cde}/stock/{ligne}/store', [CdeController::class, 'storeStockLigne'])->name('cde.stock.ligne.store');
        Route::delete('/cde/{cde}/stock/ligne/{ligne}/destroy', [CdeController::class, 'destroyMouvements'])->name('cde.stock.mouvement.destroy');
        Route::get('/cde/{cde}/stock/no', [CdeController::class, 'noStock'])->name('cde.stock.no');
    });
    Route::middleware('permission:voir_les_affaires')->group(function () {
        Route::get('/affaires', [AffaireController::class, 'index'])->name('affaires.index');
        Route::get('/affaires/actualiser', [AffaireController::class, 'actualiserAllTotals'])->name('affaires.actualiser_totals');
        Route::get('/affaires/create', [AffaireController::class, 'create'])->name('affaires.create');
        Route::post('/affaires/store', [AffaireController::class, 'store'])->name('affaires.store');
        Route::get('/affaires/{affaire}/edit', [AffaireController::class, 'edit'])->name('affaires.edit');
        Route::patch('/affaires/{affaire}/update', [AffaireController::class, 'update'])->name('affaires.update');
        Route::delete('/affaires/{affaire}/delete', [AffaireController::class, 'destroy'])->name('affaires.destroy');
        Route::get('/affaires/{affaire}', [AffaireController::class, 'show'])->name('affaires.show');
    });

    // Routes pour le système de médias
    Route::get('/media/download/{mediaId}', [MediaController::class, 'download'])->name('media.download');
    Route::post('/media/{model}/{id}', [MediaController::class, 'store'])->name('media.store');


    Route::middleware('permission:gerer_les_medias')->group(function () {
        Route::get('/media', [MediaController::class, 'index'])->name('media.index');
        Route::put('/media/{media}', [MediaController::class, 'update'])->name('media.update');
        Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    });


    // Route pour générer un lien signé vers la page d'upload par QR code
    Route::get('/media/generate-qr/{model}/{id}', [MediaController::class, 'generateQrLink'])->name('media.generate-qr');
    Route::get('/media/{id}', [MediaController::class, 'show'])->name('media.show');
    Route::patch('/media/{id}/commentaire/save', [MediaController::class, 'updateCommentaire'])->name('media.commentaire.save');
    Route::patch('/media/{id}/type/save', [MediaController::class, 'updateType'])->name('media.type.save');
});

// Route d'upload via QR code (protégée par signature)
Route::get('/media/upload/{model}/{id}/{token}', [MediaController::class, 'showUploadForm'])
    ->name('media.upload-form')
    ->middleware('signed');
// Route POST pour traiter l'upload via QR code
Route::post('/media/upload/{model}/{id}/{token}', [MediaController::class, 'uploadFromQr'])
    ->name('media.upload')
    ->middleware(['signed', 'PreventDebugMode'])
    ->withoutMiddleware([VerifyCsrfToken::class, ValidatePostSize::class]);


require __DIR__ . '/auth.php';

// Import matières Excel
