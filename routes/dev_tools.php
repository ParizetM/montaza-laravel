<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// ... existing code ...

// Route temporaire pour forcer la migration depuis le navigateur
Route::get('/dev/run-migrations', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return '<h1>Migrations exécutées</h1><pre>' . Artisan::output() . '</pre><a href="/">Retour à l\'accueil</a>';
    } catch (\Exception $e) {
        return '<h1>Erreur</h1><pre>' . $e->getMessage() . '</pre>';
    }
});

Route::get('/dev/run-tests', function () {
    try {
        // Optionnel : Effacer le cache config pour être sûr que phpunit.xml est pris en compte ou que l'environnement est propre
        Artisan::call('config:clear');

        // Exécuter les tests via Artisan
        // Note: Artisan::call('test') affichera la sortie formatée CLI.
        // On essaye de capturer le buffer.

        $exitCode = Artisan::call('test', ['--filter' => 'Devis']);
        $output = Artisan::output();

        // Convertir les codes couleurs ANSI en HTML basique pour lisibilitÃ©
        $output = preg_replace('/\e\[32m/', '<span style="color:green">', $output);
        $output = preg_replace('/\e\[31m/', '<span style="color:red">', $output);
        $output = preg_replace('/\e\[33m/', '<span style="color:orange">', $output);
        $output = preg_replace('/\e\[39m/', '</span>', $output);
        $output = preg_replace('/\e\[0m/', '</span>', $output);

        return '<h1>Exécution des Tests Unitaires (Filtre: Devis)</h1>
                <pre style="background: #1e1e1e; color: #cfcfcf; padding: 15px; border-radius: 5px; font-family: monospace;">' . $output . '</pre>
                <p>Code de sortie: ' . $exitCode . '</p>
                <a href="/">Retour à l\'accueil</a>';
    } catch (\Exception $e) {
        return '<h1>Erreur lors des tests</h1><pre>' . $e->getMessage() . '</pre><pre>' . $e->getTraceAsString() . '</pre>';
    }
});
