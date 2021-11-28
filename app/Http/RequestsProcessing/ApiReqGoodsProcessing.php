<?php

namespace App\Http\RequestsProcessing;

use App\Models\Category;
use App\Models\Goods;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait ApiReqGoodsProcessing
{
    private Request $req;

    /**
     * Обработка запроса на список товаров с фильтрацией, сортировкой и разбитием на страницы + доп характеристики.
     *
     * @return array
     */
    public function reqProcessingForIndex(): array
    {
        $this->req = request();
        $goods = Goods::select();
    //отфильтруем
        if ($this->req->input('filter')) {
            if ($this->validateFilter()) {
                return ['errors' => $this->validateFilter()];
            }
            $filteredGoods = $this->filtering($goods);
        } else {
            $filteredGoods = $goods;
        }
    //отсортируем
        if ($this->req->input('sort')) {
            if ($this->validateSort()) {
                return ['errors' => $this->validateSort()];
            }
            $sortedGoods = $this->sorting($filteredGoods);
        } else {
            $sortedGoods = $filteredGoods;
        }
    //добавим доп характеристики товаров в результат
        $sortedGoods->with('additionalChars:id,name,value');
    //разобьём результат на страницы
        if ($this->req->input('perpage')) {
            if ($this->validatePerpage()) {
                return ['errors' => $this->validatePerpage()];
            }
            $result = $sortedGoods->paginate($this->req->input('perpage'));
        } else {
            $result = $sortedGoods->get();
        }

        return $result->toArray();
    }

    private function validateFilter(): array
    {
        $validator = Validator::make($this->req->all(), [
            'filter.name' => ['nullable', 'string', 'max:255'],
            'filter.price' => ['nullable', 'max:20'],
            'filter.category_ids' => ['nullable', 'regex:/^(?:\d\,?)+\d?$/'],
            'filter.additСhar_ids' => ['nullable', 'regex:/^(?:\d\,?)+\d?$/']
        ]);
        return ($validator->fails()) ? $validator->errors()->all() : [];
    }

    private function filtering(Builder $data): Builder
    {
        //фильтр по строке в имени
        if ($this->req->input('filter.name')) {
            $data->where('name', 'like', '%' . $this->req->input('filter.name') . '%');
        }
        //фильтр по цене
        if ($this->req->input('filter.price')) {
            $data->where('price', $this->req->input('filter.price'));
        }
        //фильтр по категориям
        if ($this->req->input('filter.category_ids')) {
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
            foreach (explode(',', $this->req->input('filter.category_ids')) as $catId) {
                $category = Category::whereId($catId)->first();
                if ($category) {
                    $catsWithChildsList = catChildsToList($category, $catsWithChildsList);
                }
            }
            $data->whereIn('category_id', $catsWithChildsList);
        }
        //фильтр по доп. характеристикам
        if ($this->req->input('filter.additСhar_ids')) {
            $chars = explode(',', $this->req->input('filter.additСhar_ids'));
            foreach ($chars as $char) {
                $data->whereHas('additionalChars', function (Builder $query) use ($char) {
                    $query->where('additional_char_id', $char);
                });
            }
        }
        return $data;
    }

    private function validateSort(): array
    {
        $validator = Validator::make($this->req->all(), [
            'sort.*' => ['nullable', 'string', 'max:255', Rule::in(['asc', 'desc'])]
        ]);
        return ($validator->fails()) ? $validator->errors()->all() : [];
    }

    private function sorting(Builder $data): Builder
    {
        $sortColumns = ['created_at', 'updated_at', 'name', 'price'];
        foreach ($sortColumns as $column) {
            if ($this->req->input('sort.' . $column)) {
                $data->orderBy($column, $this->req->input('sort.' . $column));
            }
        }
        return $data;
    }

    private function validatePerpage(): array
    {
        $validator = Validator::make($this->req->all(), ['perpage' => ['nullable', 'integer']]);
        return ($validator->fails()) ? $validator->errors()->all() : [];
    }
}
