<?php

namespace App\Http\RequestsProcessing;

use App\Http\Validators\BasketsUpdateValidator;
use App\Models\Basket;
use Illuminate\Http\Request;

trait ReqBasketsProcessing
{
    /**
     * Обработка запроса на получение данных корзины.
     *
     * @return array
     */
    public function reqProcessingForIndex(): array
    {
        return ['basket' => Basket::getActualDataOfBasket()];
    }

    /**
     * Обработка запроса на добавление позиции в корзину.
     *
     * @param Request $req
     * @return bool
     */
    public function reqProcessingForStoreNewPosition(Request $req): bool
    {
        return Basket::addPositionToBasket($req['id'], $req['quantity']);
    }

    /**
     * Обработка запроса на обновление данных корзины.
     *
     * @param Request $req
     * @return array
     */
    public function reqProcessingForUpdateBasket(Request $req): array
    {
        $validationErrors = (new BasketsUpdateValidator($req))->errors();
        if ($validationErrors) {
            return [['errors' => $validationErrors, 'basket' => Basket::getActualDataOfBasket()], 400];
        }

        return (Basket::syncBasketData($req['basket']))
            ? [['success' => 'Корзина успешно обновлена.', 'basket' => Basket::getActualDataOfBasket()], 200]
            : [['errors' => 'Не удалось обновить корзину.', 'basket' => Basket::getActualDataOfBasket()], 400];
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
}
