<?php

namespace App\Http\Validators\Api;

use App\Models\AdditionalChar;
use App\Models\Category;
use App\Models\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiGoodsUpdateValidator extends \App\Http\Validators\Validator
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
            'name' => [
                function ($attribute, $value, $fail): void {
                    if (Goods::where($attribute, $value)->first()) {
                        $fail("Товар с именем $value уже существует.");
                    }
                }],
            'slug' => [
                function ($attribute, $value, $fail): void {
                    if (Goods::where($attribute, $value)->first()) {
                        $fail("Товар со slug $value уже существует.");
                    }
                }],
            'price' => ['regex:/^\d*\.?\d{0,2}$/'],
            'category_id' => [
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
    }
}
