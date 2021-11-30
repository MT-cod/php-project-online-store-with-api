<?php

namespace App\Http\RequestsProcessing;

trait ApiReqBasketsProcessing
{
    /**
     * Обработка запроса на добавление позиции.
     *
     * @return array
     */
    public function reqProcessingForStore(): array
    {
        $request = request();
        if ($request->input('basket')) {
            $basket = array_map(fn($qua) => ['quantity' => $qua], $request->input('basket'));
            $request->user()->goodsInBasket()->sync($basket);
        }
        return $request->user()->basketForApi();
    }

    /**
     * Обработка запроса на удаление позиции.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForDestroy(int $id): array
    {
        $request = request();
        $basket = $request->user()->goodsInBasket();
        if ($basket->where('baskets.goods_id', $id)->first()) {
            $basket->detach($id);
            return $request->user()->basketForApi();
        }
        return [];
    }
}
