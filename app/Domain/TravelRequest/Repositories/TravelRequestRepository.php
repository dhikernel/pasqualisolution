<?php

declare(strict_types=1);

namespace App\Domain\TravelRequest\Repositories;

use App\Domain\TravelRequest\Support\FiltersBetween;
use App\Domain\TravelRequest\Resources\TravelRequestCollection;
use App\Domain\TravelRequest\Models\TravelRequest;
use App\Enums\TravelRequestStatus;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\DB;

class TravelRequestRepository
{
    public function index()
    {
        $query = QueryBuilder::for(TravelRequest::class)
            ->allowedFilters([
                AllowedFilter::exact('id', 'id'),
                AllowedFilter::exact('status', 'status'),
                AllowedFilter::custom('departure_date', new FiltersBetween()),
                AllowedFilter::custom('return_date', new FiltersBetween()),
            ])
            ->defaultSort('created_at')
            ->get();

        $returnDoctorCollection = new TravelRequestCollection($query);

        return $returnDoctorCollection->toArray(request());
    }

    public function store(array $data): array
    {
        try {
            DB::beginTransaction();

            $data['status'] = TravelRequestStatus::SOLICITADO->value;

            $createdTravelRequest = TravelRequest::create($data);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw new \Exception($exception->getMessage());
        }

        return [
            'messagem' => 'Viagem solicitada com sucesso!',
            'viagem' => $createdTravelRequest
        ];
    }

    public function statusAprovar($travelRequest)
    {
        $user = Auth::user();

        $findTravel = TravelRequest::where('user_id', $user->id)->first();
        if ($findTravel) {
            return [
                'mensagem' => 'Você não está autorizado a atualizar o status da sua própria solicitação de viagem ou a solicitação não existe!'
            ];
        }
        $updateTravelStatus = TravelRequest::find($travelRequest['travelId']);

        $updateTravelStatus['status'] = TravelRequestStatus::APROVADO->value;

        $updateTravelStatus->fill($travelRequest)->save();

        return [
            'mensagem' => 'status aprovado com sucesso!'
        ];
    }

    public function statusCancelado($travelRequest)
    {
        $user = Auth::user();

        $findTravel = TravelRequest::where('user_id', $user->id)->first();
        if ($findTravel) {
            return [
                'mensagem' => 'Você não está autorizado a atualizar o status da sua própria solicitação de viagem ou a solicitação não existe!'
            ];
        }
        $updateTravelStatus = TravelRequest::find($travelRequest['travelId']);

        $updateTravelStatus['status'] = TravelRequestStatus::CANCELADO->value;

        $updateTravelStatus->fill($travelRequest)->save();

        return [
            'mensagem' => 'status foi cancelado com sucesso.'
        ];
    }
}
