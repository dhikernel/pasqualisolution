<?php

namespace App\Domain\TravelRequest\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nome_solicitante' => $this->applicant_name,
            'destino' => $this->destination,
            'date_partida' => Carbon::parse($this->departure_date)->format('d/m/Y'),
            'data_retorno' => Carbon::parse($this->return_date)->format('d/m/Y'),
            'status' => $this->status,
        ];
    }
}
