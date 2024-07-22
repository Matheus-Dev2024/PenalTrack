<?php

namespace App\Http\Controllers;

use App\Http\Mail\Email;
use App\Models\Entity\ProcessoAvaliacaoServidor;
use App\Models\Entity\Servidor;
use App\Models\Entity\UsuarioAvaliaUnidades;
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
use Illuminate\Support\Facades\Mail;
use PoliciaCivil\Seguranca\Models\Entity\Usuario;
use PoliciaCivil\Seguranca\Models\Entity\UsuarioSistema;

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

                // if(Log::count() > 9) {
                //     self::removeLogMaisAntigo();
                // }

            } else {
                DB::select("SELECT eprobatorio.registro_processo_avaliacao_servidor(?)", [$data_parametro]);
    
                self::gravarLog();
            }


            //Pegar os servidores cujo o processo esta como a coluna "notificado" = false

            //enviar email para o avaliado e avaliador sobre a conclusão do processo


            return Response('sucesso', 200);
        } catch (Exception $e){
             return Response($e->getMessage(), 500);
            //return Response('falha', 500);
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


    public function enviarNotificacaoPorEmailAvaliador()
    {
        $avaliadores = self::getDadosAvaliador();
        foreach ($avaliadores as $avaliador){
            $data = $avaliador;
            Mail::to($avaliador->email_avaliador)->send(new Email($data));
        }
               
        return 'Email Enviado'; 
    }

    public function getDadosAvaliador() 
    {
        //status 1 = aguardando avaliação
        $avaliador = ProcessoAvaliacaoServidor::from('processo_avaliacao_servidor as pas')
        ->where('pas.status', 1)
        ->where('pas.notificado', false)
        ->join('srh.sig_servidor as ss', 'pas.fk_servidor', '=', 'ss.id_servidor')
        ->join("srh.sig_cargo as c", "c.id", "=", "ss.fk_id_cargo")
        ->join('usuario_avalia_unidades as uau', 'uau.unidade_id', '=', 'pas.fk_unidade')
        ->join('seguranca.usuario as su', 'su.id', '=', 'uau.usuario_id')
        ->join('periodos_processo as pp', 'pas.fk_periodo', '=', 'pp.id')
        ->select(
            'c.abreviacao as cargo',
            'pas.fk_unidade',
            'ss.nome as nome_avaliado',
            'ss.matricula as matricula_avaliado',
            'su.nome as nome_avaliador',
            'su.email as email_avaliador',
            'pp.nome as periodo'
        )
        ->get();

        return $avaliador;

    }

}