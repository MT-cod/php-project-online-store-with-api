<?php

namespace App\Http\Controllers;

use App\Http\RequestsProcessing\ReqCategoriesProcessing;
use App\Http\Validators\CategoriesStoreValidator;
use App\Models\Category;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class CategoriesController extends Controller
{
    use ReqCategoriesProcessing;

    public function index(): View|Factory|Application
    {
        return view('category.index', $this->reqProcessingForIndex());
    }

    public function create(): array
    {
        return $this->reqProcessingForCreate();
    }

    public function store(CategoriesStoreValidator $req): JsonResponse
    {
        $validationErrors = $req->errors();
        if ($validationErrors) {
            return Response::json(['error' => $validationErrors], 400);
        }

        [$result, $status] = $this->reqProcessingForStore();

        return Response::json($result, $status);
    }

    public function edit(int $id): array
    {
        return $this->reqProcessingForEdit($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return JsonResponse
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
        $this->authorize('delete', $cat);
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
    /*private function categsForModals()
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
    }*/
}
