<?php

namespace App\Http\Validators;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiCategoriesUpdateValidator extends \App\Http\Validators\Validator
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
            'name' => [
                function ($attribute, $value, $fail) use ($request): void {
                    $cat = Category::whereId($request->id)->first();
                    if ($cat) {
                        if ((Category::where($attribute, $value)->first() !== null) && ($value !== $cat->name)) {
                            $fail("Категория с именем $value уже существует.");
                        }
                    } else {
                        $fail("Не удалось найти категорию с id:$request->id");
                    }
                }],
            'parent_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) use ($request): void {
                    $cat = Category::whereId($request->id)->first();
                    if ($cat) {
                        if (!$value) {
                            if ($cat->goods()->count()) {
                                $fail('У категории 1-го уровня не может быть товаров!');
                            }
                        } else {
                            $parent = Category::whereId($value)->first();
                            if ($parent) {
                                if ($parent->id === $cat->id) {
                                    $fail('Категория не может иметь себя же в родителях!');
                                }
                                if ($parent->level == 3) {
                                    $fail('Категория не может стать категорией 4-го уровня! Максимальный уровень 3');
                                }
                                if ($parent->level < 3) {
                                    if ($cat->childrens()->count()) {
                                        if ($parent->level == 2) {
                                            $fail('Категория не может стать категорией 3-го уровня, т.к. имеет дочернюю категорию!');
                                        } else {
                                            foreach ($cat->childrens()->get() as $child) {
                                                if ($child->childrens()->count()) {
                                                    $fail('Категория не может стать категорией 2-го уровня, т.к. имеет дочернюю категорию с подкатегорией!');
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                $fail("Не удалось найти категорию-родителя с id:$request->id");
                            }
                        }
                    } else {
                        $fail("Не удалось найти категорию с id:$request->id");
                    }
                }]
        ]);
    }
}
