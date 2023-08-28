<?php
namespace App\Models\Facade;
use App\Models\Servidor;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class ServidorDB

{
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
}
