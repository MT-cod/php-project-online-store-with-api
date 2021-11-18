<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Goods;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use function PHPUnit\Framework\isEmpty;

class GoodsApiController extends Controller
{
    /**
     * List
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = Goods::goodsList();
        return Response::json([
            'success' => 'Список товаров с дополнительными характеристиками успешно получен.',
            'data' => $data
        ], 200);
    }

    /**
     * Отдаем данные пользователя по запросу
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function slug($slug): JsonResponse
    {
        $data = Goods::where('slug', $slug)->first();
        if ($data) {
            return Response::json(['success' => 'Товар успешно получен.', 'data' => $data->toArray()], 200);
        }
        return Response::json(['error' => 'Неверный slug'], 400);
    }
}
