<?php

namespace App\Http\RequestsProcessing\Api;

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
            $basket = array_map(static fn($qua) => ['quantity' => $qua], $req->input('basket'));
            $req->user()->goodsInBasket()->sync($basket);
        }
        $result = $req->user()->basketForApi();
        return ['success' => 'Корзина успешно сохранена.', 'data' => $result, 'status' => 200];
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
        try {
            $result = request()->user()->goodsInBasket()->detach();
        } catch (\Throwable $e) {
            return ['errors' => 'Не удалось очистить корзину пользователя.', 'status' => 500];
        }
        if ($result) {
            return ['success' => 'Корзина пользователя полностью очищена.', 'status' => 200];
        }
        return ['errors' => 'Не удалось очистить корзину пользователя.', 'status' => 500];
    }
}
