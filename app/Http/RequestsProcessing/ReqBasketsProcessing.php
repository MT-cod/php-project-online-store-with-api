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
        return Basket::addItemToBasket($req['id'], $req['quantity']);
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
        if ($id) {
            //id не нулевое, будем удалять позицию из корзины по id
            return (Basket::delItemFromBasket($id))
                ? [['success' => 'Позиция успешно удалена.', 'basket' => Basket::getActualDataOfBasket()], 200]
                : [['errors' => 'Не удалось удалить позицию с id:$id.', 'basket' => Basket::getActualDataOfBasket()], 500];
        }
        //Если передан нулевой id, то очищаем корзину полностью
        return (Basket::purgeBasket())
            ? [['success' => 'Корзина полностью очищена.'], 200]
            : [['errors' => 'Не удалось очистить корзину.'], 500];
    }
}
