<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            FatorAvaliacaoSeeder::class,
            FatorAvaliacaoItemSeeder::class,
            ProcessoAvaliacaoSeeder::class,

            UsuarioAvaliaServidoresSeeder::class,
            UsuarioAvaliaUnidadesSeeder::class,

            // ProcessoAvaliacaoServidorSeeder::class,
            // ProcessoSituacaoServidorSeeder::class,



            SegAcaoSeeder::class,
            SegMenuSeeder::class,
            SegPerfilSeeder::class,
            SegGrupoSeeder::class,
        ]);
    }
}
