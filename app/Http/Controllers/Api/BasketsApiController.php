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
            return Response::json(['error' => $request->errors()], 400);
        }
        $data = self::reqProcessingForStore();
        return Response::json(
            ['success' => 'Корзина успешно сохранена.', 'data' => $data],
            200
        );
    }

    /**
     * Удаляем позицию из корзины.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $data = self::reqProcessingForDestroy($id);
        if (!$data) {
            return Response::json(['error' => 'Неверный идентификатор товара для удаления.'], 400);
        }
        return Response::json(['success' => 'Позиция успешно удалена.', 'data' => $data], 200);
    }

    /**
     * Полная очистка корзины.
     *
     * @return JsonResponse
     */
    public function purge(): JsonResponse
    {
        $result = request()->user()->goodsInBasket()->detach();
        if (!$result) {
            return Response::json(['error' => 'Не удалось очистить корзину.'], 500);
        }
        return Response::json(['success' => 'Корзина полностью очищена.'], 200);
    }
}
