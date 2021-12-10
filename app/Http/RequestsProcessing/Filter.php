<?php

namespace App\Http\RequestsProcessing;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

trait Filter
{
    private function filtering(array|null $reqFilters, Builder $data): Builder
    {
        if ($reqFilters) {
            $filters = [
                'create_min' => fn($val, $data) => $data->whereDate('created_at', '>=', $val),
                'create_max' => fn($val, $data) => $data->whereDate('created_at', '<=', $val),
                'update_min' => fn($val, $data) => $data->whereDate('updated_at', '>=', $val),
                'update_max' => fn($val, $data) => $data->whereDate('updated_at', '<=', $val),
                'name' => fn($val, $data) => $data->where('name', 'like', '%' . $val . '%'),
                'email' => fn($val, $data) => $data->where('email', 'like', '%' . $val . '%'),
                'phone' => fn($val, $data) => $data->where('phone', 'like', '%' . $val . '%'),
                'address' => fn($val, $data) => $data->where('address', 'like', '%' . $val . '%'),
                'value' => fn($val, $data) => $data->where('value', 'like', '%' . $val . '%'),
                'completed' => fn($val, $data) => $data->where('completed', $val),
                'price' => fn($val, $data) => $data->where('price', $val),
                'level' => fn($val, $data) => $data->where('level', $val),
                'parent_id' => fn($val, $data) => $data->where('parent_id', $val),
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
                    $data->whereIn('category_id', $catsWithChildsList);
                },
                'additChar_ids' => function ($val, $data): void {
                    $chars = explode(',', $val);
                    foreach ($chars as $char) {
                        $data->whereHas('additionalChars', function (Builder $query) use ($char) {
                            $query->where('additional_char_id', $char);
                        });
                    }
                }
            ];

            array_walk($reqFilters, static fn($val, $filtName) => $filters[$filtName]($val, $data));
        }

        return $data;
    }
}
