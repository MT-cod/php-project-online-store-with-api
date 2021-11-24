<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class ApiBasketsStoreValidator extends \App\Http\Validators\Validator
{
    /**
    * Валидация запроса
    *
    * @param Request $request
    * @return MessageBag|array
     */
    public function validate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['integer', 'exists:goods'],
            'quantity' => ['integer']
        ]);
        return ($validator->fails()) ? $validator->errors() : [];
    }
}
