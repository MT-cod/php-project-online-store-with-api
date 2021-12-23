<?php

namespace App\Http\RequestsProcessing;

use App\Models\Category;
use Illuminate\Auth\Access\AuthorizationException;

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
        $categories = Category::categoriesListWithReadableDates();

        return compact('categTree', 'categories');
    }

    /**
     * Обработка запроса на получение необходимых данных для формы создания новой категории.
     *
     * @return array
     */
    public function reqProcessingForCreate(): array
    {
        $categories = Category::categsForSelectsWithMarkers(2);

        return compact('categories');
    }

    /**
     * Обработка запроса на создание категории.
     *
     * @return array
     * @throws AuthorizationException
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
     * @throws AuthorizationException
     */
    public function reqProcessingForEdit(int $id): array
    {
        $prepare_categ = Category::findOrFail($id);
        $this->authorize('edit', $prepare_categ);
        $cat = $prepare_categ->toArray();
        $cat['created_at'] = $prepare_categ->created_at->format('d.m.Y H:i:s');
        $cat['updated_at'] = $prepare_categ->updated_at->format('d.m.Y H:i:s');
        $categories = Category::categsForSelectsWithMarkers(2);
        return compact('cat', 'categories');
    }

    /**
     * Обработка запроса на изменение категории.
     *
     * @param int $id
     * @return array
     * @throws AuthorizationException
     */
    public function reqProcessingForUpdate(int $id): array
    {
        $req = request();
        $cat = Category::whereId($id)->first();
        $this->authorize('update', $cat);
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
                    if ($cat->childrens()->count()) {
                        foreach ($cat->childrens()->get() as $child) {
                            $child->level = $data['level'] + 1;
                        }
                    }
                    break;
            }
        }
        $cat->fill($data);
        if ($cat->save()) {
            return [['success' => "Параметры категории успешно изменены."], 200];
        }
        return [['errors' => 'Ошибка изменения данных.'], 400];
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
        $this->authorize('delete', $cat);
        if ($cat) {
            if ($cat->childrens()->count()) {
                return [
                    ['errors' => "Не удалось удалить категорию $cat->name! У категории имеется подкатегория!"],
                    400
                ];
            }
            if ($cat->goods()->count()) {
                return [
                    ['errors' => "Не удалось удалить категорию $cat->name! У категории есть товары!"],
                    400
                ];
            }
            try {
                $cat->delete();
                return [['success' => "Категория $cat->name успешно удалена."], 200];
            } catch (\Throwable $e) {
                return [['errors' => 'Не удалось удалить категорию.'], 400];
            }
        }
        return [['errors' => "Не удалось найти категорию с id:$id."], 400];
    }
}
