<?php

namespace Database\Seeders;

use App\Models\Entite;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $permissions = [
            'gerer_les_utilisateurs',
            'gerer_les_permissions',
            'gerer_les_postes',
        ];
        foreach ($permissions as $permission) {
            Permission::factory()->create([
                'name' => $permission,
            ]);
        }
        // User::factory(10)->create();
        Entite::factory()->create([
            'name' => 'Atlantis Montaza',
        ]);
        Entite::factory()->create([
            'name' => 'Atlantis Ventilation',
        ]);
        Entite::factory()->create([
            'name' => 'AMB',
        ]);

        $Gerant = Role::factory()->create([
            'name' => 'Gerant',
            'entite_id' => 1,
            'undeletable' => true,
        ]);
        $Gerant->permissions()->sync(Permission::all());

        Role::factory()->create([
            'name' => 'Responsable Ressources Humaines',
            'entite_id' => 1,
        ]);

        Role::factory()->create([
            'name' => 'Secrétaire',
            'entite_id' => 1,
        ]);
        Role::factory()->create([
            'name' => 'Magasinier',
            'entite_id' => 1,
        ]);
        Role::factory()->create([
            'name' => 'Chef d\'équipe',
            'entite_id' => 2,
        ]);
        Role::factory()->create([
            'name' => 'Assistant Technique',
            'entite_id' => 3,
        ]);
        Role::factory()->create([
            'name' => 'Assistante chargée d\'affaires',
            'entite_id' => 1,
        ]);
        User::factory()->create([
            'last_name' => 'Admin',
            'first_name' => 'Admin',
            'phone' => '0600000000',
            'email' => 'admin@atlantismontaza.fr',
            'password' => Hash::make('Not24get'),
            'role_id' => 1,
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        User::factory()->create([
            'last_name' => 'JOSIPOVIC',
            'first_name' => 'Goran',
            'phone' => '06 88 84 88 53',
            'email' => 'goran.josipovic@atlantismontaza.fr',
            'password' => Hash::make('Gjosipovic'.date('Y')),
            'role_id' => 1,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        User::factory()->create([
            'last_name' => 'NICOL',
            'first_name' => 'Sylvie',
            'phone' => '02 40 17 65 45',
            'email' => 'sylvie.nicol@atlantismontaza.fr',
            'password' => Hash::make('Snicol'.date('Y')), // Replace 'password' with a secure password
            'role_id' => 2,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        User::factory()->create([
            'last_name' => 'AGASSE',
            'first_name' => 'Janie',
            'phone' => '02 40 17 65 62',
            'email' => 'janie.agasse@atlantismontaza.fr',
            'password' => Hash::make('Jagasse'.date('Y')), // Replace 'password' with a secure password
            'role_id' => 3,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        User::factory()->create([
            'last_name' => 'AVRAMOV',
            'first_name' => 'Stefan',
            'phone' => '06 76 81 08 82',
            'email' => 'stefan.avramov@amb.bg',
            'password' => Hash::make('Savramov'.date('Y')), // Replace 'password' with a secure password
            'role_id' => 6,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        User::factory()->create([
            'last_name' => 'BIMBO',
            'first_name' => 'Harri',
            'phone' => '06 30 31 32 13',
            'email' => 'harry.bimbo@atlantisventilation.fr',
            'password' => Hash::make('Hbimbo'.date('Y')), // Replace 'password' with a secure password
            'role_id' => 5,
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        User::factory()->create([
            'last_name' => 'EVANNO',
            'first_name' => 'Mélanie',
            'phone' => '06 30 31 32 13',
            'email' => 'melanie.evanno@atlantismontaza.fr',
            'password' => Hash::make('Mevanno'.date('Y')), // Replace 'password' with a secure password
            'role_id' => 7,
            'updated_at' => now(),
            'created_at' => now(),
        ]);
    }
}
