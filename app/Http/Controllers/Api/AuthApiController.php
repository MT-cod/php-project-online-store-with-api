<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Validators\ApiAuthLoginValidator;
use App\Http\Validators\ApiAuthRegisterValidator;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AuthApiController extends Controller
{
    /**
     * Регистрация пользователя через api
     *
     * @param ApiAuthRegisterValidator $request
     * @return JsonResponse
     */
    public function register(ApiAuthRegisterValidator $request)
    {
        if ($request->errors()) {
            return Response::json(['error' => $request->errors()], 401);
        }

        $input = $request->request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $token = $user->createToken('Main token')->plainTextToken;
        return Response::json(['success' => 'Пользователь успешно зарегистрирован. Токен выдан.', 'token' => $token], 200);
    }

    /**
     * Вход в систему, получение токена пользователя
     *
     * @param ApiAuthLoginValidator $request
     * @return JsonResponse
     */
    public function login(ApiAuthLoginValidator $request)
    {
        if ($request->errors()) {
            return Response::json(['error' => $request->errors()], 401);
        }

        $request = $request->request;
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return Response::json(['error' => 'Не удалось авторизовать пользователя.'], 401);
        }
        $token = $user->createToken('Main token')->plainTextToken;
        return Response::json(['success' => 'Успешная авторизация. Токен выдан.', 'token' => $token], 200);
    }

    /**
     * Отдаем данные пользователя по запросу
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function user(Request $request)
    {
        $data = $request->user()->toArray();
        return Response::json(['success' => 'Данные пользователя ' . $data['name'] . '.', 'data' => $data], 200);
    }

    /**
     * Выход с удалением токена пользователя
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return Response::json(['success' => 'Успешный выход из системы.'], 200);
    }
}
