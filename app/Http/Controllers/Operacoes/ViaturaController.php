<?php

namespace App\Http\Controllers\Operacoes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operacoes\ViaturaRequest;
use App\Http\Resources\Operacoes\ViaturaResource;
use App\Models\Entity\Operacoes\Viaturas;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PoliciaCivil\Seguranca\Models\Regras\DB;

class ViaturaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(ViaturaRequest $request) : Response
    {
        $data = $request->valid();

        try{
            DB::beginTransaction();
            $viatura = Viaturas::create($data);
            DB::commit();

            return response(new ViaturaResource($viatura), 201);

        } catch(\Exception $e) {
            DB::rollBack();

            return response(exibirErro($e, 'Erro ao cadastrar Viatura'), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $viatura): Response
    {
        return response(new ViaturaResource(Viaturas::find($viatura)), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ViaturaRequest $request, Viaturas $viatura): Response
    {
        $data = $request->valid();

        try{
            DB::beginTransaction();
            $viatura->update($data);
            $viatura->fresh();
            DB::commit();

            return response(new ViaturaResource($viatura), 200);

        } catch(\Exception $e){
            DB::rollBack();

            return response(exibirErro($e, 'Erro ao atualizar Viatura.'), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $viatura) : Response
    {
        try{
            DB::beginTransaction();
            Viaturas::destroy($viatura);
            DB::commit();

            return response(null, 204);
        }catch(\Exception $e){
            DB::rollBack();

            return response(exibirErro($e, 'Erro ao remover Viatura.'), 500);
        }
    }

    public function viaturasDaOperacao($operacao) : Response
    {
        $viatura = Viaturas::where('operacao_id', $operacao)->get();
 
        return response(ViaturaResource::collection($viatura), 200);
    }
}
