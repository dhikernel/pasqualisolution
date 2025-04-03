<?php

namespace App\Domain\TravelRequest\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TravelRequestCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'messagem' => 'Viagens listadas com sucesso!',
            'viagens' => TravelRequestResource::collection($this->collection),
        ];
    }
}
