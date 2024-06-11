<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class AvaliadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'nome_servidor' => $this->nome_servidor,
            'periodo' => $this->periodo,
            'fk_periodo' => $this->fk_periodo,
            'unidade' => $this->unidade,
            'status' => $this->status,
            'cargo' => $this->cargo,
            'fk_status' => $this->fk_status,
            'id' => $this->id
        ];
    }
}
