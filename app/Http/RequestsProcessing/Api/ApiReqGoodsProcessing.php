<?php

namespace App\Http\RequestsProcessing\Api;

use App\Http\RequestsProcessing\Filter;
use App\Http\RequestsProcessing\Sorter;
use App\Http\Validators\Api\ApiGoodsIndexValidator;
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

        $validationErrors = (new ApiGoodsIndexValidator($req))->errors();
        if ($validationErrors) {
            return ['errors' => $validationErrors, 'status' => 400];
        }

        $filteredData = $this->filtering($req->input('filter'), Goods::select());
        $sortedData = $this->sorting($req->input('sort'), $filteredData);

        //добавим доп характеристики товаров в результат
        $sortedData->with('additionalChars:id,name,value');

        $result = $sortedData->paginate($req->input('perpage') ?? 1000);

        return [
            'success' => 'Список товаров с дополнительными характеристиками успешно получен.',
            'data' => $result->toArray(),
            'status' => 200
        ];
    }

    /**
     * Обработка запроса на получение товара по запрошенному slug.
     *
     * @param string $slug
     * @return array
     */
    public function reqProcessingForSlug(string $slug): array
    {
        $item = Goods::where('slug', $slug)->with('additionalChars:id,name,value')->first();
        if ($item) {
            return ['success' => 'Данные о товаре успешно получены.', 'data' => $item, 'status' => 200];
        }
        return ['errors' => 'Не удалось получить данные о товаре по запрошенному slug.', 'status' => 400];
    }

    /**
     * Обработка запроса на получение товара по id.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForShow(int $id): array
    {
        $item = Goods::whereId($id)->with('additionalChars:id,name,value')->first();
        if ($item) {
            return ['success' => 'Данные о товаре успешно получены.', 'data' => $item, 'status' => 200];
        }
        return ['errors' => "Не удалось получить данные о товаре с id:$id.", 'status' => 400];
    }

    /**
     * Обработка запроса на создание товара.
     *
     * @return array
     */
    public function reqProcessingForStore(): array
    {
        $req = request();
        $item = new Goods();
        $data['name'] = $req->input('name');
        $data['slug'] = $req->input('slug');
        $data['description'] = $req->description ?? '-';
        $data['price'] = $req->price ?? 0;
        $data['category_id'] = $req->input('category_id');
        $item->fill($data);
        $additChars = $req->input('additChars', []);
        if ($item->save()) {
            $item->additionalChars()->attach($additChars);
            $result = Goods::whereId($item->id)->with('additionalChars:id,name,value')->first();
            return ['success' => "Товар $item->name успешно создан.", 'data' => $result, 'status' => 200];
        }
        return ['errors' => 'Не удалось создать товар.', 'status' => 400];
    }

    /**
     * Обработка запроса на изменение товара.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForUpdate(int $id): array
    {
        $req = request();
        $item = Goods::find($id);
        $data = [];
        foreach ($req->input() as $row => $val) {
            switch ($row) {
                case 'name':
                    $data['name'] = $val;
                    break;
                case 'slug':
                    $data['slug'] = $val;
                    break;
                case 'description':
                    $data['description'] = $val ?? '-';
                    break;
                case 'price':
                    $data['price'] = $val ?? 0;
                    break;
                case 'category_id':
                    $data['category_id'] = $val;
                    break;
                case 'additChars':
                    $item->additionalChars()->sync($val);
                    break;
            }
        }
        $item->fill($data);
        if ($item->save()) {
            $result = Goods::whereId($id)->with('additionalChars:id,name,value')->first();
            return ['success' => "Параметры товара успешно изменены.", 'data' => $result, 'status' => 200];
        }
        return ['errors' => 'Ошибка изменения данных.', 'status' => 400];
    }

    /**
     * Обработка запроса на удаление товара.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForDestroy(int $id): array
    {
        $item = Goods::find($id);
        if ($item) {
            try {
                $item->additionalChars()->detach();
                $item->delete();
                return ['success' => 'Товар успешно удален.', 'status' => 200];
            } catch (\Throwable $e) {
                return [
                    'errors' => "Не удалось удалить товар с id:$id. Товар может участвовать в транзакциях.",
                    'status' => 400
                ];
            }
        }
        return ['errors' => "Не удалось найти товар с id:$id.", 'status' => 400];
    }
}
