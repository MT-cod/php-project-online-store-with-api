<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    /**
     * Регистрация пользователя через api
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8']
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $token = $user->createToken('Main token')->plainTextToken;
        return response()->json(['token' => $token], 200);
    }

    /**
     * Вход в систему, получение токена пользователя
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8']
        ]);

        if ($validator->fails()) {
            return Response::json(['error' => $validator->errors()], 401);
        }

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
        if (Auth::check()) {
            Auth::user()->tokens()->delete();
        }
        return Response::json(['success' => 'Успешный выход из системы.'], 200);
    }
}
