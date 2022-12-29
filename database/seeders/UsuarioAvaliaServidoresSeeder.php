<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuarioAvaliaServidoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuario_avalia_servidores')->insert(
            [
                [
                    'id' => 1,
                    'usuario_id' =>	587,
                    'servidor_id' => 2731,
                    'created_at' =>	date('Y-m-d H:i:s'),
                ]
            ]
        );
    }
}
