<?php

namespace Database\Seeders;

use App\Models\Mailtemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MailtemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mailtemplates = [
            ['nom' => 'ddp', 'sujet' => 'Demande de prix {code_ddp}', 'contenu' => 'Bonjour, {br/} Pourriez-vous svp nous donner par retour votre {underline}{strong} tarif et délai{/strong}{/underline} suivant la pièce jointe. {br/} Merci {br/} Cordialement']
        ];

        foreach ($mailtemplates as $mailtemplate) {
            Mailtemplate::updateOrCreate($mailtemplate);
        }
    }
}
