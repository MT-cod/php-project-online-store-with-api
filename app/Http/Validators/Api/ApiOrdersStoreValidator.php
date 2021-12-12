<?php

namespace App\Http\Validators\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiOrdersStoreValidator extends \App\Http\Validators\Validator
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
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['required', 'max:20'],
            'address' => ['nullable', 'string', 'max:1000'],
            'comment' => ['nullable', 'string', 'max:1000']
        ]);
    }
}
