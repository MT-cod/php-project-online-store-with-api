<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GoodsIndexValidator extends \App\Http\Validators\Validator
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
            'filter.create_min' => ['nullable', 'date'],
            'filter.create_max' => ['nullable', 'date'],
            'filter.update_min' => ['nullable', 'date'],
            'filter.update_max' => ['nullable', 'date'],
            'filter.name' => ['nullable', 'string', 'max:255'],
            'filter.price' => ['regex:/^\d*\.?\d{0,2}$/'],
            'filter.price_min' => ['nullable', 'regex:/^\d*\.?\d{0,2}$/'],
            'filter.price_max' => ['nullable', 'regex:/^\d*\.?\d{0,2}$/'],
            'filter.category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'filter.additChars' => ['nullable', 'array', 'exists:additional_chars,id'],
            'sort.*' => ['nullable', Rule::in(['asc', 'desc'])],
            'perpage' => ['nullable', 'integer'],
            'filter.*' => function ($attr, $val, $fail): void {
                $permitAttrs = [
                    'create_min',
                    'create_max',
                    'update_min',
                    'update_max',
                    'name',
                    'price',
                    'price_min',
                    'price_max',
                    'category_id',
                    'additChars'
                ];
                if (!in_array(explode('.', $attr)[1], $permitAttrs)) {
                    $fail("Фильтрация данных по полю $attr некорректна");
                }
            }
        ]);
    }
}
