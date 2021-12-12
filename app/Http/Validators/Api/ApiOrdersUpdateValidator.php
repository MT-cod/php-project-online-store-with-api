<?php

namespace App\Http\Validators\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiOrdersUpdateValidator extends \App\Http\Validators\Validator
{
    /**
    * Валидация запроса
    *
    * @param Request $request
    * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validate(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), ['completed' => ['nullable', 'boolean']]);
    }
}
