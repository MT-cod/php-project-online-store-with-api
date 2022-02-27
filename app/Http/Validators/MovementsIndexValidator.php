<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MovementsIndexValidator extends \App\Http\Validators\Validator
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
            'filter.movement_type' => ['integer', 'between:1,4'],
            'filter.warehouse_id' => ['nullable', 'integer', 'exists:warehouses,id'],
            'filter.goods_id' => ['nullable', 'integer', 'exists:goods,id'],
            'sort.*' => ['nullable', Rule::in(['asc', 'desc'])],
            'perpage' => ['nullable', 'integer'],
            'filter.*' => function ($attr, $val, $fail): void {
                $permitAttrs = [
                    'id',
                    'create_min',
                    'create_max',
                    'movement_type',
                    'warehouse_id',
                    'goods_id'
                ];
                if (!in_array(explode('.', $attr)[1], $permitAttrs)) {
                    $fail("Фильтрация данных по полю $attr некорректна.");
                }
            }
        ]);
    }
}
