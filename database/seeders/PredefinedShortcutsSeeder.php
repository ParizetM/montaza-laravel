<?php

namespace Database\Seeders;

use App\Models\PredefinedShortcut;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PredefinedShortcutsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shortcuts = [
            [
                'title' => 'Ajouter un contact',
                'icon' => 'new-contact',
                'modal' => json_encode([
                    'title' => 'Ajouter-un-contact',
                    'route' => 'societes.contacts.quickCreate',
                    'vue' => 'societes.contacts.quick-create',
                    'varName' => 'societes',
                ]),
            ],
            [
                'title' => 'Consulter l\'historique',
                'url' => "model_changes.index",
                'icon' => 'history',
            ],
            [
                'title' => 'Consulter les notifications',
                'url' => "notifications.index",
                'icon' => 'bell',
            ],
            [
                'title' => 'Consulter les postes',
                'url' => "roles",
                'icon' => 'badge',
            ],
            [
                'title' => 'Consulter les permissions',
                'url' => "permissions",
                'icon' => 'key',
            ],
            [
                'title' => 'Consulter les profils',
                'url' => "profile.index",
                'icon' => 'group',
            ]

        ];

        foreach ($shortcuts as $shortcut) {
            PredefinedShortcut::create($shortcut);
        }
    }
}
