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
    * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validate(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['max:255'],
            'password' => ['required', 'string', 'min:8']
        ]);
    }
}
