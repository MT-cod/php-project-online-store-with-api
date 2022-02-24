<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WarehousesUpdateValidator extends \App\Http\Validators\Validator
{
    /**
     * Валидация запроса
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validate(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all() + ['id' => $request->warehouse], [
            'id' => 'exists:warehouses',
            'name' => ['max:100', Rule::unique('warehouses')->ignore($request->warehouse)],
            'address' => ['nullable', 'string', 'max:1000'],
            'priority' => ['integer', 'between:1,100', Rule::unique('warehouses')->ignore($request->warehouse)]
        ]);
    }
}
