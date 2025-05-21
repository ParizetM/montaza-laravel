<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanLivewireTemp extends Command
{
    protected $signature = 'livewire:clean-temp';
    protected $description = 'Nettoie les fichiers temporaires de Livewire';

    public function handle()
    {
        $this->info('Nettoyage des fichiers temporaires de Livewire...');

        $disk = Storage::disk('local');
        $directory = config('livewire.temporary_file_upload.directory', 'livewire-tmp');

        if (!$disk->exists($directory)) {
            $this->info('Le répertoire n\'existe pas encore.');
            return;
        }

        $files = $disk->files($directory);
        $count = 0;

        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp($disk->lastModified($file));
            $hoursSinceModified = $lastModified->diffInHours(now());

            // Supprimer les fichiers de plus de 24 heures
            if ($hoursSinceModified > 24) {
                $disk->delete($file);
                $count++;
            }
        }

        $this->info("{$count} fichiers temporaires supprimés.");
    }
}
