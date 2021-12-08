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
        return $this->validate($this->request);
    }

    abstract public function validate(Request $request): array|MessageBag;
}
