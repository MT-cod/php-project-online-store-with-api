<?php

namespace App\Http\Validators\Api;

use App\Models\AdditionalChar;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        return Validator::make($request->all() + ['id' => $request->id], [
            'id' => 'exists:goods',
            'file' => ['image', 'mimetypes:image/jpeg,image/png'],
            'name' => ['max:100', Rule::unique('goods')->ignore($request->id)],
            'slug' => ['max:100', Rule::unique('goods')->ignore($request->id)],
            'price' => ['regex:/^\d*\.?\d{0,2}$/'],
            'category_id' => [
                'bail',
                'exists:categories,id',
                function ($attribute, $value, $fail): void {
                    if (Category::find($value)->level === 1) {
                        $fail('Категории 1-го уровня не могут принадлежать товары.');
                    }
                }],
            'additChars' => [function ($attribute, $value, $fail): void {
                foreach ($value as $id) {
                    if (!AdditionalChar::find($id)) {
                        $fail("Указана несуществующая доп. характеристика с id:$id для нового товара.");
                    }
                }
            }]
        ]);
    }
}
