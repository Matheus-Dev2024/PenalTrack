<?php
namespace App\Models\Facade;
use App\Models\Servidor;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ServidorDB

{
    public static function lotacoesPorPeriodo($servidor_id, $dtInicio, $dtTermino=null)
    {
        return DB::select("SELECT * FROM srh.sp_info_servidor_estagio_probatorio('$dtInicio'::date, '$dtTermino'::date, $servidor_id)");
    }

    public static function listaAusenciasPorPeriodo($servidor_id, $dtInicio, $dtTermino=null)
    {
        $ausencias = DB::select("SELECT * FROM srh.sp_ausencias_estagio_probatorio('$dtInicio'::date, '$dtTermino'::date, $servidor_id)");
        return $ausencias;
    }
    public static function grid(\stdClass $p) :Collection
    {
        $campos = [
            'id_servidor as id',
            'nome'
        ];

        $sql = Servidor::orderBy('nome')
            ->where('status', '1');

        if(isset($p->campos))
            $campos = array_merge($campos, $p->campos);

        if(isset($p->nome))
            $sql->where('nome', 'ilike', "%$p->nome%");
        if(isset($p->cep))
            $sql->where('cep', 'ilike', "%$p->cep%");
        if(isset($p->sigla))
            $sql->where('sigla', 'ilike', "%$p->sigla%");

        return $sql->get($campos);
    }

    // Esta action recebe um CPF sem formatação e devolve o servidor que possuir este cpf.
    public static function buscarPorCpf($cpf) : JsonResponse
    {

    $campos = [
        "id_servidor as id",
        "nome",
        "matricula",
        "cargo",
        "fk_id_cargo",
        "cod_cargo",
        "telefone",
        "telefone_funcional",
        "endereco",
        "complemento",
        "email_funcional",
        "fk_id_unidade_atual",
        //"lotacao",
        //"cpf",
        //"dt_nascimento",
        //"sexo",
        //"rg",
        //"orgao_emissor",
        //"pispasep",
        //"fk_id_raca",
        //"fk_id_forma_ingresso",
        //"tip_sangue",
        //"fk_grau_instrucao",
        //"raca",
        //"escolaridade",
        //"estado_civil",
        //"nome_conjuge",
        //"naturalidade",
        //"deficiencia",
        //"nome_deficiencia",
        //"email",
        //"nm_pai",
        //"nm_mae",
        //"dt_admissao",
        //"dt_nomeacao",
        //"dt_posse",
        //"fk_vinculo",
        //"fk_id_unidade_sead",
        //"cod_lotacao",
        //"fk_id_setor",
        //"nome_setor",
        //"fk_id_funcao",
        //"funcao",
        //"celular",
        //"cep",
        //"fk_estado",
        //"fk_id_cidade",
        //"fk_id_bairro",
        //"cidade",
        //"cod_cidade",
        //"nome_imagem",
        //"titulo_num",
        //"titulo_zona",
        //"titulo_secao",
        //"fk_titulo_uf",
        //"titulo_uf",
        //"numero_carteira_funcional_old",
        //"numero_carteira_funcional",
        //"carteira_emitida",
        //"forma_datiloscopica",
        //"num_certificado_militar",
        //"fk_orgao_certificado_militar",
        //"serie_certificado_militar",
        //"habilitado",
        //"num_habilitacao",
        //"dt_emissao_habilitacao",
        //"dt_validade_habilitacao",
        //"categoria_habilitacao",
        //"fk_banco",
        //"agencia",
        //"conta",
        //"dt_cadastro",
        //"dt_alteracao",
        //"usr_cadastro",
        //"usr_alteracao",
        //"fk_id_usuario",
        //"observacoes",
        //"fk_id_situacao_servidor",
        //"status",
        //"usr_interno",
        //"fk_orgao_externo_cedido",
        //"fk_unidade_atuacao",
        //"fk_usuario_cad",
        //"sisp1",
        //"sisp2",
        //"remember_token",
        //"acesso_intranet",
        //"concurso",
        //"subjudice",
        //"numero_concurso",
        //"ctps",
    ];

        $servidor = Servidor::where('cpf', $cpf)->first($campos);
        if (!$servidor) {
            return response()->json([
                'message' => 'Não foi possível localizar o cpf informado',
                'error_code' => 'cpf_not_found'
            ], 404);
        }else{
            return response()->json($servidor);
        }
    }
    public static function info($servidor_id, $processo_id, $dtInicio, $dtTermino)
    {
        //pega os dados do servidor

        $servidor = DB::table('srh.sig_servidor as s')
            ->join('srh.sig_cargo as c', 'c.id', '=', 's.fk_id_cargo')
            ->join('processo_avaliacao_servidor as pas', 'pas.fk_servidor', '=', 's.id_servidor')
            ->join('policia.unidade as u', 'u.id', '=', 'pas.fk_unidade')
            ->leftJoin('processo_situacao_servidor as pss', 'pss.id', '=', 'pas.status')
            ->select([
                's.id_servidor',
                's.nome',
                's.matricula',
                'c.abreviacao as cargo',
                'u.id as unidade_id',
                'u.nome as unidade',
                'pas.fk_processo_avaliacao',
                'pas.status',
                'pss.nome as nome_status'
                //'pas.dias_estagio',
                //'pas.dias_trabalho_programado',
                //'pas.dias_ausencia',
                //'pas.dias_trabalhados',
                //'pas.dias_prorrogados',
            ])
            ->where('pas.fk_processo_avaliacao', $processo_id)
            ->where('s.id_servidor', $servidor_id)
            ->first();



        $infoEstagio = self::lotacoesPorPeriodo($servidor_id, $dtInicio, $dtTermino);

        if(count($infoEstagio) > 0)
            $servidor->periodo = $infoEstagio[0];



        return $servidor;
    }
    public static function lotacaoComMaiorTempoTrabalhado($lotacoes)
    {
        if(count($lotacoes) == 0) {
            return null;
        }

        if(count($lotacoes) == 1) {
            return $lotacoes[0];
        }

        /*
        * caso mude a procedure sp_lotacoes_por_periodo() e retorne mais de um registro
        * descomentar esta instrução
        *
        $maior = 0; //maior recebe o valor do primeiro elemento do array
        $indiceMaior = null;

        foreach($lotacoes as $i => $item) {
            if($item->dias > $maior) {
                $maior = $item->dias_trabalhados;
                $indiceMaior = $i;
            }
        }


        return $lotacoes[$indiceMaior];
        */

    }
}
