<?php

namespace App\Http\Controllers;

use App\Http\Mail\Email;
use App\Http\Mail\EmailAvaliador;
use App\Http\Mail\EmailEstagiario;
use App\Models\Entity\ProcessoAvaliacaoServidor;
use App\Models\Log;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RotinasController extends Controller
{

    public function roboEprobatorio() //:Response
    {
        try {
            $logs = self::getLogsPerdidos();
            $data_parametro = null;
            // Faz o laço e seja feliz
            if ($logs) {

                for ($i = 0; $i < count($logs); $i++) {
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
        } catch (Exception $e) {
            return Response($e->getMessage(), 500);
            //return Response('falha', 500);
        }
    }

    public function getLogsPerdidos(): array
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

        } else {
            return [];
        }
    }

    public function gravarLog($data = null): void
    {
        if ($data !== null) {
            $log = new Log(['data' => $data]);
            $log->save();
        } else {

            $hoje = new DateTime();
            $log = new Log(['data' => $hoje->format('Y-m-d')]);
            $log->save();
        }
    }

    public function removeLogMaisAntigo(): void
    {
        $registroMaisAntigo = Log::orderBy('created_at', 'asc')->first();
        if ($registroMaisAntigo) {
            $registroMaisAntigo->delete();
        }
    }

    public function enviarNotificacaoPorEmailAvaliador()
    {
        $avaliadores = self::getDadosAvaliador();
        foreach ($avaliadores as $avaliador) {
            $data = $avaliador;
            Mail::to($avaliador->email_avaliador)->send(new EmailAvaliador($data));
            Mail::to($avaliador->email_estagiario)->send(new EmailEstagiario($data));
            ProcessoAvaliacaoServidor::where('id', $avaliador->id)
                ->where('status', 1)
                ->update(['notificado' => true]);
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
            ->select(
                'pas.id',
                'c.abreviacao as cargo',
                'pas.fk_unidade',
                'ss.nome as nome_avaliado',
                'ss.matricula as matricula_avaliado',
                'su.nome as nome_avaliador',
                'su.email as email_avaliador',
                'ss.email as email_estagiario',
                'pas.fk_periodo'
            )
            ->get();

        return $avaliador;

    }


}
