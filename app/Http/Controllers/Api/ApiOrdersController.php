<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\RequestsProcessing\Api\ApiReqOrdersProcessing;
use App\Http\RequestsProcessing\Api\ApiResponses;
use App\Http\Validators\Api\ApiOrdersStoreValidator;
use App\Http\Validators\Api\ApiOrdersUpdateValidator;
use Illuminate\Http\JsonResponse;

class ApiOrdersController extends Controller
{
    use ApiReqOrdersProcessing;
    use ApiResponses;

    public function index(): JsonResponse
    {
        return $this->sendResultRespAfterProcessing($this->reqProcessingForIndex());
    }

    /**
     * Получение списка заказов авторизированного пользователя
     *
     * @return JsonResponse
     */
    public function ownOrders(): JsonResponse
    {
        return $this->sendResultRespAfterProcessing($this->reqProcessingForOwnOrders());
    }

    /**
     * Данные заказа с товарами.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return $this->sendResultRespAfterProcessing($this->reqProcessingForShow($id));
    }

    /**
     * Создаём новый заказ пользователя.
     *
     * @param \App\Http\Validators\Api\ApiOrdersStoreValidator $request
     * @return JsonResponse
     */
    public function store(ApiOrdersStoreValidator $request): JsonResponse
    {
        return $this->sendErrRespOnInvalidValidate($request)
            ?? $this->sendResultRespAfterProcessing($this->reqProcessingForStore());
    }

    /**
     * Изменяем заказ.
     *
     * @param ApiOrdersUpdateValidator $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ApiOrdersUpdateValidator $request, int $id): JsonResponse
    {
        return $this->sendErrRespOnInvalidValidate($request)
            ?? $this->sendResultRespAfterProcessing($this->reqProcessingForUpdate($id));
    }
}
