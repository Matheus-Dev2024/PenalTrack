<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SegGrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('seg_grupo')->insert(
            [
                [
                    'id' => 1,
                    'usuario_id' =>	587,
                    'perfil_id' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
            ]
            );
    }
}
