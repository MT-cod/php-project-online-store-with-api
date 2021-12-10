<?php

namespace App\Http\RequestsProcessing;

use App\Http\Validators\ApiAdditionalCharsIndexValidator;
use App\Models\AdditionalChar;
use App\Models\Category;

trait ApiReqAdditionalCharsProcessing
{
    use Filter;
    use Sorter;

    /**
     * Обработка запроса на список категорий с фильтрацией, сортировкой и разбитием на страницы.
     *
     * @return array
     */
    public function reqProcessingForIndex(): array
    {
        $req = request();

        $validated = new ApiAdditionalCharsIndexValidator($req);
        if ($validated->errors()) {
            return ['errors' => $validated->errors(), 'status' => 400];
        }

        $filteredData = $this->filtering($req->input('filter'), AdditionalChar::select());
        $sortedData = $this->sorting($req->input('sort'), $filteredData);
        $result = $sortedData->paginate($req->input('perpage') ?? 1000);

        return [
            'success' => 'Список доп характеристик успешно получен.',
            'data' => $result->toArray(),
            'status' => 200
        ];
    }

    /**
     * Обработка запроса на получение категории по id.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForShow(int $id): array
    {
        $char = AdditionalChar::whereId($id)->first();
        if ($char) {
            return ['success' => 'Доп характеристика успешно получена.', 'data' => $char, 'status' => 200];
        }
        return ['errors' => "Не удалось найти доп характеристику с id:$id.", 'status' => 400];
    }

    /**
     * Обработка запроса на создание категории.
     *
     * @return array
     */
    public function reqProcessingForStore(): array
    {
        $req = request();
        $char = new AdditionalChar();
        $data['name'] = $req->input('name');
        $data['value'] = $req->input('value', '');
        $char->fill($data);
        if ($char->save()) {
            return ['success' => "Доп характеристика успешно создана.", 'data' => $char, 'status' => 200];
        }
        return ['errors' => 'Не удалось создать категорию.', 'status' => 400];
    }

    /**
     * Обработка запроса на изменение категории.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForUpdate(int $id): array
    {
        $req = request();
        $cat = Category::whereId($id)->first();
        $data = [];
        foreach ($req->input() as $row => $val) {
            switch ($row) {
                case 'name':
                    $data['name'] = $val;
                    break;
                case 'description':
                    $data['description'] = $val;
                    break;
                case 'parent_id':
                    $data['parent_id'] = $val;
                    $data['level'] = ($val)
                    ? Category::whereId($val)->first()->level + 1
                    : 1;
                    break;
            }
        }
        $cat->fill($data);
        if ($cat->save()) {
            $result = Category::whereId($id)->first();
            return ['success' => "Параметры категории успешно изменены.", 'data' => $result, 'status' => 200];
        }
        return ['errors' => 'Ошибка изменения данных.', 'status' => 400];
    }

    /**
     * Обработка запроса на удаление категории.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForDestroy(int $id): array
    {
        $cat = Category::whereId($id)->first();
        if ($cat) {
            if ($cat->childrens()->count()) {
                return [
                    'errors' => "Не удалось удалить категорию $cat->name! У категории имеется подкатегория!",
                    'status' => 400
                ];
            }
            if ($cat->goods()->count()) {
                return [
                    'errors' => "Не удалось удалить категорию $cat->name! У категории есть товары!",
                    'status' => 400
                ];
            }
            try {
                $cat->delete();
                return ['errors' => "Категория $cat->name успешно удалена", 'status' => 400];
            } catch (\Exception $e) {
                return ['errors' => 'Не удалось удалить категорию', 'status' => 400];
            }
        }
        return ['errors' => "Не удалось найти категорию с id:$id", 'status' => 400];
    }
}
