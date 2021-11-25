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

    public function index(): JsonResponse
    {
        $data = request()->user()->basketForApi();
        return Response::json([
            'success' => 'Корзина пользователя успешно получена.',
            'data' => $data
        ], 200);
    }

    /**
     * Добавление новой позиции товара с кол-вом в корзину.
     *
     * @param ApiBasketsStoreValidator $request
     * @return JsonResponse
     */
    public function store(ApiBasketsStoreValidator $request): JsonResponse
    {
        if ($request->errors()) {
            return Response::json(['error' => $request->errors()], 401);
        }
        $data = self::reqProcessingForStore(request());
        return Response::json(
            ['success' => 'Корзина успешно сохранена.', 'data' => $data],
            200
        );
    }
}
