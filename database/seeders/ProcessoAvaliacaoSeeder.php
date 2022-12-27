<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessoAvaliacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('processo_avaliacao')->insert(
            [
                'id' => 1,
                'descricao' => '1º PERÍODO DE AVALIAÇÃO: 01/12/2022 a 30/01/2023',
                'dt_inicio_avaliacao' => '2022-12-01',
                'dt_termino_avaliacao' => '2023-01-30',
                'dt_inicio_estagio' =>	'2022-12-01',
                'ref_inicio' =>	'2018-01-01',
                'ref_termino' => '2018-12-31',
                'instrucao' => '34. Leia com atenção as especificações de cada fator e todos os quesitos antes de iniciar a avaliação.<br>
                35. De acordo com o que mais traduza o desempenho do servidor, atribua a pontuação do 0 (zero) a 5 (cinco) a cada quesito "a", "b", "c" e "d", somando 20 pontos por tópico, num total possível de 100 (cem) pontos.<br>
                36. Concluída a avaliação, elabore o Parecer do Avaliador (Anexo II), junte-o a este formulário e após a ciêNcia do avaliado, encaminhe á Divisão de Recursos Humanos.',
                'created_at' => date('Y-m-d H:i:s')
            ]
        );
    }
}
