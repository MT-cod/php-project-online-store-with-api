<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\RequestsProcessing\ApiReqOrdersProcessing;
use App\Http\Validators\ApiOrdersStoreValidator;
use App\Http\Validators\ApiOrdersUpdateValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class OrdersApiController extends Controller
{
    use ApiReqOrdersProcessing;

    public function index(): JsonResponse
    {
        $result = $this->reqProcessingForIndex();
        if (isset($result['errors'])) {
            return Response::json(['error' => $result['errors']], $result['status']);
        }
        return Response::json(['success' => $result['success'], 'data' => $result['data']], $result['status']);
    }

    /**
     * Получение списка заказов авторизированного пользователя
     *
     * @return JsonResponse
     */
    public function ownOrders(): JsonResponse
    {
        $result = $this->reqProcessingForOwnOrders();
        return Response::json(['success' => $result['success'], 'data' => $result['data']], $result['status']);
    }

    /**
     * Создаём новый заказ пользователя.
     *
     * @param ApiOrdersStoreValidator $request
     * @return JsonResponse
     */
    public function store(ApiOrdersStoreValidator $request): JsonResponse
    {
        if ($request->errors()) {
            return Response::json(['error' => $request->errors()], 400);
        }
        $result = $this->reqProcessingForStore();
        if (isset($result['errors'])) {
            return Response::json(['error' => $result['errors']], $result['status']);
        }
        return Response::json(['success' => $result['success'], 'data' => $result['data']], $result['status']);
    }

    /**
     * Изменяем заказ.
     *
     * @param ApiOrdersUpdateValidator $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ApiOrdersUpdateValidator $request, int $id): JsonResponse
    {
        if ($request->errors()) {
            return Response::json(['error' => $request->errors()], 400);
        }
        $result = $this->reqProcessingForUpdate($id);
        if (isset($result['errors'])) {
            return Response::json(['error' => $result['errors']], $result['status']);
        }
        return Response::json(['success' => $result['success'], 'data' => $result['data']], $result['status']);
    }
}
