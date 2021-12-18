<?php

namespace App\Http\RequestsProcessing\Api;

use App\Http\RequestsProcessing\Filter;
use App\Http\RequestsProcessing\Sorter;
use App\Http\Validators\Api\ApiCategoriesIndexValidator;
use App\Models\Category;

trait ApiReqCategoriesProcessing
{
    use Filter;
    use Sorter;

    /**
     * Обработка запроса на получение дерева категорий.
     *
     * @return array
     */
    public function reqProcessingForTree(): array
    {
        $data = Category::categoriesTree();
        if ($data) {
            return ['success' => 'Дерево категорий успешно получено.', 'data' => $data, 'status' => 200];
        }
        return ['errors' => 'Не удалось получить дерево категорий', 'status' => 400];
    }

    /**
     * Обработка запроса на список категорий с фильтрацией, сортировкой и разбитием на страницы.
     *
     * @return array
     */
    public function reqProcessingForIndex(): array
    {
        $req = request();

        $validationErrors = (new ApiCategoriesIndexValidator($req))->errors();
        if ($validationErrors) {
            return ['errors' => $validationErrors, 'status' => 400];
        }

        $filteredData = $this->filtering($req->input('filter'), Category::select());
        $sortedData = $this->sorting($req->input('sort'), $filteredData);
        $result = $sortedData->paginate($req->input('perpage') ?? 1000);

        return [
            'success' => 'Список категорий успешно получен.',
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
        $cat = Category::find($id);
        if ($cat) {
            return ['success' => 'Категория успешно получена.', 'data' => $cat, 'status' => 200];
        }
        return ['errors' => "Не удалось найти категорию с id:$id.", 'status' => 400];
    }

    /**
     * Обработка запроса на создание категории.
     *
     * @return array
     */
    public function reqProcessingForStore(): array
    {
        $req = request();
        $cat = new Category();
        $data['name'] = $req->input('name');
        $data['description'] = $req->input('description', '');
        $data['parent_id'] = $req->input('parent_id', 0);
        $data['category_id'] = $req->input('category_id');
        $data['level'] = ($req->input('parent_id'))
            ? Category::find($req->input('parent_id'))->level + 1
            : 1;
        $cat->fill($data);
        if ($cat->save()) {
            return ['success' => "Категория $cat->name успешно создана.", 'data' => $cat, 'status' => 200];
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
                    ? Category::find($val)->level + 1
                    : 1;
                    break;
            }
        }
        $cat->fill($data);
        if ($cat->save()) {
            return ['success' => "Параметры категории успешно изменены.", 'data' => $cat, 'status' => 200];
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
        $cat = Category::find($id);
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
                return ['success' => "Категория $cat->name успешно удалена.", 'status' => 200];
            } catch (\Throwable $e) {
                return ['errors' => 'Не удалось удалить категорию.', 'status' => 400];
            }
        }
        return ['errors' => "Не удалось найти категорию с id:$id.", 'status' => 400];
    }
}
