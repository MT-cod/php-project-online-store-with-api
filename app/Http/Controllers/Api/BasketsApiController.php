<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use App\Http\RequestsProcessing\ApiReqBasketsProcessing;

class BasketsApiController extends Controller
{
    use ApiReqBasketsProcessing;

    public function index(): JsonResponse
    {
        $data = request()->user()->basketForApi();
        return Response::json([
            'success' => 'Корзина пользователя успешно получена из БД.',
            'data' => $data
        ], 200);
    }

    /**
     * Добавление новой позиции товара с кол-вом в корзину.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = self::reqProcessingForStore($request);
        if (isset($data['errors'])) {
            return Response::json(['error' => $data['errors']], 400);
        }
        return Response::json(
            ['success' => 'Позиция успешно добавлена в корзину.', 'data' => $data],
            200
        );
    }
}
