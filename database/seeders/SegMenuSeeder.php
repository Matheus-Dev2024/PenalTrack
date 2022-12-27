<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SegMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('seg_menu')->insert(
            [
                [
                    'id' => 1,
                    'acao_id' => 1,
                    'pai' => null,
                    'nome' => 'Estágio Probatório',
                    'dica' => null,
                    'ativo' => true,
                    'ordem' => 1,
                    'configuracoes' => null,
                    'created_at' => date('Y-m-d H:i:s')
                ],
            ]
        );
    }
}
