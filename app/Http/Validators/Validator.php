<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

abstract class Validator
{
    public Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Проверка на ошибки валидации
     *
     * @return array|MessageBag
     */
    public function errors(): array|MessageBag
    {
        $validated = $this->validate($this->request);
        return ($validated->stopOnFirstFailure()->fails()) ? $validated->errors() : [];
    }

    abstract public function validate(Request $request): \Illuminate\Contracts\Validation\Validator;
}
