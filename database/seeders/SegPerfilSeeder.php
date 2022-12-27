<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SegPerfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('seg_perfil')->insert(
            [
                [
                    'id' => 1,
                    'nome' => 'root',
                    'created_at' => date('Y-m-d H:i:s')
                ],
            ]
        );
    }
}
