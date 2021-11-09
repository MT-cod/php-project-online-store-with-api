<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Goods;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class GoodsApiController extends Controller
{
    public function index(): JsonResponse
    {
        $data = Goods::goodsList();
        return Response::json([
            'success' => 'Список товаров с дополнительными характеристиками успешно получен.',
            'data' => $data
        ], 200);
    }
}
