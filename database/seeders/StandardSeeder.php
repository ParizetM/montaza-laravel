<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\DossierStandard;
use App\Models\Standard;
use App\Models\StandardVersion;


class StandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stockagePath = Storage::path('standards');

        $directories = File::directories($stockagePath);

        foreach ($directories as $directory) {
            $pdfFiles = File::files($directory);
            $dossierStandard = new DossierStandard;
            $dossierStandard->nom = basename($directory);
            $dossierStandard->save();

            foreach ($pdfFiles as $file) {
            if ($file->getExtension() === 'pdf') {
                $standard = new Standard();
                $standard->nom = str_replace('.pdf', '', $file->getFilename());
                $standard->dossier_standard_id = $dossierStandard->id;
                $standard->save();

                $standardVersion = new StandardVersion();
                $standardVersion->standard_id = $standard->id;
                $standardVersion->version = 'A';
                $standardVersion->chemin_pdf = 'standards/' . $dossierStandard->nom . '/' . $standard->nom . '.pdf';
                $standardVersion->save();
            }
            }
        }
    }
}
