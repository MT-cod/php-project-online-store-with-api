<?php

namespace App\Http\RequestsProcessing;

use App\Http\Validators\GoodsIndexValidator;
use App\Models\AdditionalChar;
use App\Models\Category;
use App\Models\Goods;
use Illuminate\Auth\Access\AuthorizationException;

trait ReqGoodsProcessing
{
    use Filter;
    use Sorter;

    /**
     * Обработка запроса на список товаров с фильтрацией, сортировкой и разбитием на страницы + доп характеристики.
     *
     * @return array
     */
    public function reqProcessingForGoodsIndex(): array
    {
        $req = request();

        $validationErrors = (new GoodsIndexValidator($req))->errors();
        if ($validationErrors) {
            return [[], $validationErrors->first()];
        }

        $filteredData = $this->filtering($req->input('filter'), Goods::select());
        $sortedData = $this->sorting($req->input('sort'), $filteredData);

        //добавим доп характеристики товаров в результат
        $sortedData->with('additionalChars:id,name,value');

        $result = $sortedData->paginate($req->input('perpage') ?? 20)->withQueryString();

        return [$result, []];
    }

    /**
     * Обработка запроса на получение необходимых данных для формы создания нового товара.
     *
     * @return array
     */
    public function reqProcessingForGoodsCreate(): array
    {
        $categories = Category::categsForSelectsWithMarkers();

        $additCharacteristics = AdditionalChar::additCharsForFilters();

        return compact('categories', 'additCharacteristics');
    }

    /**
     * Обработка запроса на создание товара.
     *
     * @return array
     * @throws AuthorizationException
     */
    public function reqProcessingForGoodsStore(): array
    {
        $req = request();
        $item = new Goods();
        $this->authorize('store', $item);
        $data['name'] = $req->name;
        $data['slug'] = $req->slug;
        $data['description'] = $req->input('description', '');
        $data['price'] = $req->input('price', 0);
        $data['category_id'] = $req->category_id;
        $item->fill($data);
        $additChars = $req->input('additChars', []);
        if ($item->save()) {
            $item->additionalChars()->attach($additChars);
            return [['success' => "Товар $item->name успешно создан. Обновите список товаров."], 200];
        }
        return [['errors' => 'Не удалось создать товар.'], 400];
    }

    /**
     * Обработка запроса на получение товара по id.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForGoodsShow(int $id): array
    {
        $prepare_item = Goods::findOrFail($id);
        $item = $prepare_item->toArray();
        $item['category'] = $prepare_item->category()->first()->name;
        $item['created_at'] = $prepare_item->created_at->format('d.m.Y H:i:s');
        $item['updated_at'] = $prepare_item->updated_at->format('d.m.Y H:i:s');
        $item['additional_chars'] = $prepare_item
            ->additionalChars()
            ->select('id', 'name', 'value')
            ->orderBy('name')
            ->get()
            ->toArray();
        return $item;
    }

    /**
     * Обработка запроса на изменение товара.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForGoodsUpdate(int $id): array
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
                    $data['description'] = $val;
                    break;
                case 'price':
                    $data['price'] = $val;
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
    public function reqProcessingForGoodsDestroy(int $id): array
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
