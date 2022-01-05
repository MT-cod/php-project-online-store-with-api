<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

class Basket
{
    /*public function __construct()
    {}*/

    public static function addPositionToBasket(int $id, int $quantity): bool
    {
        $user = Auth::user();
        try {
            if ($user) {
                //Пользователь авторизован - работаем с табличными данными в БД
                $user->goodsInBasket()->attach($id, ['quantity' => $quantity]);
            } else {
                //Пользователь не авторизован - работаем с данными корзины в сессии
                session(['basket.' . $id . '.id' => $id, 'basket.' . $id . '.quantity' => $quantity]);
            }
        } catch (\Throwable $e) {
            return false;
        }
        return true;
    }

    public static function getActualDataOfBasket(): array
    {
        $basket = [];
        $user = Auth::user();
        if ($user) {
            //Если пользователь авторизован - работаем с табличными данными в БД
            if ($user->basket()) {
                $basket = $user->basket();
            }
        } elseif (session()->has('basket')) {
            //Если пользователь не авторизован и корзина в сессии присутствует - работаем с данными корзины в сессии
            $smallBasket = session('basket');
            $basket = array_reduce($smallBasket, function ($res, $val): array {
                $item = Goods::find($val['id']);
                if ($item) {
                    $res[$val['id']] = [
                        'id' => $val['id'],
                        'name' => $item->name,
                        'price' => $item->price,
                        'quantity' => $val['quantity']
                    ];
                    return $res;
                }
            }, []);
        }
        return $basket;
    }

    public static function countOfPositionsInBasket(): int
    {
        self::checkAndMoveSessBasketToUserBasket();
        $user = Auth::user();
        if ($user) {
            //Если пользователь авторизован
            $baskCount = ($user->basket()) ? count($user->basket()) : 0;
        } else {
            //Если пользователь не авторизован - работаем с данными корзины в сессии
            $baskCount = (session()->has('basket')) ? count(session('basket')) : 0;
        }
        return $baskCount;
    }

    //Если пользователь авторизовался после того, как добавил товары в корзину,
    //то присваиваем неавторизованную корзину авторизованному пользователю
    public static function checkAndMoveSessBasketToUserBasket(): void
    {
        $user = Auth::user();
        if ($user && session()->has('basket')) {
            $sessBasket = session('basket');
            $basketToDb = array_map(static fn($qua) => ['quantity' => $qua['quantity']], $sessBasket);
            $user->goodsInBasket()->attach($basketToDb);
            session()->forget('basket');
        }
    }
}
