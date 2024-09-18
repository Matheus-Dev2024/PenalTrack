<?php
use App\Http\Controllers\PresosController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;


Route::get('/', [PresosController::class, 'TelaCadastro'])->name('TelaCadastro');
Route::get('/Listagem', [PresosController::class, 'listagem'])->name('Listagem');
Route::get('/presos/{id}/edicao', [PresosController::class, 'edicao'])->name('presos.edicao');


Route::post('/cadastrar/preso', [PresosController::class, 'cadastro']);
Route::get('/pesquisar/presos', [PresosController::class, 'pesquisar']);
Route::get('listar/presos', [PresosController::class, 'listar']);
Route::get('/presos/{id}/edit', [PresosController::class, 'edit'])->name('presos.edit');
Route::put('/presos/{id}', [PresosController::class, 'update'])->name('presos.update');
//Route::delete('/presos/{id}', [PresosController::class, 'destroy'])->name('presos.destroy');




Route::group(['middleware' => ['acesso.unico']], function () {
    Route::get('/acesso/usuario/token/{token}/{usuario}', [UsuarioController::class, 'acessoViaIntranet']);

});



