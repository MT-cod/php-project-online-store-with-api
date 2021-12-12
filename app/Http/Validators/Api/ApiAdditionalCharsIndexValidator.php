<?php

namespace App\Http\Validators\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ApiAdditionalCharsIndexValidator extends \App\Http\Validators\Validator
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
            'filter.name' => ['nullable', 'string', 'max:100'],
            'filter.value' => ['nullable', 'max:200'],
            'sort.*' => ['nullable', Rule::in(['asc', 'desc'])],
            'perpage' => ['nullable', 'integer'],
            'filter.*' => function ($attr, $val, $fail): void {
                $permitAttrs = [
                    'create_min',
                    'create_max',
                    'update_min',
                    'update_max',
                    'name',
                    'value'
                ];
                if (!in_array(explode('.', $attr)[1], $permitAttrs)) {
                    $fail("Фильтрация данных по полю $attr некорректна");
                }
            }
        ]);
    }
}
