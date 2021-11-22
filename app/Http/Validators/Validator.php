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
     * @return array
     */
    public function errors()
    {
        return $this->validate($this->request);
    }

    abstract public function validate(Request $request);
}
