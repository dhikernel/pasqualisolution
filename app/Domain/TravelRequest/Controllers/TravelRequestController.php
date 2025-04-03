<?php

declare(strict_types=1);

namespace App\Domain\TravelRequest\Controllers;

use App\Domain\TravelRequest\Repositories\TravelRequestRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class TravelRequestController extends Controller
{
    protected TravelRequestRepository $repository;

    protected array $validators = [
        'applicant_name' => 'required|string|max:255',
        'destination' => 'required|string|max:255',
        'departure_date' => 'required|date|after_or_equal:today',
        'return_date' => 'required|date|after:departure_date',
    ];

    public function __construct(TravelRequestRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *     path="/travel/list",
     *     summary="Lista todas as solicitações de viagem",
     *     tags={"Travel Requests"},
     *     @OA\Response(response=200, description="Lista de solicitações de viagem"),
     *     @OA\Response(response=400, description="Erro na requisição")
     * )
     */
    public function index(Request $request)
    {
        return parent::index($request);
    }

    /**
     * @OA\Post(
     *     path="/travel/create",
     *     summary="Cria uma nova solicitação de viagem",
     *     tags={"Travel Requests"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"applicant_name", "destination", "departure_date", "return_date"},
     *             @OA\Property(property="applicant_name", type="string", example="João Silva"),
     *             @OA\Property(property="destination", type="string", example="Paris"),
     *             @OA\Property(property="departure_date", type="string", format="date", example="2025-06-15"),
     *             @OA\Property(property="return_date", type="string", format="date", example="2025-06-30")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Solicitação criada com sucesso"),
     *     @OA\Response(response=400, description="Erro na requisição")
     * )
     */
    public function store(Request $request)
    {
        return parent::store($request);
    }

    /**
     * @OA\Put(
     *     path="/travel/update",
     *     summary="Atualiza uma solicitação de viagem",
     *     tags={"Travel Requests"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "applicant_name", "destination", "departure_date", "return_date"},
     *             @OA\Property(property="id", type="integer", example=2),
     *             @OA\Property(property="applicant_name", type="string", example="João Silva"),
     *             @OA\Property(property="destination", type="string", example="Paris"),
     *             @OA\Property(property="departure_date", type="string", format="date", example="2025-06-15"),
     *             @OA\Property(property="return_date", type="string", format="date", example="2025-06-30")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Solicitação atualizada com sucesso"),
     *     @OA\Response(response=404, description="Solicitação não encontrada")
     * )
     */
    public function updateTravel(Request $request)
    {
        try {
            if (!empty($this->repository)) {
                return response()->json($this->repository->updateTravelRequest($request->all()))
                    ->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()])
                ->setStatusCode(Response::HTTP_NOT_FOUND, Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @OA\Post(
     *     path="/travel/aprovar",
     *     summary="Aprova uma solicitação de viagem",
     *     tags={"Travel Requests"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Solicitação aprovada"),
     *     @OA\Response(response=404, description="Solicitação não encontrada")
     * )
     */
    public function aprovar(Request $request)
    {
        try {
            if (!empty($this->repository)) {
                return response()->json($this->repository->statusAprovar($request->all()))
                    ->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()])
                ->setStatusCode(Response::HTTP_NOT_FOUND, Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @OA\Post(
     *     path="/travel/cancelar",
     *     summary="Cancela uma solicitação de viagem",
     *     tags={"Travel Requests"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Solicitação cancelada"),
     *     @OA\Response(response=404, description="Solicitação não encontrada")
     * )
     */
    public function cancelar(Request $request)
    {
        try {
            if (!empty($this->repository)) {
                return response()->json($this->repository->statusCAncelar($request->all()))
                    ->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()])
                ->setStatusCode(Response::HTTP_NOT_FOUND, Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }
    }
}
