<?php

namespace App\Http\Validators;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class ApiAuthRegisterValidator
{
    public Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
    * Регистрация пользователя через api
    *
    * @param Request $request
    * @return MessageBag|array
     */
    public function validate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8']
        ]);
        return ($validator->fails()) ? $validator->errors() : [];
    }

    /**
     * Регистрация пользователя через api
     *
     * @return JsonResponse|array
     */
    public function errors()
    {
        return $this->validate($this->request);
    }
}
