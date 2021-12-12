<?php

namespace App\Http\Validators\Api;

use App\Models\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiBasketsStoreValidator extends \App\Http\Validators\Validator
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
            'basket' => [
                'required',
                function ($attr, $value, $fail): void {
                    if (!is_array($value)) {
                        $fail('Передана некорректная структура данных.');
                    } else {
                        foreach ($value as $id => $quantity) {
                            if (!Goods::find($id)) {
                                $fail('Указан некорректный идентификатор товара.');
                            }
                            if (!is_numeric($quantity) || $quantity <= 0) {
                                $fail('Указано некорректное количество товара.');
                            }
                        }
                    }
                }
            ]
        ]);
    }
}
