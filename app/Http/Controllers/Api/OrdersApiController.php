<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\RequestsProcessing\ApiReqOrdersProcessing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class OrdersApiController extends Controller
{
    use ApiReqOrdersProcessing;

    public function index(): JsonResponse
    {
        $data = self::reqProcessingForIndex();
        if (isset($data['errors'])) {
            return Response::json(['error' => $data['errors']], 400);
        }
        return Response::json([
            'success' => 'Список заказов успешно получен.',
            'data' => $data
        ], 200);
    }

    /**
     * Получение списка заказов запрашивающего авторизированного пользователя
     *
     * @return JsonResponse
     */
    public function ownOrders(): JsonResponse
    {
        $data = request()->user()->orders()->get();
        return Response::json([
            'success' => 'Список заказов пользователя успешно получен.',
            'data' => $data
        ], 200);
    }
}
