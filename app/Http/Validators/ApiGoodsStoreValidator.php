<?php

namespace App\Http\Validators;

use App\Models\AdditionalChar;
use App\Models\Category;
use App\Models\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class ApiGoodsStoreValidator extends \App\Http\Validators\Validator
{
    /**
    * Валидация запроса
    *
    * @param Request $request
    * @return MessageBag|array
     */
    public function validate(Request $request): array|MessageBag
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                function ($attribute, $value, $fail): void {
                    if (Goods::where($attribute, $value)->first()) {
                        $fail("Товар с именем $value уже существует.");
                    }
                }],
            'slug' => [
                'required',
                function ($attribute, $value, $fail): void {
                    if (Goods::where($attribute, $value)->first()) {
                        $fail("Товар со slug $value уже существует.");
                    }
                }],
            'category_id' => [
                'required',
                function ($attribute, $value, $fail): void {
                    if (!Category::find($value)) {
                        $fail("Категория с id:$value не найдена.");
                    } else {
                        if (Category::find($value)->level === 1) {
                            $fail('Категории 1-го уровня не могут принадлежать товары.');
                        }
                    }
                }],
            'additChars' => [function ($attribute, $value, $fail): void {
                foreach ($value as $id) {
                    if (!AdditionalChar::whereId($id)->first()) {
                        $fail("Указана несуществующая доп. характеристика с id:$id для нового товара.");
                    }
                }
            }]
        ]);
        return ($validator->fails()) ? $validator->errors() : [];
    }
}
