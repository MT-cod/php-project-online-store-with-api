<?php

namespace App\Http\RequestsProcessing;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

trait Filter
{
    private function filtering(array|null $reqFilters, Builder $data): Builder
    {
        if ($reqFilters) {
            $filters = [
                'create_min' => fn($val, $data) => $data->whereDate('created_at', '>=', $val ?? Carbon::create(1)),
                'create_max' => fn($val, $data) => $data->whereDate('created_at', '<=', $val ?? Carbon::now()),
                'update_min' => fn($val, $data) => $data->whereDate('updated_at', '>=', $val ?? Carbon::create(1)),
                'update_max' => fn($val, $data) => $data->whereDate('updated_at', '<=', $val ?? Carbon::now()),
                'name' => fn($val, $data) => $data->where('name', 'like', '%' . $val . '%'),
                'slug' => fn($val, $data) => $data->where('slug', 'like', '%' . $val . '%'),
                'email' => fn($val, $data) => $data->where('email', 'like', '%' . $val . '%'),
                'phone' => fn($val, $data) => $data->where('phone', 'like', '%' . $val . '%'),
                'address' => fn($val, $data) => $data->where('address', 'like', '%' . $val . '%'),
                'value' => fn($val, $data) => $data->where('value', 'like', '%' . $val . '%'),
                'price' => fn($val, $data) => $data->where('price', $val),
                'price_min' => fn($val, $data) => $data->where('price', '>=', $val ?? 0),
                'price_max' => fn($val, $data) => $data->where('price', '<=', $val ?? 10e20),
                'level' => fn($val, $data) => $data->where('level', $val),
                'parent_id' => fn($val, $data) => $data->where('parent_id', $val),
                'category_id' => function ($val, $data): void {
                    $catsWithChildsList = [];
                    function catChildsToList($category, $catsWithChildsList): array
                    {
                        $catsWithChildsList[] = $category->id;
                        $catChilds = $category->childrens()->get();
                        if (count($catChilds)) {
                            foreach ($catChilds as $cat) {
                                $catsWithChildsList = catChildsToList($cat, $catsWithChildsList);
                            }
                        }
                        return $catsWithChildsList;
                    }

                    $category = Category::whereId($val)->first();
                    if ($category) {
                        $catsWithChildsList = catChildsToList($category, $catsWithChildsList);
                    }
                    if ($catsWithChildsList) {
                        $data->whereIn('category_id', $catsWithChildsList);
                    }
                },
                'category_ids' => function ($val, $data): void {
                    $catsWithChildsList = [];
                    function catChildsToList($category, $catsWithChildsList): array
                    {
                        $catsWithChildsList[] = $category->id;
                        $catChilds = $category->childrens()->get();
                        if (count($catChilds)) {
                            foreach ($catChilds as $cat) {
                                $catsWithChildsList = catChildsToList($cat, $catsWithChildsList);
                            }
                        }
                        return $catsWithChildsList;
                    }
                    foreach (explode(',', $val) as $catId) {
                        $category = Category::whereId($catId)->first();
                        if ($category) {
                            $catsWithChildsList = catChildsToList($category, $catsWithChildsList);
                        }
                    }
                    if ($catsWithChildsList) {
                        $data->whereIn('category_id', $catsWithChildsList);
                    }
                },
                'additChar_ids' => function ($val, $data): void {
                    $chars = explode(',', $val);
                    foreach ($chars as $char) {
                        $data->whereHas('additionalChars', function (Builder $query) use ($char) {
                            $query->where('additional_char_id', $char);
                        });
                    }
                },
                'additChars' => function ($val, $data): void {
                    foreach ($val as $char) {
                        $data->whereHas('additionalChars', function (Builder $query) use ($char) {
                            $query->where('additional_char_id', $char);
                        });
                    }
                },
                'completed' => function ($val, $data): void {
                    if ($val == 1) {
                        $data->where('completed', 1);
                    }
                    if ($val == 0 && !is_null($val)) {
                        $data->where('completed', 0);
                    }
                },
                'id' => function ($val, $data): void {
                    if ($val > 0 && is_int($val)) {
                        $data->where('id', $val);
                    }
                }
            ];

            array_walk($reqFilters, static fn($val, $filtName) => $filters[$filtName]($val, $data));
        }

        return $data;
    }
}
