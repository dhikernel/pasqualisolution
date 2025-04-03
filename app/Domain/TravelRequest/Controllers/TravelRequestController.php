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

    public function index(Request $request)
    {
        return parent::index($request);
    }

    public function store(Request $request)
    {
        return parent::store($request);
    }

    public function aprovar(Request $request)
    {
        try {
            if (!empty($this->repository)) {
                return response()->json($this->repository->statusAprovar($request->all()))
                    ->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
            }
        } catch (\Exception $exception) {
            return response()->json(['messagem' => $exception->getMessage()])
                ->setStatusCode(Response::HTTP_NOT_FOUND, Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }
    }

    public function cancelado(Request $request)
    {
        try {
            if (!empty($this->repository)) {
                return response()->json($this->repository->statusCAncelado($request->all()))
                    ->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
            }
        } catch (\Exception $exception) {
            return response()->json(['messagem' => $exception->getMessage()])
                ->setStatusCode(Response::HTTP_NOT_FOUND, Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }
    }
}
