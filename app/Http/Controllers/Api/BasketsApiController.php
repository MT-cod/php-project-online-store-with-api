<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class BasketsApiController extends Controller
{
    public function index(): JsonResponse
    {
        $data = request()->user()->basketForApi();
        return Response::json([
            'success' => 'Корзина пользователя успешно получена из БД.',
            'data' => $data
        ], 200);
    }
}
