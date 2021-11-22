<?php

namespace App\Http\RequestsProcessing;

use App\Models\Category;
use App\Models\Goods;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait ApiReqGoodsProcessing
{
    private Request $req;

    /**
     * Обработка запроса в контроллере.
     *
     * @return array
     */
    public function reqProcessingForIndex(): array
    {
        $this->req = request();
        $goods = Goods::select();

        if ($this->req->input('filter')) {
            if ($this->validateFilter()) {
                return ['errors' => $this->validateFilter()];
            }
            $filteredGoods = $this->filtering($goods);
        } else {
            $filteredGoods = $goods;
        }



        return $filteredGoods->get()->toArray();
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

    private function filtering($goods): Builder
    {
        //фильтр по строке в имени
        if ($this->req->input('filter.name')) {
            $goods->where('name', 'like', '%' . $this->req->input('filter.name') . '%');
        }

        //фильтр по цене
        if ($this->req->input('filter.price')) {
            $goods->where('price', $this->req->input('filter.price'));
        }

        //фильтр по категориям
        if ($this->req->input('filter.category_ids')) {
            $categories = [];
            $cats = explode(',', $this->req->input('filter.category_ids'));
            foreach ($cats as $cat) {
                if (Category::whereId($cat)->first()) {
                    $categories[] = $cat;
                    if (count(Category::whereId($cat)->first()->childrens()->get()) > 0) {
                        foreach (Category::whereId($cat)->first()->childrens()->get() as $cat2) {
                            $categories[] = $cat2['id'];
                            if (count(Category::whereId($cat2['id'])->first()->childrens()->get()) > 0) {
                                foreach (Category::whereId($cat2['id'])->first()->childrens()->get() as $cat3) {
                                    $categories[] = $cat3['id'];
                                }
                            }
                        }
                    }
                }
            }
            $goods->whereIn('category_id', $categories);
        }

        //фильтр по доп. характеристикам
        if ($this->req->input('filter.additСhar_ids')) {
            $chars = explode(',', $this->req->input('filter.additСhar_ids'));
            foreach ($chars as $char) {
                $goods->whereHas('additionalChars', function (Builder $query) use ($char) {
                    $query->where('additional_char_id', $char);
                });
            }
        }

        return $goods;
    }
}
