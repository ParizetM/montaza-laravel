<?php

namespace Database\Seeders;

use App\Models\ConditionPaiement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConditionPaiementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ConditionPaiement::create(['nom' => 'VIREMENT CIC 45 JOURS']);
        ConditionPaiement::create(['nom' => 'VIREMENT CIC 60 JOURS']);
        ConditionPaiement::create(['nom' => 'VIREMENT CIC 30 JOURS']);
        ConditionPaiement::create(['nom' => '45 JOURS FDM']);
        ConditionPaiement::create(['nom' => '60 JOURS FDM']);
        ConditionPaiement::create(['nom' => '30 JOURS FDM']);
    }
}
