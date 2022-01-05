<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

class Basket
{
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
