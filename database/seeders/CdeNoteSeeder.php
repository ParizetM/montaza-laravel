<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CdeNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cde_notes')->insert(array_map(fn($contenu) => ['contenu' => $contenu, 'created_at' => now(), 'updated_at' => now()], [
            'Ceci est une note fictive qui décrit une journée ensoleillée où tout semble aller pour le mieux. Les oiseaux chantent, et l\'air est frais.',
            'Aujourd\'hui, j\'ai découvert un petit café caché dans une ruelle. Le café était délicieux, et l\'ambiance était chaleureuse.',
            'Je me suis promené dans le parc cet après-midi. Les feuilles des arbres commencent à changer de couleur, annonçant l\'arrivée de l\'automne.',
            'Ce soir, j\'ai regardé un film inspirant qui m\'a donné envie de poursuivre mes rêves avec encore plus de détermination.',
        ]));
    }
}
