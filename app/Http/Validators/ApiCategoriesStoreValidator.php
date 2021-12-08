<?php

namespace App\Http\Validators;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class ApiCategoriesStoreValidator extends \App\Http\Validators\Validator
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
            'name' => [
                'required',
                function ($attribute, $value, $fail): void {
                    if (Category::where($attribute, $value)->first()) {
                        $fail("Категория с именем $value уже существует.");
                    }
                }],
            'parent_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail): void {
                    if ($value > 0) {
                        if (Category::where('id', $value)->first()->level == 3) {
                            $fail('Категория не может быть подкатегорией категории 3-го уровня!');
                        }
                    }
                }]
        ]);
        return ($validator->fails()) ? $validator->errors() : [];
    }
}
