<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessoSituacaoServidorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('processo_situacao_servidor')->insert(
            [
                [
                    'id' => 1,
                    'nome' =>	'Pendênte',
                    'created_at' =>	date('Y-m-d H:i:s')
                ],
                [
                    'id' => 2,
                    'nome' =>	'Avaliado',
                    'created_at' =>	date('Y-m-d H:i:s')
                ]
            ]
        );
    }
}
