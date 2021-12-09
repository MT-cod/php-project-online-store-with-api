<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class ApiAuthRegisterValidator extends \App\Http\Validators\Validator
{
    /**
    * Валидация запроса
    *
    * @param Request $request
    * @return array|MessageBag
     */
    public function validate(Request $request): array|MessageBag
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['max:255'],
            'password' => ['required', 'string', 'min:8']
        ]);
        return ($validator->fails()) ? $validator->errors() : [];
    }
}
