<?php
use App\Http\Controllers\PresosController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [PresosController::class, 'index'])->name('cadastro');
Route::get('/telaDeListagem', [PresosController::class, 'listagem'])->name('listagem');


Route::post('/cadastrar/preso', [PresosController::class, 'cadastro']);
Route::get('/pesquisar/presos', [PresosController::class, 'pesquisar']);
Route::get('listar/presos', [PresosController::class, 'listar']);


Route::group(['middleware' => ['acesso.unico']], function () {
    Route::get('/acesso/usuario/token/{token}/{usuario}', [UsuarioController::class, 'acessoViaIntranet']);

});



