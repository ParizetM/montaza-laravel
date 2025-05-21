<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('can', function ($expression) {
            return "<?php if (Auth::check() && Auth::user()->hasPermission({$expression})): ?>";
        });

        // Redéfinir @endcan
        Blade::directive('endcan', function () {
            return "<?php endif; ?>";
        });

        // Configurer le répertoire temporaire de Livewire
        $this->configureLivewireTemporaryDirectory();
    }

    protected function configureLivewireTemporaryDirectory()
    {
        // Définir un répertoire temporaire spécifique pour Livewire
        $storage = Storage::disk('local');
        $tmpPath = 'livewire-tmp';

        // S'assurer que le répertoire existe et est accessible en écriture
        if (!$storage->exists($tmpPath)) {
            $storage->makeDirectory($tmpPath);
            // Pour le serveur de production, on peut avoir besoin de définir des permissions
            if (file_exists(storage_path('app/' . $tmpPath))) {
                chmod(storage_path('app/' . $tmpPath), 0775);
            }
        }

        // Configurer Livewire pour utiliser ce répertoire
        config(['livewire.temporary_file_upload.directory' => $tmpPath]);
    }
}
