<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SegAcaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('seg_acao')->insert(
            [
                [
                    'id' => 1,
                    'nome' =>	'/estagio',
                    'method' => 'GET',
                    'descricao' => 'Listar Servidores do Estágio',
                    'destaque' => true,
                    'nome_amigavel' => 'Listar Servidores do Estágio',
                    'obrigatorio' => false,
                    'grupo' => 'Estágio',
                    'log_acesso' => false,
                    'rota_front' => true,
                    'created_at' => date('Y-m-d H:i:s')
                ],
            ]
        );
    }
}
