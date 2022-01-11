<?php

namespace App\Http\RequestsProcessing\Api;

use App\Models\Basket;

trait ApiReqBasketsProcessing
{
    /**
     * Обработка запроса корзины пользователя.
     *
     * @return array
     */
    public function reqProcessingForOwnBasket(): array
    {
        try {
            $data = request()->user()->basketForApi();
        } catch (\Throwable $e) {
            return ['errors' => 'Не удалось получить корзину пользователя.', 'status' => 400];
        }
        if ($data) {
            return [
                'success' => 'Корзина пользователя успешно получена.',
                'data' => $data,
                'status' => 200
            ];
        }
        return ['success' => 'Корзина пользователя пуста.', 'data' => $data, 'status' => 200];
    }

    /**
     * Обработка запроса на создание(обновление данных) корзины пользователя.
     *
     * @return array
     */
    public function reqProcessingForStore(): array
    {
        $req = request();
        if ($req->input('basket')) {
            $result = Basket::syncBasketData($req['basket']);
            $data = $req->user()->basketForApi();
            return ($result)
                ? ['success' => 'Корзина успешно сохранена.', 'data' => $data, 'status' => 200]
                : ['errors' => 'Не удалось сохранить корзину.', 'data' => $data, 'status' => 400];
        }
        $data = $req->user()->basketForApi();
        return ['errors' => 'Была передана пустая корзина.', 'data' => $data, 'status' => 200];
    }

    /**
     * Обработка запроса на удаление позиции.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForDestroy(int $id): array
    {
        $req = request();
        try {
            $basket = $req->user()->goodsInBasket();
            if (!$basket->where('baskets.goods_id', $id)->first()) {
                return ['errors' => "Не удалось найти позицию с id:$id в корзине пользователя.", 'status' => 400];
            }
            $basket->detach($id);
        } catch (\Throwable $e) {
            return ['errors' => "Не удалось удалить позицию с id:$id.", 'status' => 400];
        }
        return ['success' => 'Позиция успешно удалена.', 'data' => $req->user()->basketForApi(), 'status' => 200];
    }

    /**
     * Обработка запроса на очистку корзины пользователя.
     *
     * @return array
     */
    public function reqProcessingForPurge(): array
    {
        return (Basket::purgeBasket())
            ? ['success' => 'Корзина пользователя полностью очищена.', 'status' => 200]
            : ['errors' => 'Не удалось очистить корзину пользователя.', 'status' => 500];
    }
}
