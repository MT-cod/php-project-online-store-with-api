<?php

namespace App\Http\RequestsProcessing;

use App\Http\Validators\ApiAuthRegisterValidator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait ApiReqAuthProcessing
{
    /*public function reqProcessingForTree(): array
    {
        $data = Category::categoriesTree();
        if ($data) {
            return ['success' => 'Дерево категорий успешно получено.', 'data' => $data, 'status' => 200];
        }
        return ['errors' => 'Не удалось получить дерево категорий', 'status' => 400];
    }*/

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
        $data = request()->user()->toArray();
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
        request()->user()->tokens()->delete();
        return ['success' => 'Успешный выход из системы.', 'status' => 200];
    }
}
