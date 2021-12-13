<?php

namespace App\Http\RequestsProcessing\Api;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait ApiReqAuthProcessing
{
    /**
     * Обработка запроса на регистрацию пользователя через api
     *
     * @return array
     */
    public function reqProcessingForRegister(): array
    {
        $req = request();
        $input['name'] = $req->input('name');
        $input['email'] = $req->input('email');
        $input['phone'] = $req->input('phone');
        $input['password'] = bcrypt($req->input('password'));
        $user = User::create($input);
        $token = $user->createToken('Main token')->plainTextToken;
        return ['success' => 'Пользователь успешно зарегистрирован. Токен выдан.', 'data' => $token, 'status' => 201];
    }

    /**
     * Обработка запроса на вход в систему и получение токена пользователя
     *
     * @return array
     */
    public function reqProcessingForLogin(): array
    {
        $req = request();
        $user = User::where('email', $req->input('email'))->first();
        if (!$user || !Hash::check($req->input('password'), $user->password)) {
            return ['errors' => 'Не удалось авторизовать пользователя.', 'status' => 401];
        }
        $token = $user->createToken('Main token')->plainTextToken;
        return ['success' => 'Успешная авторизация. Токен выдан.', 'data' => $token, 'status' => 200];
    }

    /**
     * Обработка запроса на данные пользователя
     *
     * @return array
     */
    public function reqProcessingForUser(): array
    {
        try {
            $data = request()->user()->toArray();
        } catch (\Throwable $e) {
            return ['errors' => 'Не удалось получить данные пользователя.', 'status' => 400];
        }
        return [
            'success' => 'Данные пользователя ' . $data['name'] . ' успешно получены.',
            'data' => $data,
            'status' => 200
        ];
    }

    /**
     * Logout с удалением токена авторизованного пользователя
     *
     * @return array
     */
    public function reqProcessingForLogout(): array
    {
        try {
            request()->user()->tokens()->delete();
        } catch (\Throwable $e) {
            return ['errors' => 'Не удалось удалить токены пользователя.', 'status' => 400];
        }
        return ['success' => 'Успешный выход из системы.', 'status' => 200];
    }
}
