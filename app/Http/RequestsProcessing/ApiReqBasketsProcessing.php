<?php

namespace App\Http\RequestsProcessing;

trait ApiReqBasketsProcessing
{
    /**
     * Обработка запроса корзины пользователя.
     *
     * @return array
     */
    public function reqProcessingForOwnBasket(): array
    {
        $data = request()->user()->basketForApi();
        if ($data) {
            return [
                'success' => 'Корзина пользователя успешно получена.',
                'data' => $data,
                'status' => 200
            ];
        }
        return [
            'success' => 'Корзина пользователя пуста.',
            'data' => $data,
            'status' => 200
        ];
    }

    /**
     * Обработка запроса на создание(обновление данных) корзины.
     *
     * @return array
     */
    public function reqProcessingForStore(): array
    {
        $req = request();
        if ($req->input('basket')) {
            $basket = array_map(static fn($qua) => ['quantity' => $qua], $req->input('basket'));
            $req->user()->goodsInBasket()->sync($basket);
        }
        $result = $req->user()->basketForApi();
        return [
            'success' => 'Корзина успешно сохранена.',
            'data' => $result,
            'status' => 200
        ];
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
