<?php

use App\Http\Controllers\DocumentacaoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\CargoController;
use \App\Http\Controllers\ServidorController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\TipoDocumentacaoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Expr\Cast\Object_;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:sanctum')->group(function() {
//Route::group(['middleware' => ['seguranca']], function () {

    //Estágio - Lista servidores
    Route::get('avaliacao/corrente/get-servidores', [AvaliacaoController::class, 'getServidoresAvaliacaoCorrente']);
    Route::get('avaliacao/combo-processo', [AvaliacaoController::class, 'combo']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Portaria
Route::get('/portaria/emitentes', [App\Http\Controllers\Portaria\EmitenteController::class, 'emitentes']);

//Ordem de Serviço
Route::get('/ordem-servico/emitentes', [App\Http\Controllers\OrdemServico\EmitenteController::class, 'emitentes']);

//Publicação
Route::get('/publicacao/boletim/documentos/para/envio', [App\Http\Controllers\Publicacao\PublicacaoController::class, 'listaDocumentosParaEnvio']);
Route::post('/publicacao/store', [App\Http\Controllers\Publicacao\PublicacaoController::class, 'store']);
Route::post('/publicacao/manual/store', [App\Http\Controllers\Publicacao\PublicacaoController::class, 'storePortariaManual']);
Route::post('/publicacao/delete/{publicacao}', [App\Http\Controllers\Publicacao\PublicacaoController::class, 'delete']);
Route::get('/publicacao/preview', [App\Http\Controllers\Publicacao\PublicacaoController::class, 'preview']);
Route::get('/publicacao/get-boletins-por-ano', [App\Http\Controllers\Publicacao\PublicacaoController::class, 'getBoletinsPorAno']);
Route::get('/publicacao/open-boletim', [App\Http\Controllers\Publicacao\PublicacaoController::class, 'openBoletim']);

//Tipo Documentação
Route::get('/tipo-documentacao/grid', [TipoDocumentacaoController::class, 'grid']);
Route::put('/tipo-documentacao/store', [TipoDocumentacaoController::class, 'store']);
Route::put('/tipo-documentacao/update', [TipoDocumentacaoController::class, 'update']);
Route::put('/tipo-documentacao/delete/{id}', [TipoDocumentacaoController::class, 'delete']);
Route::get('/tipo-documentacao/combo-tipo-documentacao', [TipoDocumentacaoController::class, 'combo']);

//Documentação
Route::post('/documentacao/store', [DocumentacaoController::class, 'store']);
Route::post('/documentacao/update', [DocumentacaoController::class, 'update']);
Route::post('/documentacao/delete/{id}', [DocumentacaoController::class, 'delete']);
Route::get('/documentacao/arquivo-download/{id}', [DocumentacaoController::class, 'exibirArquivo']);

//Boletim
Route::post('/boletim/store', [App\Http\Controllers\Publicacao\BoletimController::class, 'store']);

// ROTAS PARA CARGO
Route::controller(CargoController::class)->group(function () {
    Route::prefix('cargo')->group(function () {
        Route::get('/', 'cargosAtivos');
    });
});

// ROTAS PARA CARGO
Route::controller(UnidadeController::class)->group(function () {
    Route::prefix('unidade')->group(function () {
        Route::get('/', 'unidadesAtivas');
        Route::get('/{unidade}', 'show');
    });
});

    // Rota de Impressao
    Route::get('imprimir', [ImpressaoController::class, 'imprimir']);

//});


//Fator de Avaliação
Route::get('fator-avaliacao/grid', [FatorAvaliacaoController::class, 'grid']);
Route::post('fator-avaliacao/salvar', [FatorAvaliacaoController::class, 'salvar']);
Route::post('fator-avaliacao/alterar', [FatorAvaliacaoController::class, 'alterar']);
Route::post('fator-avaliacao/excluir', [FatorAvaliacaoController::class, 'excluir']);

//Fator de Avaliação item
Route::get('fator-avaliacao-item/grid', [FatorAvaliacaoItemController::class, 'grid']);
Route::post('fator-avaliacao-item/salvar', [FatorAvaliacaoItemController::class, 'salvar']);
Route::post('fator-avaliacao-item/alterar', [FatorAvaliacaoItemController::class, 'alterar']);
Route::post('fator-avaliacao-item/excluir', [FatorAvaliacaoItemController::class, 'excluir']);

//Processo de Avaliação
Route::get('processo-avaliacao/grid', [ProcessoAvaliacaoController::class, 'grid']);
Route::get('processo-avaliacao/grid-servidores', [ProcessoAvaliacaoController::class, 'gridServidores']);
Route::get('processo-avaliacao/servidores-grid/{id_processo}', [ProcessoAvaliacaoController::class, 'servidoresGrid']);
Route::post('processo-avaliacao/remover/{id_processo_avaliacao}', [ProcessoAvaliacaoController::class, 'remover']);
Route::get('processo-avaliacao-servidor/listaservidor', [ProcessoAvaliacaoController::class, 'ListaServidor']);
Route::post('processo-avaliacao/salvar', [ProcessoAvaliacaoController::class, 'salvar']);
Route::get('processo-avaliacao/edit/{id}', [ProcessoAvaliacaoController::class, 'editar']);
Route::post('processo-avaliacao/alterar', [ProcessoAvaliacaoController::class, 'alterar']);
Route::post('processo-avaliacao/excluir/{id_periodo_avaliacao}', [ProcessoAvaliacaoController::class, 'excluir']);
Route::get('processo-avaliacao/pesquisardescricao', [ProcessoAvaliacaoController::class, 'pesquisarDescricao']);
Route::get('processo-avaliacao/grid-arquivos/{id}', [ProcessoAvaliacaoController::class, 'exibirArquivo']);
Route::post('processo-avaliacao/salvar-processo-avaliacao-servidor', [ProcessoAvaliacaoController::class, 'salvarProcessoAvaliacaoServidor']);
Route::post('processo-avaliacao/salvar-usuario-avalia-servidor', [ProcessoAvaliacaoController::class, 'salvarUsuarioAvaliaServidor']);
Route::get('processo-avaliacao/grid-usuario-avalia-servidor', [ProcessoAvaliacaoController::class, 'listaUsuarioAvaliaServidor']);

//Avaliador
Route::get('avaliador/grid-pesquisar-avaliador', [AvaliadorController::class, 'index']);
Route::get('avaliador/{avaliador}', [AvaliadorController::class, 'show']);
Route::post('avaliador', [AvaliadorController::class, 'store']);
Route::post('avaliador/alterar', [AvaliadorController::class, 'update']);
Route::delete('avaliador/remover-avaliador/{id}', [AvaliadorController::class, 'destroy']);
Route::delete('avaliador/remover-servidor-individual/{id}', [AvaliadorController::class, 'removerServidorIndividualmente']);

// unidades
// Route::get('avaliador/grid-unidades', [AvaliadorController::class, 'gridUnidades']);
Route::get('avaliador/unidades-grid/{id}', [AvaliadorController::class, 'unidadesGrid']);
Route::post('avaliador/adicionar-unidades', [AvaliadorController::class, 'adicionarUnidades']);
Route::delete('avaliador/remover-unidades/{id}', [AvaliadorController::class, 'destroyUnidades']);

// // refatoração das rotas de unidades
Route::get('unidades', [UnidadesController::class, 'index']);
// Route::get('unidades/{id}', [UnidadesController::class, 'show']);
// Route::post('unidades', [UnidadesController::class, 'store']);
// Route::delete('unidades/{id}', [UnidadesController::class, 'destroy']);



// ROTAS PARA SERVIDOR
Route::controller(ServidorController::class)->group(function () {
    Route::prefix('servidor')->group(function () {
        Route::get('/', 'grid');
        Route::get('/busca-cpf/{cpf}', 'buscarPorCpf'); //exibe um servidor por um CPF
    });
});
