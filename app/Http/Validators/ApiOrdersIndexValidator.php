<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;

class ApiOrdersIndexValidator extends \App\Http\Validators\Validator
{
    /**
    * Валидация запроса
    *
    * @param Request $request
    * @return MessageBag|array
     */
    public function validate(Request $request): array|MessageBag
    {
        $validator = Validator::make($request->all(), [
            'filter.*' => ['exists:App\Models\Order'],
            'filter.create_min' => ['nullable', 'date'],
            'filter.create_max' => ['nullable', 'date'],
            'filter.update_min' => ['nullable', 'date'],
            'filter.update_max' => ['nullable', 'date'],
            'filter.name' => ['nullable', 'string', 'max:255'],
            'filter.email' => ['nullable', 'email', 'max:255'],
            'filter.phone' => ['nullable', 'string', 'max:255'],
            'filter.address' => ['nullable', 'string', 'max:1000'],
            'filter.completed' => ['nullable', 'boolean'],
            'sort.*' => ['nullable', 'string', 'max:255', Rule::in(['asc', 'desc'])],
            'perpage' => ['nullable', 'integer']
        ]);
        return ($validator->fails()) ? $validator->errors() : [];
    }
}
