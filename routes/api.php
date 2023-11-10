<?php

use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\AvaliadorController;
use App\Http\Controllers\FatorAvaliacaoController;
use App\Http\Controllers\FatorAvaliacaoItemController;
use App\Http\Controllers\ImpressaoController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PeriodoProcessoAvaliacaoController;
use App\Http\Controllers\ProcessoAvaliacaoController;
use App\Http\Controllers\TipoArquivoController;
use App\Http\Controllers\UnidadesController;
use Illuminate\Support\Facades\Route;

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

//});

//Formulário de Avaliação
Route::controller(AvaliacaoController::class)->group(function () {
    Route::get('avaliacao/info', 'info');
    Route::get('avaliacao/form', 'formulario');
    Route::post('avaliacao/store', 'store');
    Route::get('avaliacao/arquivos', 'GridArquivos');
    Route::post('avaliacao/upload-arquivo', 'uploadArquivo');
    Route::post('avaliacao/arquivos/{arquivo}/destruir', 'ExcluirArquivo');
    Route::get('avaliacao/arquivo-download', 'exibirArquivo'); //carrega um arquivo específico para ser exibido em tela
});


// Rotas de Tipo de Arquivo
Route::controller(TipoArquivoController::class)->group(function () {
    Route::prefix('tipo-arquivo')->group(function () {
        Route::post('/store', 'store');
        Route::get('/grid', 'grid');
        Route::get('/{id}', 'edit');
        Route::post('/update', 'update');
        Route::post('/delete/{tipo}', 'delete');
    });
});

// Rota de Impressao
Route::get('imprimir', [ImpressaoController::class, 'imprimir']);

//Relatorio comissão
Route::get('relatorio-comissao/{fk_servidor}', [PdfController::class, 'gerarRelatorioComissao']);

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

//Rotas da tela de Acompanhamento de Processo
Route::get('processo-avaliacao/acompanhamento-processo-avaliacao/grid', [ProcessoAvaliacaoController::class, 'acompanhamentoServidoresGrid']);
Route::get('processo-avaliacao/acompanhamento-processo-avaliacao/combo-unidade', [ProcessoAvaliacaoController::class, 'comboUnidadeDoProcesso']);
Route::get('processo-avaliacao/acompanhamento-processo-avaliacao/combo-avaliador', [AvaliadorController::class, 'comboAvaliador']);
Route::get('processo-avaliacao/acompanhamento-processo-avaliacao/combo-processo', [ProcessoAvaliacaoController::class, 'comboProcessoTelaAcompanhamento']);
Route::get('processo-avaliacao/acompanhamento-processo-avaliacao/combo-status', [ProcessoAvaliacaoController::class, 'comboStatusTelaAcompanhamento']);
Route::get('processo-avaliacao/acompanhamento-processo-avaliacao/servidores-processo', [ProcessoAvaliacaoController::class, 'getServidoresProcesso']);

//Período do processo de avaliação
Route::get('periodo-processo-avaliacao/combo', [PeriodoProcessoAvaliacaoController::class, 'combo']);

//Avaliador
Route::get('avaliador/grid-pesquisar-avaliador', [AvaliadorController::class, 'index']);
Route::get('avaliador/{avaliador}', [AvaliadorController::class, 'show']);
Route::post('avaliador', [AvaliadorController::class, 'store']);
Route::post('avaliador/alterar', [AvaliadorController::class, 'update']);
Route::post('avaliador/remover-avaliador/{id}', [AvaliadorController::class, 'destroy']);
Route::post('avaliador/remover-servidor-individual/{id}', [AvaliadorController::class, 'removerServidorIndividualmente']);

// unidades
// Route::get('avaliador/grid-unidades', [AvaliadorController::class, 'gridUnidades']);
Route::get('avaliador/unidades-grid/{id}', [AvaliadorController::class, 'unidadesGrid']);
Route::post('avaliador/adicionar-unidades', [AvaliadorController::class, 'adicionarUnidades']);
Route::post('avaliador/remover-unidades/{id}', [AvaliadorController::class, 'destroyUnidades']);

// // refatoração das rotas de unidades
Route::get('unidades', [UnidadesController::class, 'index']);
// Route::get('unidades/{id}', [UnidadesController::class, 'show']);
// Route::post('unidades', [UnidadesController::class, 'store']);
// Route::delete('unidades/{id}', [UnidadesController::class, 'destroy']);