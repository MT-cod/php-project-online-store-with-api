<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Validators\ApiBasketsStoreValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use App\Http\RequestsProcessing\ApiReqBasketsProcessing;

class BasketsApiController extends Controller
{
    use ApiReqBasketsProcessing;

    public function ownBasket(): JsonResponse
    {
        $result = $this->reqProcessingForOwnBasket();
        return Response::json(['success' => $result['success'], 'data' => $result['data']], $result['status']);
    }

    /**
     * Создание(обновление данных) корзины пользователя.
     *
     * @param ApiBasketsStoreValidator $request
     * @return JsonResponse
     */
    public function store(ApiBasketsStoreValidator $request): JsonResponse
    {
        if ($request->errors()) {
            return Response::json(['error' => $request->errors()], 400);
        }
        $result = $this->reqProcessingForStore();
        return Response::json(['success' => $result['success'], 'data' => $result['data']], $result['status']);
    }

    /**
     * Удаление позиции из корзины пользователя.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->reqProcessingForDestroy($id);
        if (isset($result['errors'])) {
            return Response::json(['error' => $result['errors']], $result['status']);
        }
        return Response::json(['success' => $result['success'], 'data' => $result['data']], $result['status']);
    }

    /**
     * Полная очистка корзины пользователя.
     *
     * @return JsonResponse
     */
    public function purge(): JsonResponse
    {
        $result = $this->reqProcessingForPurge();
        if (isset($result['errors'])) {
            return Response::json(['error' => $result['errors']], $result['status']);
        }
        return Response::json(['success' => $result['success']], $result['status']);
    }
}
