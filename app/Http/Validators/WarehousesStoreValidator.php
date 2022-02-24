<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WarehousesStoreValidator extends \App\Http\Validators\Validator
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
            'name' => ['required', 'unique:warehouses', 'max:100'],
            'address' => ['nullable', 'string', 'max:1000'],
            'priority' => ['integer', 'between:1,100', 'unique:warehouses']
            ]);
    }
}
