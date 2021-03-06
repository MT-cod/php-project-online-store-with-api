<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdditionalCharsStoreValidator extends \App\Http\Validators\Validator
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
            'name' => ['required', 'unique:additional_chars', 'max:100'],
            'value' => ['nullable', 'max:200']
            ]);
    }
}
