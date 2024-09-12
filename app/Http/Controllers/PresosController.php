<?php

namespace App\Http\Controllers;

use App\Models\Presos;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresosController extends Controller
{
    public function listagem(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('TelaDeListagem');
    }


    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('TelaDeCadastro');
    }

    public function cadastro(Request $request): JsonResponse
    {
        try {
            $presos = Presos::create($request->all());
            return response()->json([
                'message' => 'Preso Cadastrado Com Sucesso!',
                'presos' => $presos
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Erro ao salvar dados',
                'erro' => $exception->getMessage()
            ], 500);
        }
    }

    public function pesquisar(Request $request): JsonResponse
    {

        $nome = $request->input('nome');
        $preso = Presos::where('nome', $nome)->get(['id', 'nome', DB::raw("to_char(created_at, 'DD/MM/YYYY') as data_cadastro"),]);
        return response()->json($preso);
    }


    public function listar(Request $request): JsonResponse
    {
        $presos = Presos::all();
        return response()->json($presos);
    }

}



