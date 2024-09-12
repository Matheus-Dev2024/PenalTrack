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
    public function listagem(): Factory|Application|View
    {
        return view('TelaDeListagem');
    }

    public function TelaCadastro(): Factory|Application|View
    {
        return view('TelaCadastro');
    }

    public function edicao($id): Factory|View|Application
    {
        $preso = Presos::findOrFail($id);
        return view('TelaDeEdicao', compact('preso'));
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $preso = Presos::findOrFail($id);
        $preso->update($validatedData);

        return response()->json(['message' => 'Dados atualizados com sucesso']);
    }

    public function pesquisar(Request $request): JsonResponse
    {

        $nome = $request->input('nome');
        $dataInicio = $request->input('data_inicio');
        $dataFim = $request->input('data_fim');


        $query = Presos::query();

        if ($nome) {
            $query->where('nome', $nome);
        }

        if ($dataInicio && $dataFim) {

            $dateFormat = 'Y-m-d';
            $startDate = \DateTime::createFromFormat($dateFormat, $dataInicio);
            $endDate = \DateTime::createFromFormat($dateFormat, $dataFim);

            if ($startDate && $startDate->format($dateFormat) === $dataInicio &&
                $endDate && $endDate->format($dateFormat) === $dataFim) {
                $query->whereBetween('created_at', [$dataInicio, $dataFim . ' 23:59:59']);
            } else {
                return response()->json([
                    'message' => 'Formato de data inválido.',
                ], 400);
            }
        }

        $presos = $query->get(['id', 'nome', DB::raw("to_char(created_at, 'DD/MM/YYYY') as data_cadastro")]);

        return response()->json($presos);
    }



    public function listar(Request $request): JsonResponse
    {
        $nome = $request->input('nome');
        $dataInicio = $request->input('data_inicio');
        $dataFim = $request->input('data_fim');

        $query = Presos::query();

        if ($nome) {
            $query->where('nome', $nome);
        }

        if ($dataInicio && $dataFim) {

            $dateFormat = 'Y-m-d';
            $startDate = \DateTime::createFromFormat($dateFormat, $dataInicio);
            $endDate = \DateTime::createFromFormat($dateFormat, $dataFim);

            if ($startDate && $startDate->format($dateFormat) === $dataInicio &&
                $endDate && $endDate->format($dateFormat) === $dataFim) {
                $query->whereBetween('created_at', [$dataInicio, $dataFim . ' 23:59:59']);
            } else {
                return response()->json([
                    'message' => 'Formato de data inválido.',
                ], 400);
            }
        }

        $presos = $query->get(['id', 'nome', DB::raw("to_char(created_at, 'DD/MM/YYYY') as data_cadastro")])
            ->toArray();

        return response()->json($presos);
    }



    public function cadastro(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nome' => 'required|string|max:255',
            ]);
            $preso = Presos::create($request->all());
            return response()->json([
                'message' => 'Registro criado com sucesso',
                'data' => $preso
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Erro ao salvar dados',
                'error' => $exception->getMessage()
            ], 500);
        }
    }
}
