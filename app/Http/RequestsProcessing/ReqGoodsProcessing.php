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

        //добавим изображения товаров в результат
        $sortedData->with('media');

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
     */
    public function reqProcessingForGoodsStore(): array
    {
        try {
            $req = request();
            $item = new Goods();
            $this->authorize('store', $item);
            $data['name'] = $req->name;
            $data['slug'] = $req->slug;
            $data['description'] = $req->description ?? '-';
            $data['price'] = $req->price ?? 0;
            $data['category_id'] = $req->category_id;
            $item->fill($data);
            $additChars = $req->input('additChars', []);
            if ($item->save()) {
                $item->additionalChars()->attach($additChars);
                try {
                    if ($req->file('file')) {
                        $item->addMediaFromRequest('file')->toMediaCollection('images');
                    }
                } catch (\Throwable $e) {
                    return [[
                        'success' => "Товар &quot;$item->name&quot; успешно создан, но не удалось установить изображение.",
                        'referer' => $_SERVER['HTTP_REFERER']
                    ], 200];
                }
                return [[
                    'success' => "Товар &quot;$item->name&quot; успешно создан.",
                    'referer' => $_SERVER['HTTP_REFERER']
                ], 200];
            }
            return [['errors' => 'Не удалось создать товар.'], 400];
        } catch (\Throwable $e) {
            return [['errors' => 'Не удалось создать товар.'], 400];
        }
    }

    /**
     * Обработка запроса на получение товара по id.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForGoodsShow(int $id): array
    {
        try {
            $prepareItem = Goods::findOrFail($id);
            $item = $prepareItem->toArray();
            $item['category'] = $prepareItem->category()->first()->name;
            $item['created_at'] = $prepareItem->created_at->format('d.m.Y H:i:s');
            $item['updated_at'] = $prepareItem->updated_at->format('d.m.Y H:i:s');
            $item['additional_chars'] = $prepareItem
                ->additionalChars()
                ->select('id', 'name', 'value')
                ->orderBy('name')
                ->get()
                ->toArray();
            try {
                $item['image'] = $prepareItem->getMedia('images')->first()->getUrl('normal');
            } catch (\Throwable $e) {
                $item['image'] = '/placeholder-300x200.png';
            }
            return $item;
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Обработка запроса на получение необходимых данных для формы изменения товара.
     *
     * @param int $id
     * @return array
     * @throws AuthorizationException
     */
    public function reqProcessingForGoodsEdit(int $id): array
    {
        try {
            $prepareItem = Goods::findOrFail($id);
            $this->authorize('edit', $prepareItem);
            $item = $prepareItem->toArray();
            $item['created_at'] = $prepareItem->created_at->format('d.m.Y H:i:s');
            $item['updated_at'] = $prepareItem->updated_at->format('d.m.Y H:i:s');
            $item['additional_chars'] = $prepareItem
                ->additionalChars()
                ->select('id', 'name', 'value')
                ->orderBy('name')
                ->get()
                ->toArray();
            try {
                $item['image'] = $prepareItem->getMedia('images')->first()->getUrl('normal');
            } catch (\Throwable $e) {
                $item['image'] = 'https://via.placeholder.com/300x200';
            }
            return $item;
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Обработка запроса на изменение товара.
     *
     * @param int $id
     * @return array
     * @throws AuthorizationException
     */
    public function reqProcessingForGoodsUpdate(int $id): array
    {
        try {
            $req = request();
            $item = Goods::find($id);
            $this->authorize('update', $item);
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
                try {
                    if ($req->file('file')) {
                        $item->clearMediaCollection('images')->addMediaFromRequest('file')->toMediaCollection('images');
                    }
                } catch (\Throwable $e) {
                    return [[
                        'success' => "Параметры товара &quot;$item->name&quot; успешно изменены, но не удалось изменить изображение.",
                        'referer' => $_SERVER['HTTP_REFERER']
                    ], 200];
                }
                return [[
                    'success' => "Параметры товара &quot;$item->name&quot; успешно изменены.",
                    'referer' => $_SERVER['HTTP_REFERER']
                ], 200];
            }
            return [['errors' => 'Ошибка изменения данных.'], 400];
        } catch (\Throwable $e) {
            return [['errors' => 'Ошибка изменения данных.'], 400];
        }
    }

    /**
     * Обработка запроса на удаление товара.
     *
     * @param int $id
     * @return array
     * @throws AuthorizationException
     */
    public function reqProcessingForGoodsDestroy(int $id): array
    {
        $item = Goods::find($id);
        $this->authorize('delete', $item);
        if ($item) {
            try {
                $item->additionalChars()->detach();
                $item->delete();
                return [['success' => "Товар &quot;$item->name&quot; успешно удален."], 200];
            } catch (\Throwable $e) {
                return [
                    ['errors' => "Не удалось удалить товар &quot;$item->name&quot;. Товар может участвовать в транзакциях."],
                    400
                ];
            }
        }
        return [['errors' => "Не удалось найти товар, указанный для удаления."], 400];
    }
}
