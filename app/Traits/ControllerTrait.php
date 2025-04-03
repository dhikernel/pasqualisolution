<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait ControllerTrait
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            if (!empty($this->repository)) {
                return response()->json($this->repository->index($request->all()))
                    ->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())
                ->setStatusCode(Response::HTTP_NOT_FOUND, Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validators);
        if ($validator->fails()) {
            return response()->json([$validator->errors()])
                ->setStatusCode(Response::HTTP_BAD_REQUEST, Response::$statusTexts[Response::HTTP_BAD_REQUEST]);
        }

        try {
            if (!empty($this->repository)) {
                $returnInsert = $this->repository->store($request->all());
                return response()->json([$returnInsert])
                    ->setStatusCode(Response::HTTP_CREATED, Response::$statusTexts[Response::HTTP_CREATED]);
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())
                ->setStatusCode(Response::HTTP_NOT_FOUND, Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id)
    {
        try {
            if (!empty($this->repository)) {
                $returnShow = $this->repository->getById($id);
            }
            return response()->json([$returnShow])
                ->setStatusCode(Response::HTTP_NO_CONTENT, Response::$statusTexts[Response::HTTP_NO_CONTENT]);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())
                ->setStatusCode(Response::HTTP_NOT_FOUND, Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        try {
            if (!empty($this->repository)) {
                $returnUpdate = $this->repository->update($request->all(), $id);
                return response()->json([$returnUpdate])
                    ->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())
                ->setStatusCode(Response::HTTP_NOT_FOUND, Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            if (!empty($this->repository)) {
                $returnDestroy = $this->repository->destroy($id);
                return response()->json([$returnDestroy])
                    ->setStatusCode(Response::HTTP_NO_CONTENT, Response::$statusTexts[Response::HTTP_NO_CONTENT]);
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())
                ->setStatusCode(Response::HTTP_NOT_FOUND, Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }
    }

}
