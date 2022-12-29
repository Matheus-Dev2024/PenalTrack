<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessoAvaliacaoServidorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('processo_avaliacao_servidor')->insert(
            [
                [
                    'id' => 1,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 4454,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 2,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 2545,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 3,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 2766,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 4,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 2555,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 5,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 3540,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 6,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 2642,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 7,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 2403,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 8,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 2205,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 9,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 4428,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 10,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 4879,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 11,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 2438,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 12,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 862,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 14,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 2532,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 15,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 2250,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 16,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 2402,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 17,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 2132,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 18,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 2065,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 19,	
                    'fk_processo_avaliacao' => 1,	
                    'fk_servidor' => 1914,
                    'status' =>	1,
                    'created_at' => date('Y-m-d H:i:s')
                ]
            ]
        );
    }
}
