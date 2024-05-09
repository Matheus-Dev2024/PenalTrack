<?php

namespace App\Http\Controllers;

use App\Models\Entity\ProcessoAvaliacaoServidor;
use App\Models\Facade\ProcessoAvaliacaoDB;
use App\Models\Facade\ProcessoAvaliacaoServidorDB;
use App\Models\Log;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class RotinasController extends Controller
{
    
    public function roboEprobatorio() //:Response
    {
        try{
            $logs = self::getLogsPerdidos();
            $data_parametro = null;
            // Faz o laço e seja feliz
            if ($logs) {

                for ($i=0; $i < count($logs); $i++){
                    DB::select("SELECT * FROM eprobatorio.registro_processo_avaliacao_servidor(?)", [$logs[$i]]);
                    self::gravarLog($logs[$i]);
                }

                if(Log::count() > 9) {
                    self::removeLogMaisAntigo();
                }

            } else {
                DB::select("SELECT eprobatorio.registro_processo_avaliacao_servidor(?)", [$data_parametro]);
    
                self::gravarLog();
            }


            //Pegar os servidores cujo o processo esta como a coluna "notificado" = false

            //enviar email para o avaliado e avaliador sobre a conclusão do processo


            return Response('sucesso', 200);
        } catch (Exception $e){
            // return Response($e->getMessage(), 500);
            return Response('falha', 500);
        }    
    }

    public function gravarLog($data = null) :void
    {
        if ($data !== null) {
            $log = new Log(['data' => $data]);
            $log->save();
        } else {

        $hoje = new \DateTime();
        $log = new Log(['data' => $hoje->format('Y-m-d')]);
        $log->save();
        }
    }

    // public function gravarLog() :void
    // {
    //     $hoje = new \DateTime();
    //     $log = new Log(['data' => $hoje->format('Y-m-d')]);
    //     $log->save();
    // }


    public function removeLogMaisAntigo() :void
    {
        $registroMaisAntigo = Log::orderBy('created_at', 'asc')->first();
        if ($registroMaisAntigo) {
            $registroMaisAntigo->delete();
        }
    }

    public function getLogsPerdidos() :array
    {
        //pega o valor máximo do campo data(a data mais recente)
        $ultimoLog = Log::max('data');

        if ($ultimoLog) {
            $dataUltimoRegistro = Carbon::createFromFormat('Y-m-d', $ultimoLog);
            
            $dataHoje = Carbon::now();
            
            $datasFaltando = [];
            
            // Enquanto a data do registro for anterior à data de hoje, adicione um dia de cada vez ao array
            while ($dataUltimoRegistro->addDay() <= $dataHoje) {
                $datasFaltando[] = $dataUltimoRegistro->toDateString();
            }

            return $datasFaltando;
            // foreach ($datasFaltando as $data) {
            //     echo $data . '<br>';
            // }
        } else {
            return [];
        }
    }

}