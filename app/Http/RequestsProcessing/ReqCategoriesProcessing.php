<?php

namespace App\Http\RequestsProcessing;

use App\Models\Category;

trait ReqCategoriesProcessing
{
    use Filter;
    use Sorter;

    /**
     * Обработка запроса на список категорий.
     *
     * @return array
     */
    public function reqProcessingForIndex(): array
    {
        $categTree = Category::categoriesTree();
        $categories = Category::categoriesList();

        return compact('categTree', 'categories');
    }

    /**
     * Обработка запроса на получение необходимых данных для формы создания новой категории.
     *
     * @return array
     */
    public function reqProcessingForCreate(): array
    {
        $categories = $this->categsForModals();

        return compact('categories');
    }

    /**
     * Обработка запроса на создание категории.
     *
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function reqProcessingForStore(): array
    {
        $req = request();
        $cat = new Category();
        $this->authorize('store', $cat);
        $data['name'] = $req->input('name');
        $data['description'] = $req->input('description', '');
        $data['parent_id'] = $req->input('parent_id', 0);
        $data['category_id'] = $req->input('category_id');
        $data['level'] = ($req->input('parent_id'))
            ? Category::find($req->input('parent_id'))->level + 1
            : 1;
        $cat->fill($data);
        if ($cat->save()) {
            return [['success' => "Категория $cat->name успешно создана."], 200];
        }
        return [['errors' => 'Не удалось создать категорию.'], 400];
    }

    /**
     * Обработка запроса на получение необходимых данных для формы изменения категории.
     *
     * @param int $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function reqProcessingForEdit(int $id): array
    {
        $prepare_categ = Category::findOrFail($id);
        $this->authorize('edit', $prepare_categ);
        $cat = $prepare_categ->toArray();
        $cat['created_at'] = $prepare_categ->created_at->format('d.m.Y H:i:s');
        $cat['updated_at'] = $prepare_categ->updated_at->format('d.m.Y H:i:s');
        $categories = $this->categsForModals();
        return compact('cat', 'categories');
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

    /**
     * Список категорий для селектов в модалках по управлению категориями
     *
     * @return array
     */
    private function categsForModals(): array
    {
        $categories = array_reduce(Category::categoriesTree(), function ($res, $cat) {
            $res[] = ['id' => $cat['id'], 'name' => $cat['name']];
            if (isset($cat['childrens'])) {
                foreach ($cat['childrens'] as $cat2lvl) {
                    $res[] = ['id' => $cat2lvl['id'], 'name' => '- ' . $cat2lvl['name']];
                }
            }
            return $res;
        }, []);
        return $categories;
    }
}
