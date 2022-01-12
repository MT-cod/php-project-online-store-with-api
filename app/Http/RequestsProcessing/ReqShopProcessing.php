<?php

namespace App\Http\RequestsProcessing;

use App\Models\AdditionalChar;
use App\Models\Basket;
use App\Models\Category;
use App\Models\Goods;

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
        [$goods, $errors] = (isset($req['filter']) || isset($req['sort']) || isset($req['perpage']) || isset($req['page']))
            ? $this->reqProcessingForGoodsIndex()
            : [[], []];

        $categories = Category::categsForSelectsWithMarkers();
        $additCharacteristics = AdditionalChar::additCharsForFilters();

        $carouselData = Goods::where('price', Goods::max('price'))->get()->toArray();
        $carouselData[] = Goods::where('price', Goods::min('price'))->get()->toArray()[0];
        $carouselData[] = Goods::where('id', Goods::min('id'))->get()->toArray()[0];
        $carouselData[] = Goods::where('id', Goods::max('id'))->get()->toArray()[0];

        $baskCount = Basket::countOfPositionsInBasket();

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
