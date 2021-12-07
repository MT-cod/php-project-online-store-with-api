<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\RequestsProcessing\ApiResponses;
use App\Http\Validators\ApiBasketsStoreValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use App\Http\RequestsProcessing\ApiReqBasketsProcessing;

class BasketsApiController extends Controller
{
    use ApiReqBasketsProcessing;
    use ApiResponses;

    public function ownBasket(): JsonResponse
    {
        return $this->sendResultRespAfterProcessing($this->reqProcessingForOwnBasket());
    }

    /**
     * Создание(обновление данных) корзины пользователя.
     *
     * @param ApiBasketsStoreValidator $request
     * @return JsonResponse
     */
    public function store(ApiBasketsStoreValidator $request): JsonResponse
    {
        return $this->sendErrRespOnInvalidValidate($request)
            ?? $this->sendResultRespAfterProcessing($this->reqProcessingForStore());
    }

    /**
     * Удаление позиции из корзины пользователя.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->sendResultRespAfterProcessing($this->reqProcessingForDestroy($id));
    }

    /**
     * Полная очистка корзины пользователя.
     *
     * @return JsonResponse
     */
    public function purge(): JsonResponse
    {
        return $this->sendResultRespAfterProcessing($this->reqProcessingForPurge());
    }
}
