<?php

namespace App\Http\RequestsProcessing;

use App\Models\Category;
use App\Models\Goods;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait ApiReqBasketsProcessing
{
    /**
     * Обработка запроса на добавление позиции.
     *
     * @param Request $request
     * @return array
     */
    public function reqProcessingForStore(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'basket' => [function ($attr, $value, $fail): void {
            foreach ($value as $id => $quantity) {
                if (!Goods::find($id)) {
                    $fail('Указан некорректный идентификатор товара');
                }
                if (!is_numeric($quantity) || $quantity <= 0) {
                    $fail('Указано некорректное количество товара');
                }
            }
        }]]);
        if ($validator->fails()) {
            return ['errors' => $validator->errors()->all()];
        }
        if ($request->input('basket')) {
            $basket = array_map(fn($qua) => ['quantity' => $qua], $request->input('basket'));
            $request->user()->goodsInBasket()->sync($basket);
        }
        return $request->user()->basketForApi();
    }
}
