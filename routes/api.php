<?php

use App\Http\Controllers\AvaliacaoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FatorAvaliacaoController;
use App\Http\Controllers\FatorAvaliacaoItemController;
use App\Http\Controllers\ImpressaoController;
use App\Http\Controllers\ProcessoAvaliacaoController;
use \App\Http\Controllers\TipoArquivoController;

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
Route::group(['middleware' => ['seguranca']], function () {

    //Estágio - Lista servidores
    Route::get('avaliacao/corrente/get-servidores', [AvaliacaoController::class, 'getServidoresAvaliacaoCorrente']);


    //Formulário de Avaliação
    Route::controller(AvaliacaoController::class)->group(function () {
        Route::get('avaliacao/info', 'info');
        Route::get('avaliacao/form', 'formulario');
        Route::post('avaliacao/store', 'store');
        Route::get('avaliacao/arquivos','GridArquivos');
        Route::post('avaliacao/upload-arquivo', 'uploadArquivo');
        Route::post('avaliacao/arquivos/{arquivo}/destruir', 'ExcluirArquivo');
        Route::get('avaliacao/arquivo-download', 'exibirArquivo'); //carrega um arquivo específico para ser exibido em tela
    });


    // Rotas de Tipo de Arquivo
    Route::controller(TipoArquivoController::class)->group(function() {
        Route::prefix('tipo-arquivo')->group(function () {
            Route::post('/', 'store');
            Route::get('/grid', 'grid');
            Route::get('/{id}', 'edit');
            Route::post('/update', 'update');
            Route::delete('/{tipo}', 'delete');
        });
    });

    // Rota de Impressao
    Route::get('imprimir', [ImpressaoController::class, 'imprimir']);

});


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




