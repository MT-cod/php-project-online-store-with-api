<?php

namespace App\Http\Controllers;

use App\Models\AdditionalChar;
use App\Models\Category;
use App\Models\Goods;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws ValidationException
     */
    public function index()
    {
        $goods = (isset($_REQUEST['filter']))
            ? $this->validAndFiltIndex(request())->get()->toArray()
            : Goods::orderBy('name')->get()->toArray();

        $categories = array_reduce(Category::categoriesTree(), function ($res, $cat) {
            $res[] = ['id' => $cat['id'], 'name' => $cat['name']];
            if (isset($cat['childrens'])) {
                foreach ($cat['childrens'] as $cat2lvl) {
                    $res[] = ['id' => $cat2lvl['id'], 'name' => '- ' . $cat2lvl['name']];
                    if (isset($cat2lvl['childrens'])) {
                        foreach ($cat2lvl['childrens'] as $cat3lvl) {
                            $res[] = ['id' => $cat3lvl['id'], 'name' => '-- ' . $cat3lvl['name']];
                        }
                    }
                }
            }
            return $res;
        }, []);

        $additCharacteristics = AdditionalChar::select('id', 'name')->orderBy('name')->get()->toArray();

        return view('goods.filt_index', compact('goods', 'categories', 'additCharacteristics'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory
     * @throws ValidationException
     */
    public function validAndFiltIndex(Request $request)
    {
        $validated = $this->validate($request, [
            'filter.category_id' => [function ($attribute, $value, $fail): void {
                if ($value != null && Category::whereId($value)->first() === null) {
                    unset($_REQUEST['filter']['category_id']);
                    $fail('Запрошена некорректная категория для фильтра');
                }
            }],
            'filter.name' => 'nullable|max:255',
            'filter.additChars' => [function ($attribute, $value, $fail): void {
                if (AdditionalChar::whereId($value)->first() == null) {
                    unset($_REQUEST['filter']['additChars']);
                    $fail('Запрошена некорректная доп. характеристика для фильтра');
                }
            }
            ]
        ]);

        $filteredGoods = array_reduce($validated, function ($res, $filters) {
            foreach ($filters as $fName => $fValue) {
                //фильтр по категориям
                if ($fName === 'category_id' && !is_null($fValue)) {
                    $categories = [];
                    $categories[] = $fValue;
                    if (count(Category::whereId($fValue)->first()->childrens()->get()) > 0) {
                        foreach (Category::whereId($fValue)->first()->childrens()->get() as $cat2) {
                            $categories[] = $cat2['id'];
                            if (count(Category::whereId($cat2['id'])->first()->childrens()->get()) > 0) {
                                foreach (Category::whereId($cat2['id'])->first()->childrens()->get() as $cat3) {
                                    $categories[] = $cat3['id'];
                                }
                            }
                        }
                    }
                    $res = $res->whereIn('category_id', $categories);
                }
                //фильтр по строке в имени
                if ($fName == 'name' && !is_null($fValue)) {
                    $res = $res->where('name', 'like', '%' . $fValue . '%');
                }
                //фильтр по доп. характеристикам
                if ($fName == 'additChars' && !is_null($fValue)) {
                    foreach ($fValue as $char) {
                        $res = $res->whereHas('additionalChars', function (Builder $query) use ($char) {
                            $query->where('additional_char_id', $char);
                        });
                    }
                }
            }
            return $res;
        }, Goods::orderBy('name'));
        return $filteredGoods;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return array
     */
    public function show($id)
    {
        $item = Goods::findOrFail($id);
        $res = $item->toArray();
        $res['category'] = $item->category()->first()->name;
        $res['created_at'] = $item->created_at->format('d.m.Y H:i:s');
        $res['updated_at'] = $item->updated_at->format('d.m.Y H:i:s');
        $res['additional_chars'] = $item->additionalChars()->get()->toArray();
        return $res;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return array
     */
    public function edit($id)
    {
        $item = Goods::findOrFail($id);
        //$this->authorize('update', $task);
        $res = $item->toArray();
        $res['category'] = $item->category()->first()->name;
        $res['created_at'] = $item->created_at->format('d.m.Y H:i:s');
        $res['updated_at'] = $item->updated_at->format('d.m.Y H:i:s');
        $res['additional_chars'] = $item->additionalChars()->get()->toArray();
        return $res;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     * @throws AuthorizationException|ValidationException
     */
    public function update(Request $request, int $id)
    {
        $item = Goods::findOrFail($id);
        //$this->authorize('update', $item);
        $data = $this->validate($request, ['name' => [
            'required',
            function ($attribute, $value, $fail) use ($item): void {
                if ((Goods::where($attribute, $value)->first() !== null) && ($value !== $item->name)) {
                    $fail('Товар с таким именем уже существует');
                }
            }
        ],
            'status_id' => 'required']);
        $data['description'] = $request->input('description', '');
        $data['assigned_to_id'] = $request->input('assigned_to_id') ?? $item->assigned_to_id;
        $item->fill($data);
        $labels = $request->input('labels', []);
        if ($task->save()) {
            $task->labels()->sync($labels);
            flash('Задача успешно изменена')->success();
        } else {
            flash('Ошибка изменения задачи')->error();
        }
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Goods  $goods
     * @return \Illuminate\Http\Response
     */
    public function destroy(Goods $goods)
    {
        //
    }
}
