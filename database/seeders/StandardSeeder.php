<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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

            foreach ($pdfFiles as $file) {
                if ($file->getExtension() === 'pdf') {
                    $standard = DB::table('standards')->insert([
                        'nom' => str_replace('.pdf','',$file->getFilename()),
                    ]);
                    DB::table('standard_versions')->insert([
                        'standard_id' => $standard,
                        'version' => 'A',
                        'chemin_pdf' => $file->getPathname(),
                    ]);
                }
            }
        }
    }
}
