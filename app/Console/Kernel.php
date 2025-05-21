<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // ...existing scheduled tasks...

        // Nettoyer les fichiers temporaires de Livewire tous les jours à minuit
        $schedule->command('livewire:clean-temp')->daily();
    }

    // ...existing code...
}
