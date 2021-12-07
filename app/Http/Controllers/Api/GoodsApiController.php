<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\RequestsProcessing\ApiReqGoodsProcessing;
use App\Http\RequestsProcessing\ApiResponses;
use App\Http\Validators\ApiGoodsStoreValidator;
use App\Http\Validators\ApiGoodsUpdateValidator;
use Illuminate\Http\JsonResponse;

class GoodsApiController extends Controller
{
    use ApiReqGoodsProcessing;
    use ApiResponses;

    public function index(): JsonResponse
    {
        return $this->sendResultRespAfterProcessing($this->reqProcessingForIndex());
    }

    /**
     * Получаем товар по запрошенному slug
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function slug(string $slug): JsonResponse
    {
        return $this->sendResultRespAfterProcessing($this->reqProcessingForSlug($slug));
    }

    /**
     * Данные товара.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return $this->sendResultRespAfterProcessing($this->reqProcessingForShow($id));
    }

    /**
     * Создание товара.
     *
     * @param ApiGoodsStoreValidator $request
     * @return JsonResponse
     */
    public function store(ApiGoodsStoreValidator $request): JsonResponse
    {
        return ($this->sendErrRespOnInvalidValidate($request))
            ?? $this->sendResultRespAfterProcessing($this->reqProcessingForStore());
    }

    /**
     * Изменение товара.
     *
     * @param ApiGoodsUpdateValidator $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ApiGoodsUpdateValidator $request, int $id): JsonResponse
    {
        return ($this->sendErrRespOnInvalidValidate($request))
            ?? $this->sendResultRespAfterProcessing($this->reqProcessingForUpdate($id));
    }

    /**
     * Удаление товара.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->sendResultRespAfterProcessing($this->reqProcessingForDestroy($id));
    }
}
