<?php

namespace App\Http\Validators;

use App\Models\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BasketsStoreValidator extends \App\Http\Validators\Validator
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
            'id' => ['required', 'exists:goods'],
            'quantity' => ['required',
                function ($attr, $value, $fail): void {
                    if (!is_numeric($value) || $value <= 0) {
                        $fail('Указано некорректное количество товара.');
                    }
                }
            ]
        ]);
    }
}
