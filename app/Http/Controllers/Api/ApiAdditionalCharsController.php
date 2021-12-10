<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\RequestsProcessing\ApiReqAdditionalCharsProcessing;
use App\Http\RequestsProcessing\ApiResponses;
use Illuminate\Http\JsonResponse;

class ApiAdditionalCharsController extends Controller
{
    use ApiReqAdditionalCharsProcessing;
    use ApiResponses;

    public function index(): JsonResponse
    {
        return $this->sendResultRespAfterProcessing($this->reqProcessingForIndex());
    }

    /**
     * Данные категории.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return $this->sendResultRespAfterProcessing($this->reqProcessingForShow($id));
    }

    /**
     * Создание категории.
     *
     * @param ApiCategoriesStoreValidator $request
     * @return JsonResponse
     */
    public function store(ApiCategoriesStoreValidator $request): JsonResponse
    {
        return ($this->sendErrRespOnInvalidValidate($request))
            ?? $this->sendResultRespAfterProcessing($this->reqProcessingForStore());
    }

    /**
     * Изменение категории.
     *
     * @param ApiCategoriesUpdateValidator $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ApiCategoriesUpdateValidator $request, int $id): JsonResponse
    {
        return ($this->sendErrRespOnInvalidValidate($request))
            ?? $this->sendResultRespAfterProcessing($this->reqProcessingForUpdate($id));
    }

    /**
     * Удаление категории.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->sendResultRespAfterProcessing($this->reqProcessingForDestroy($id));
    }
}
