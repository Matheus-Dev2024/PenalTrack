<?php

namespace App\Http\Controllers;

use App\Models\Entity\ProcessoAvaliacaoServidor;
use App\Models\Facade\ProcessoAvaliacaoDB;
use App\Models\Facade\ProcessoAvaliacaoServidorDB;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class RotinasController extends Controller
{
    public function roboEprobatorio() 
    {

        //1º AV: 1 -> 180
        //2º AV: 181 -> 360
        //3º AV: 361 -> 540
        //4º AV: 541 -> 720
        //5º AV: 721 -> 900
        //6º AV: 901 -> 1080


        //pega a Data de Hoje
        //Subtrai da data de hoje 4 anos para encontrar a data_inicio
        $dataInicio = new DateTime(date('Y-m-d'));
        $data_inicio = $dataInicio->sub(new DateInterval('P4Y'))->format('Y-m-d');

        //formata a data atual para string
        $dataAtual = new DateTime(date('Y-m-d'));
        $dataAtualFormatada = $dataAtual->format('Y-m-d');

        //Pega todos os servidores com (dt_admissao BETWEEN dt_inicio AND dt_hoje)
        $servidoresEstagio = ProcessoAvaliacaoDB::listarServidoresGrid($data_inicio, $dataAtualFormatada);
        //INICIO do Laço
            if ($servidoresEstagio) {
                foreach($servidoresEstagio as $servidor){

                    //Verifica se o servidor já tem um processo de avaliação
                    $processoAvaliacaoServidor = ProcessoAvaliacaoServidorDB::getProcessoAvaliacaoServidor($servidor->servidor);
                    if ($processoAvaliacaoServidor->count() > 0){
                    //SE servidor tem processo           
                        //Pega a dt_termino do último processo, soma mais 1 dia, para obter a dt_inicio do novo processo

                        //$info = DB::select("SELECT * FROM srh.sp_info_servidor_estagio_probatorio('data_termino_ultimo_processo', '$dataAtualFormatada', $servidor->servidor)");
                        dd('tem processo avaliação servidor');
                    }
                    
                    //SENÃO
                        //Pega a quantidade de dias trabalhados (bruto), a partir da dt_admissao (exercicio) até HOJE
                        //Pega a quantidade de ausências, a partir da dt_admissao até HOJE
                    $info = DB::select("SELECT * FROM srh.sp_info_servidor_estagio_probatorio('$servidor->data_exercicio', '$dataAtualFormatada', '$servidor->servidor')");
                    dd($info[0]->dias_trabalhados);
    
                }
            }


                //Subtrai a quantidade de dias trabalho (bruto) - a quantidade de ausências, para obter a quantidade de dias trabalhados (líquido)

                //Pega a quantidade de dias trabalhados líquido e divide por 180 dias, para obter a quantidade de estágios já realizados
                //Exemplo: 980 dias líquidos (6ª AVALIAÇÃO)
                //         dias totais trabalhados até a 6ª avaliação = 1080 dias

                //Subtrai o total de dias da 6ª avaliação (1080) - total de dias trabalhados líquido (980), para obter a quantidade de dias que faltam para completar os dias de estágio
                //Exemplo: (1080 - 980) = 100 dias restantes

            //FIM DO IF    


        $dataAtual = new DateTime(date('Y-m-d'));

        $dataAtualFormatada = $dataAtual->format('Y-m-d');
        //return response()->json($servidoresEstagio);
        
        if ($servidoresEstagio){
            foreach ($servidoresEstagio as $servidor){
                $processoAvaliacaoServidor = ProcessoAvaliacaoServidorDB::getProcessoAvaliacaoServidor($servidor->servidor);
                //verifica se existe um processo avaliação servidor 
                if ($processoAvaliacaoServidor->count() > 0){
                    //$info = DB::select("SELECT * FROM srh.sp_info_servidor_estagio_probatorio('data_termino_ultimo_processo', '$dataAtualFormatada', $servidor->servidor)");
                    dd('tem processo avaliação servidor');
                }
                $info = DB::select("SELECT * FROM srh.sp_info_servidor_estagio_probatorio('$servidor->data_exercicio', '$dataAtualFormatada', '$servidor->servidor')");
                dd($info);


                if($info){
                    ProcessoAvaliacaoServidor::create([
                        'fk_servidor' => $servidor->servidor,
                        'fk_unidade' => $info[0]->fk_id_unidade,
                        'dias_estagio' => $info[0]->dias_trabalhados,
                        'dias_ausencia' => $info[0]->dias_ausencia
                    ]);
                }
            }
        }
    }
}
// $dataExercicio = new DateTime($servidor->data_exercicio);
// $diferenca = $dataExercicio->diff($dataAtual);

// $dias = $diferenca->days;
//     dd($dias);
