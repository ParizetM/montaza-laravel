<?php

namespace Database\Seeders;

use App\Models\Matiere;
use App\Models\SousFamille;
use App\Models\Standard;
use App\Models\StandardVersion;
use App\Models\Unite;
use Illuminate\Database\Seeder;

class MatiereProductionSeeder extends Seeder
{
    public function run() {
        Matiere::truncate();
        $path = storage_path('app/public/ressources/test.csv');
        $csv = array_map(function($line) {
            return str_getcsv($line, ';');
        }, file($path));
        $tour = 0;
        $recap = [];
        foreach ($csv as $row) {
            echo 'TOUR :'.$tour . "\n";
            if ($row[7] === '') {
                $row[7] = 'U';
            }
            $unite = Unite::where('short', 'ILIKE', $row[7])->first()->id ?? null;
            $row[0] = preg_replace('/^\x{FEFF}/u', '', $row[0]);
            if ($row[0] === '') {
                $row[0] = 'Autre';
            }
            $sous_famille_model = SousFamille::where('nom','ILIKE',trim($row[0]))->first();
            $sous_famille = $sous_famille_model ? $sous_famille_model->id : null;
            $standardModel = Standard::where('nom', 'ILIKE', $row[4])->first();

            $standard = $standardModel ? $standardModel->getLatestVersion()->id : null;

            if ($unite === null) {
                echo "ERREUR Unite :  \n - " . $row[3] . "\n - " . $row[7] . "\n";
            }
            if ($sous_famille === null) {
                echo "ERREUR SousFamille :  \n - " . $row[3] . "\n - " . $row[0] . "\n";
            }
            if ($standard === null) {
                echo "ERREUR Standard :  \n - " . $row[3] . "\n - " . $row[4] . "\n";
            }
            Matiere::create([
                'ref_interne' => 'AA-' . str_pad($tour, 5, '0', STR_PAD_LEFT),
                'designation' => "{$row[3]}",
                'unite_id' => $unite ?? throw new \Exception("Unite ID is null for row: " . json_encode($row)),
                'sous_famille_id' => $sous_famille ?? throw new \Exception("SousFamille ID is null for row: " . json_encode($row)),
                'standard_version_id' => $standard,
                'dn' => $row[5],
                'epaisseur' => $row[6],
                'prix_moyen' => null,
                'date_dernier_achat' => null,
                'quantite' => 0,
                'stock_min' => 0,
            ]);
            // if ($sous_famille == null) {
            //     $recap[] = $row[0];
            // }

            $tour++;
        }
        // echo "RECAP : \n";
        // foreach ($recap as $item) {
        //     echo $item . "\n";
        // }
    }
}
