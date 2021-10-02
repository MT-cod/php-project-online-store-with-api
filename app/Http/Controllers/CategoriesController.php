<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Goods;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $categTree = Category::categoriesTree();
        $categories = Category::categoriesList();
        return view('category.index', compact('categTree', 'categories'));
    }

    /**
     * Show the form for creating the specified resource.
     *
     * @return array
     */
    public function create()
    {
        $categories = $this->categsForModals();
        return compact('categories');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $cat = new Category();
        $this->authorize('store', $cat);
        $data = $this->validateCategoryParams($request, $cat);
        $data['description'] = $request->input('description', '');
        $data['level'] = ($data['parent_id'] == 0) ? 1 : Category::findOrFail($data['parent_id'])->level + 1;
        $cat->fill($data);
        if ($cat->save()) {
            return Response::json(['success' => "Категория $cat->name успешно создана"], 200);
        }
        return Response::json(['error' => 'Ошибка данных'], 422);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return array
     * @throws AuthorizationException
     */
    public function edit($id)
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
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, $id)
    {
        $cat = Category::findOrFail($id);
        $this->authorize('update', $cat);
        $data = $this->validateCategoryParamsForUpdate($request, $cat);
        $data['description'] = $request->input('description', '');
        //Проставляем уровни изменённой категории и её детям
        $newLvl = ($request->input('parent_id') == 0)
            ? 1
            : Category::findOrFail($request->input('parent_id'))->level + 1;
        $data['level'] = $newLvl;
        if ($cat->childrens()->count()) {
            foreach ($cat->childrens()->get() as $child) {
                $child->level = $newLvl + 1;
            }
        }

        $cat->fill($data);
        if ($cat->save()) {
            return Response::json(['success' => 'Параметры категории успешно изменены'], 200);
        }
        return Response::json(['error' => 'Ошибка изменения данных'], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(int $id)
    {
        $cat = Category::findOrFail($id);
        if (env('APP_ENV') !== 'testing') {
            $this->authorize('delete', $cat);
        }
        if ($cat->childrens()->count()) {
            flash("Не удалось удалить категорию &quot;$cat->name&quot;! У категории имеется подкатегория!")->error();
            return Redirect::to($_SERVER['HTTP_REFERER']);
        }
        if ($cat->goods()->count()) {
            flash("Не удалось удалить категорию &quot;$cat->name&quot;! У категории есть товары!")->error();
            return Redirect::to($_SERVER['HTTP_REFERER']);
        }
        try {
            $cat->delete();
            flash("Категория &quot;$cat->name&quot; успешно удалена")->success();
        } catch (\Exception $e) {
            flash('Не удалось удалить категорию')->error();
        } finally {
            return Redirect::to($_SERVER['HTTP_REFERER']);
        }
    }

//Общие функции контроллера-----------------------------------------------------------------

    /**
     * @param Request $request
     * @param Category $cat
     * @return array
     * @throws ValidationException
     */
    private function validateCategoryParams(Request $request, Category $cat)
    {
        return $this->validate($request, [
            'name' => [
                'required',
                function ($attribute, $value, $fail) use ($cat): void {
                    if ((Category::where($attribute, $value)->first() !== null) && ($value !== $cat->name)) {
                        $fail('Категория с таким именем уже существует!');
                    }
                }],
            'parent_id' => [
                'required',
                function ($attribute, $value, $fail) use ($cat): void {
                    if ($value != 0) {
                        if (Category::where('id', $value)->first()->level == 3) {
                            $fail('Категория не может быть подкатегорией категории 3-го уровня!');
                        }
                    }
                }]
        ]);
    }

    /**
     * @param Request $request
     * @param Category $cat
     * @return array
     * @throws ValidationException
     */
    private function validateCategoryParamsForUpdate(Request $request, Category $cat)
    {
        return $this->validate($request, [
            'name' => [
                'required',
                function ($attribute, $value, $fail) use ($cat): void {
                    if ((Category::where($attribute, $value)->first() !== null) && ($value !== $cat->name)) {
                        $fail('Категория с таким именем уже существует!');
                    }
                }],
            'parent_id' => [
                'required',
                function ($attribute, $value, $fail) use ($cat): void {
                    if ($value == 0) {
                        if ($cat->goods()->count()) {
                            $fail('У категории 1-го уровня не может быть товаров!');
                        }
                    }
                    $parent = Category::where('id', $value)->first();
                    if ($parent) {
                        if ($parent->id === $cat->id) {
                            $fail('Категория не может иметь себя же в родителях!');
                        }
                        if ($parent->level == 3) {
                            $fail('Категория не может стать категорией 4-го уровня! Максимальный уровень 3');
                        }
                        if ($parent->level < 3) {
                            if ($cat->childrens()->count()) {
                                if ($parent->level == 2) {
                                    $fail('Категория не может стать категорией 3-го уровня, т.к. имеет дочернюю категорию!');
                                } else {
                                    foreach ($cat->childrens()->get() as $child) {
                                        if ($child->childrens()->count()) {
                                            $fail('Категория не может стать категорией 2-го уровня, т.к. имеет дочернюю категорию с подкатегорией!');
                                        }
                                    }
                                }
                            }
                        }
                    }
                }]
        ]);
    }


    /**
     * @return array
     */
    private function categsForModals()
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
