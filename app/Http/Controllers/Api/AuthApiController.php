<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\RequestsProcessing\ApiReqAuthProcessing;
use App\Http\RequestsProcessing\ApiResponses;
use App\Http\Validators\ApiAuthLoginValidator;
use App\Http\Validators\ApiAuthRegisterValidator;
use Illuminate\Http\JsonResponse;

class AuthApiController extends Controller
{
    use ApiReqAuthProcessing;
    use ApiResponses;

    /**
     * Регистрация пользователя
     *
     * @param ApiAuthRegisterValidator $request
     * @return JsonResponse
     */
    public function register(ApiAuthRegisterValidator $request): JsonResponse
    {
        return ($this->sendErrRespOnInvalidValidate($request))
            ?? $this->sendResultRespAfterProcessing($this->reqProcessingForRegister());
    }

    /**
     * Вход в систему, получение токена пользователя
     *
     * @param ApiAuthLoginValidator $request
     * @return JsonResponse
     */
    public function login(ApiAuthLoginValidator $request): JsonResponse
    {
        return ($this->sendErrRespOnInvalidValidate($request))
            ?? $this->sendResultRespAfterProcessing($this->reqProcessingForLogin());
    }

    /**
     * Данные пользователя
     *
     * @return JsonResponse
     */
    public function user(): JsonResponse
    {
        return $this->sendResultRespAfterProcessing($this->reqProcessingForUser());
    }

    /**
     * Выход с удалением токена пользователя
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        return $this->sendResultRespAfterProcessing($this->reqProcessingForLogout());
    }
}
