<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UniteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unites = [
            ['short' => 'kg', 'full' => 'Kilogramme', 'full_plural' => 'Kilogrammes', 'type' => 'Poids'],
            ['short' => 'ml', 'full' => 'Mètre linéaire', 'full_plural' => 'Mètres linéaires', 'type' => 'Longueur'],
            ['short' => 'm²', 'full' => 'Mètre carré', 'full_plural' => 'Mètres carrés', 'type' => 'Surface'],
            ['short' => 'm³', 'full' => 'Mètre cube', 'full_plural' => 'Mètres cubes', 'type' => 'Volume'],
            ['short' => 'l', 'full' => 'Litre', 'full_plural' => 'Litres', 'type' => 'Volume'],
            ['short' => 'pièce', 'full' => 'Pièce', 'full_plural' => 'Pièces', 'type' => 'Unité'],
            ['short' => 'unité', 'full' => 'Unité', 'full_plural' => 'Unités', 'type' => 'Unité'],
            ['short' => 'm', 'full' => 'Mètre', 'full_plural' => 'Mètres', 'type' => 'Longueur'],
            ['short' => 'cm', 'full' => 'Centimètre', 'full_plural' => 'Centimètres', 'type' => 'Longueur'],
            ['short' => 'mm', 'full' => 'Millimètre', 'full_plural' => 'Millimètres', 'type' => 'Longueur'],
            ['short' => 'g', 'full' => 'Gramme', 'full_plural' => 'Grammes', 'type' => 'Poids'],
            ['short' => 'mg', 'full' => 'Milligramme', 'full_plural' => 'Milligrammes', 'type' => 'Poids'],
            ['short' => 's', 'full' => 'Seconde', 'full_plural' => 'Secondes', 'type' => 'Temps'],
            ['short' => 'min', 'full' => 'Minute', 'full_plural' => 'Minutes', 'type' => 'Temps'],
            ['short' => 'h', 'full' => 'Heure', 'full_plural' => 'Heures', 'type' => 'Temps'],
            ['short' => 'jour', 'full' => 'Jour', 'full_plural' => 'Jours', 'type' => 'Temps'],
            ['short' => 'mois', 'full' => 'Mois', 'full_plural' => 'Mois', 'type' => 'Temps'],
            ['short' => 'an', 'full' => 'Année', 'full_plural' => 'Années', 'type' => 'Temps'],
            ['short' => 'W', 'full' => 'Watt', 'full_plural' => 'Watts', 'type' => 'Puissance']
        ];

        foreach ($unites as $unite) {
            \App\Models\Unite::create($unite);
        }
    }
}
