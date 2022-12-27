<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FatorAvaliacaoItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fator_avaliacao_item')->insert(
            [
                [
                    'id' => 1,
                    'fk_fator_avaliacao' =>	1,
                    'pergunta' =>	'Comparecer regularmente ao trabalho',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 2,
                    'fk_fator_avaliacao' =>	1,
                    'pergunta' =>	'É pontual',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 3,
                    'fk_fator_avaliacao' =>	1,
                    'pergunta' =>	'Permanece no local de trabalho durante o expediente',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 4,
                    'fk_fator_avaliacao' =>	1,
                    'pergunta' =>	'Informa, tempestivamente, imprevistos que impeçam cumprimento de horário',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 5,
                    'fk_fator_avaliacao' =>	2,
                    'pergunta' =>	'Cumpre os preceitos e normas internas e submete-se ao regulamento do Órgão',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 6,
                    'fk_fator_avaliacao' =>	2,
                    'pergunta' =>	'Cumpre as ordens verbais e escritas de seus superiores hierárquicos',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 7,
                    'fk_fator_avaliacao' =>	2,
                    'pergunta' =>	'Ajusta-se às situações ambientais. Sabe expressar sua opnião, acatar críticas e aceitar mudanças propostas',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 8,
                    'fk_fator_avaliacao' =>	2,
                    'pergunta' =>	'Demonstra conduta compatível com a relevância do cargo que ocupa e evita comentários e atitudes comprometedoras à imagem do Órgão',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 9,
                    'fk_fator_avaliacao' =>	3,
                    'pergunta' =>	'Procura conhecer a estrutura e funcionamento do Órgão',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 10,
                    'fk_fator_avaliacao' =>	3,
                    'pergunta' =>	'Investe em seu aperfeiçoamento profissional. Atualiza-se e procura conhecer as normas pertinentes às atribuições do cargo que ocupa',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 11,
                    'fk_fator_avaliacao' =>	3,
                    'pergunta' =>	'Soluciona problemas e dúvidas do cotidiano. Sabe encaminhar, correta e adequadamente os assuntos que  fogem á sua alçada decisória',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 12,
                    'fk_fator_avaliacao' =>	3,
                    'pergunta' =>	'Põe-se á disposição da chefia, espontaneamente, para realizar novas tarefas e auxiliar colegas',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 13,
                    'fk_fator_avaliacao' =>	4,
                    'pergunta' =>	'Trabalha de forma regular, contante e utiliza os recursos tecnológicos disponíveis, dentro de sua melhor capacidade, segundo orientações técnicas',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 14,
                    'fk_fator_avaliacao' =>	4,
                    'pergunta' =>	'Organiza as tarefas segundo as prioridades e aproveita eventual disponibilidade de forma procedente',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 15,
                    'fk_fator_avaliacao' =>	4,
                    'pergunta' =>	'Cumpre, com eficiência, as metas propostas pela instituição e as tarefas designadas pela chefia emediata',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 16,
                    'fk_fator_avaliacao' =>	4,
                    'pergunta' =>	'Seu trabalho é de excelente qualidade, realiza as metas com dinâmica e racionaliza o tempo na execução de tarefas',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 17,
                    'fk_fator_avaliacao' =>	5,
                    'pergunta' =>	'Suas tarefas são realizadas dentro dos prazos e condições estipulados',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 18,
                    'fk_fator_avaliacao' =>	5,
                    'pergunta' =>	'Demonstra dedicação ao trabalho, e o resultado do mesmo é confiável',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 19,
                    'fk_fator_avaliacao' =>	5,
                    'pergunta' =>	'Busca solucionar as dificuldades de trabalho. É discreto e reservado quanto aos assuntos de interesse do Órgão.',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 20,
                    'fk_fator_avaliacao' =>	5,
                    'pergunta' =>	'Demonstra zelo pelo ambiente de trabalho. Atende a todos sem distinção, com urbanidade',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
            ]
        );
    }
}
