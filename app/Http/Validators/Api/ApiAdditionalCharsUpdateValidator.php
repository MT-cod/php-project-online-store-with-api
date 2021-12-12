<?php

namespace App\Http\Validators\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ApiAdditionalCharsUpdateValidator extends \App\Http\Validators\Validator
{
    /**
     * Валидация запроса
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validate(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all() + ['id' => $request->additionalChar], [
            'id' => 'exists:additional_chars',
            'name' => ['max:100', Rule::unique('additional_chars')->ignore($request->additionalChar)],
            'value' => ['nullable', 'max:200']
        ]);
    }
}
