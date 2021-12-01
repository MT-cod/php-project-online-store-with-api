<?php

namespace App\Http\RequestsProcessing;

use App\Http\Validators\ApiGoodsIndexValidator;
use App\Models\Goods;

trait ApiReqGoodsProcessing
{
    use Filter;
    use Sorter;

    /**
     * Обработка запроса на список товаров с фильтрацией, сортировкой и разбитием на страницы + доп характеристики.
     *
     * @return array
     */
    public function reqProcessingForIndex(): array
    {
        $req = request();

        $validated = new ApiGoodsIndexValidator($req);
        if ($validated->errors()) {
            return ['errors' => $validated->errors()];
        }

        $filteredData = $this->filtering($req->input('filter'), Goods::select());
        $sortedData = $this->sorting($req->input('sort'), $filteredData);

        //добавим доп характеристики товаров в результат
        $sortedData->with('additionalChars:id,name,value');

        $result = $sortedData->paginate($req->input('perpage') ?? 1000);

        return $result->toArray();


    }
}
