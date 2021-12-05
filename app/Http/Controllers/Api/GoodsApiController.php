<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\RequestsProcessing\ApiReqGoodsProcessing;
use App\Http\Validators\ApiGoodsStoreValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class GoodsApiController extends Controller
{
    use ApiReqGoodsProcessing;

    public function index(): JsonResponse
    {
        $result = $this->reqProcessingForIndex();
        if (isset($result['errors'])) {
            return Response::json(['error' => $result['errors']], $result['status']);
        }
        return Response::json(['success' => $result['success'], 'data' => $result['data']], $result['status']);
    }

    /**
     * Получаем товар по запрошенному slug
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function slug(string $slug): JsonResponse
    {
        $result = $this->reqProcessingForSlug($slug);
        if (isset($result['errors'])) {
            return Response::json(['error' => $result['errors']], $result['status']);
        }
        return Response::json(['success' => $result['success'], 'data' => $result['data']], $result['status']);
    }

    /**
     * Создание товара.
     *
     * @param ApiGoodsStoreValidator $request
     * @return JsonResponse
     */
    public function store(ApiGoodsStoreValidator $request): JsonResponse
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
}
