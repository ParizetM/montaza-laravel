<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('predefined_shortcuts')
            ->where('url', 'personnel.index')
            ->exists();

        if (! $exists) {
            DB::table('predefined_shortcuts')->insert([
                'title'      => 'Consulter le personnel',
                'icon'       => 'group',
                'url'        => 'personnel.index',
                'modal'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('predefined_shortcuts')
            ->where('url', 'personnel.index')
            ->delete();
    }
};
