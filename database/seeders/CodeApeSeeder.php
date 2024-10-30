<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CodeApe;

class CodeApeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = storage_path('app/public/ressources/Liste_des_codes_APE_en_france_2024.csv');

        // Vérifier si le fichier existe avant de l'ouvrir
        if (!file_exists($filePath)) {
            throw new \Exception("Le fichier CSV n'existe pas : " . $filePath);
        }

        // Ouvrir le fichier CSV
        $csv = array_map('str_getcsv', file($filePath));


        foreach ($csv as $record) {
            $record = str_getcsv($record[0], ';');
            echo $record['0'] . ' - ' . $record['1'] . PHP_EOL;
            CodeApe::create([
            'code' => $record['0'],
            'nom' => $record['1'],
            ]);
        }

    }
}
