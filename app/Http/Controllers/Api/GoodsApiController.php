<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\RequestsProcessing\ApiReqGoodsProcessing;
use App\Models\Goods;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class GoodsApiController extends Controller
{
    use ApiReqGoodsProcessing;

    public function index(): JsonResponse
    {
        $data = self::reqProcessingForIndex();
        if (isset($data['errors'])) {
            return Response::json(['error' => $data['errors']], 400);
        }
        return Response::json([
            'success' => 'Список товаров с дополнительными характеристиками успешно получен.',
            'data' => $data
        ], 200);
    }

    /**
     * Получаем товар по запрошенному slug
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function slug(string $slug): JsonResponse
    {
        $data = Goods::where('slug', $slug)->first();
        if ($data) {
            return Response::json(['success' => 'Товар успешно получен.', 'data' => $data], 200);
        }
        return Response::json(['error' => 'Не удалось получить товар по запрошенному slug'], 400);
    }
}
