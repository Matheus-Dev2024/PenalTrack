<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FatorAvaliacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fator_avaliacao')->insert(
            [
                [
                    'id' => 1,
                    'nome' =>	'ASSIDUIDADE',
                    'created_at' =>	date('Y-m-d H:i:s'),
                    'descricao' => 'frequência/contância, pontualidade e permanência'
                ],
                [
                    'id' => 2,
                    'nome' =>	'DISCIPLINA',
                    'created_at' =>	date('Y-m-d H:i:s'),
                    'descricao' => 'observância dos padrões estabelecidos pelo órgão'
                ],
                [
                    'id' => 3,
                    'nome' =>	'CAPACIDADE',
                    'created_at' => date('Y-m-d H:i:s'),
                    'descricao' => 'capacidade de ação, empreendimento, independência e autonomia na autuação dentro dos limites estabelecidos'
                ],
                [
                    'id' => 4,
                    'nome' =>	'PRODUTIVIDADE',
                    'created_at' =>	date('Y-m-d H:i:s'),
                    'descricao' => 'rendimento compatível às condições de trabalho e qualidade do serviço na execução de suas atividades'
                ],
                [
                    'id' => 5,
                    'nome' =>	'RESPONSABILIDADE',
                    'created_at' =>	date('Y-m-d H:i:s'),
                    'descricao' => 'dedicação, ética profissional e urbanidade'
                ],
            ]
        );
    }
}
