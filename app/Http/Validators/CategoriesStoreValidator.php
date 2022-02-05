<?php

namespace App\Http\Validators;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesStoreValidator extends \App\Http\Validators\Validator
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
            'name' => ['required', 'unique:categories', 'max:100'],
            'parent_id' => [
                'bail',
                'nullable',
                'integer',
                function ($attribute, $value, $fail): void {
                    if ($value > 0  && Category::find($value)->level === 3) {
                        $fail('Категория не может быть подкатегорией категории 3-го уровня!');
                    }
                }]
        ]);
    }
}
