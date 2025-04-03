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
        $user = Auth::user();

        $query = QueryBuilder::for(TravelRequest::class)
            ->allowedFilters([
                AllowedFilter::exact('id', 'id'),
                AllowedFilter::exact('status', 'status'),
                AllowedFilter::custom('departure_date', new FiltersBetween()),
                AllowedFilter::custom('return_date', new FiltersBetween()),
            ])
            ->where('user_id', $user->id)
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

    public function updateTravelRequest(array $data)
    {
        $user = Auth::user();

        $updateTravel = TravelRequest::where('id', $data['travelId'])
            ->where('user_id', $user->id)
            ->first();

        if (!$updateTravel) {
            return [
                'message' => 'Você não tem permissão para atualizar esta solicitação de viagem.'
            ];
        }
        $updateTravel->fill($data);
        $updateTravel->save();

        return [
            'message' => 'Solicitação de viagem atualizada com sucesso!'
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

        if (!$updateTravelStatus) {
            return ['mensagem' => 'Solicitação de viagem não encontrada.'];
        }

        $updateTravelStatus->status = TravelRequestStatus::APROVADO->value;
        $updateTravelStatus->fill($travelRequest);
        $updateTravelStatus->save();

        return ['mensagem' => 'Status aprovado com sucesso!'];
    }


    public function statusCancelar($travelRequest)
    {
        $user = Auth::user();
        $findTravel = TravelRequest::where('user_id', $user->id)->first();

        if ($findTravel) {
            return [
                'mensagem' => 'Você não está autorizado a atualizar o status da sua própria solicitação de viagem ou a solicitação não existe!'
            ];
        }

        $updateTravelStatus = TravelRequest::find($travelRequest['travelId']);

        if (!$updateTravelStatus) {
            return ['mensagem' => 'Solicitação de viagem não encontrada.'];
        }

        $updateTravelStatus->status = TravelRequestStatus::CANCELADO->value;
        $updateTravelStatus->fill($travelRequest);
        $updateTravelStatus->save();

        return ['mensagem' => 'Status foi cancelado com sucesso.'];
    }
}
