<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class ApiOrdersUpdateValidator extends \App\Http\Validators\Validator
{
    /**
    * Валидация запроса
    *
    * @param Request $request
    * @return MessageBag|array
     */
    public function validate(Request $request): array|MessageBag
    {
        $validator = Validator::make($request->all(), ['completed' => ['nullable', 'boolean']]);

        return ($validator->fails()) ? $validator->errors() : [];
    }
}
