<?php

namespace App\Http\RequestsProcessing;

use App\Models\AdditionalChar;
use App\Models\Category;
use App\Models\Goods;
use Illuminate\Support\Facades\Auth;

trait ReqShopProcessing
{
    use ReqGoodsProcessing;

    /**
     * Обработка запроса на наполнение главной страницы магазина.
     *
     * @return array
     */
    public function reqProcessingForShopIndex(): array
    {
        $req = request();
        [$goods, $errors] = (isset($req['filter']) || isset($req['sort']) || isset($req['perpage']))
            ? $this->reqProcessingForGoodsIndex()
            : [[], []];

        $categories = Category::categsForSelectsWithMarkers();
        $additCharacteristics = AdditionalChar::select('id', 'name', 'value')->orderBy('name')->get()->toArray();

        $carouselData = Goods::where('price', Goods::max('price'))->get()->toArray();
        $carouselData[] = Goods::where('price', Goods::min('price'))->get()->toArray()[0];
        $carouselData[] = Goods::where('id', Goods::min('id'))->get()->toArray()[0];
        $carouselData[] = Goods::where('id', Goods::max('id'))->get()->toArray()[0];

        $user = Auth::user();
        if ($user) {
            //Если пользователь авторизован
            $baskCount = 0;
            if ($user->basket()) {
                $baskCount = count($user->basket());
            } elseif (session()->has('basket')) {
                //Пользователь авторизовался после того, как добавил товары в корзину.
                //Присваиваем неавторизованную корзину авторизованному пользователю
                $sessBasket = session('basket');
                $basketToDb = array_map(static fn($qua) => ['quantity' => $qua['quantity']], $sessBasket);
                $user->goodsInBasket()->attach($basketToDb);
                session()->forget('basket');
                $baskCount = count($user->basket());
            }
        } else {
            //Если пользователь не авторизован - работаем с данными корзины в сессии
            $baskCount = (session()->has('basket')) ? count(session('basket')) : 0;
        }

        return compact(
            'goods',
            'categories',
            'additCharacteristics',
            'carouselData',
            'baskCount',
            'errors'
        );
    }
}
