<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuarioAvaliaUnidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuario_avalia_unidades')->insert(
            [
                [
                    'id' => 1,
                    'usuario_id' =>	587,
                    'unidade_id' => 9,
                    'created_at' =>	date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 2,
                    'usuario_id' =>	587,
                    'unidade_id' => 299,
                    'created_at' =>	date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 3,
                    'usuario_id' =>	587,
                    'unidade_id' => 9,
                    'created_at' =>	date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 4,
                    'usuario_id' =>	587,
                    'unidade_id' => 677,
                    'created_at' =>	date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 5,
                    'usuario_id' =>	587,
                    'unidade_id' => 680,
                    'created_at' =>	date('Y-m-d H:i:s'),
                ],
            ]
        );
    }
}
