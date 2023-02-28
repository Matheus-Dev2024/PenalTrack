<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoArquivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = date('Y-m-d H:i:s');
        $dados = [
            [
              'id' => 1,
              'nome' => 'FICHA FUNCIONAL DO SERVIDOR',
              'created_at' => $data
            ],
            [
                'id' => 2,
                'nome' => 'FORMULÁRIO DE AVALIAÇÃO',
                'created_at' => $data
            ],
            [
                'id' => 3,
                'nome' => 'DOCUMENTO DE RECURSO DO SERVIDOR',
                'created_at' => $data
            ],
            [
                'id' => 4,
                'nome' => 'FORMULÁRIO DE AVALIAÇÃO - RECURSO',
                'created_at' => $data
            ],
        ];
        DB::table('tipo_arquivo')->insert($dados);
    }
}
