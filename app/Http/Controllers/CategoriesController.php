<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Goods;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
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
        //$this->authorize('store', $cat);
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
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }

//Общие функции контроллера-----------------------------------------------------------------

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return array
     */
    private function validateCategoryParams(Request $request, Category $cat)
    {
        return $this->validate($request, [
            'name' => [
                'required',
                function ($attribute, $value, $fail) use ($cat): void {
                    if ((Category::where($attribute, $value)->first() !== null) && ($value !== $cat->name)) {
                        $fail('Категория с таким именем уже существует');
                    }
                }],
            'parent_id' => [
                'required',
                function ($attribute, $value, $fail) use ($cat): void {
                    if ($value != 0) {
                        if (Category::where('id', $value)->first()->level == 3) {
                            $fail('Категория не может быть подкатегорией категории 3-го уровня');
                        }
                    }
                }]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
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
