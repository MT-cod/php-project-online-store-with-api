<?php

namespace App\Http\Validators;

use App\Models\Goods;
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
        return ($validator->fails()) ? $validator->errors() : [];
    }
}