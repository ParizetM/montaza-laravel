<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocieteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('societe_types')->insert([
            ['name' => 'Client'],
            ['name' => 'Fournisseur'],
            ['name' => 'Client et Fournisseur'],
        ]);
    }
}
