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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
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
            ? $this->filterValidDataForIndex($this->validateForIndex(request()))->get()->toArray()
            : Goods::orderBy('name')->get()->toArray();

        [$categories, $additCharacteristics] = $this->categAndAdditCharsForModals();

        return view('goods.filt_index', compact('goods', 'categories', 'additCharacteristics'));
    }

    /**
     * Show the form for creating the specified resource.
     *
     * @return array
     */
    public function create()
    {
        [$categories, $additCharacteristics] = $this->categAndAdditCharsForModals();
        return compact('categories', 'additCharacteristics');
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
        $item = new Goods();
        $this->authorize('store', $item);
        $data = $this->validateCommonGoodsParams($request, $item);
        $data['description'] = $request->input('description', '');
        $data['price'] = $request->input('price') ?? 0;
        $item->fill($data);
        $additChars = $request->input('additChars', []);
        if ($item->save()) {
            $item->additionalChars()->attach($additChars);
            return Response::json(['success' => "Товар $item->name успешно создан"], 200);
        }
        return Response::json(['error' => 'Ошибка данных'], 422);
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
        $prepare_item = Goods::findOrFail($id);
        $this->authorize('edit', $prepare_item);
        $item = $prepare_item->toArray();
        $item['created_at'] = $prepare_item->created_at->format('d.m.Y H:i:s');
        $item['updated_at'] = $prepare_item->updated_at->format('d.m.Y H:i:s');
        $item['additional_chars'] = $prepare_item
            ->additionalChars()
            ->select('id', 'name', 'value')
            ->orderBy('name')
            ->get()
            ->toArray();

        [$categories, $additCharacteristics] = $this->categAndAdditCharsForModals();

        return compact('item', 'categories', 'additCharacteristics');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException|ValidationException
     */
    public function update(Request $request, int $id)
    {
        $item = Goods::findOrFail($id);
        $this->authorize('update', $item);
        $data = $this->validateCommonGoodsParams($request, $item);
        $data['description'] = $request->input('description', '');
        $data['price'] = $request->input('price') ?? 0;
        $item->fill($data);
        $additChars = $request->input('additChars', []);
        if ($item->save()) {
            $item->additionalChars()->sync($additChars);
            return Response::json(['success' => 'Параметры товара успешно изменены'], 200);
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
        $item = Goods::findOrFail($id);
        $this->authorize('delete', $item);
        try {
            $item->additionalChars()->detach();
            $item->delete();
            flash('Товар "' . $item->name . '" успешно удалён')->success();
        } catch (\Exception $e) {
            flash('Не удалось удалить товар')->error();
        } finally {
            return Redirect::to($_SERVER['HTTP_REFERER']);
        }
    }

//Общие функции контроллера-----------------------------------------------------------------

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    private function validateForIndex(Request $request)
    {
        return $this->validate($request, [
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
    }

    private function filterValidDataForIndex(array $validated)
    {
        return array_reduce($validated, function ($res, $filters) {
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
    }

    private function validateCommonGoodsParams(Request $request, Goods $item): array
    {
        return $this->validate($request, [
            'name' => [
                'required',
                function ($attribute, $value, $fail) use ($item): void {
                    if ((Goods::where($attribute, $value)->first() !== null) && ($value !== $item->name)) {
                        $fail('Товар с таким именем уже существует');
                    }
                }],
            'slug' => [
                'required',
                function ($attribute, $value, $fail) use ($item): void {
                    if ((Goods::where($attribute, $value)->first() !== null) && ($value !== $item->slug)) {
                        $fail('Товар с таким slug уже существует');
                    }
                }],
            'category_id' => [
                'required',
                function ($attribute, $value, $fail) use ($item): void {
                    if (Category::where('id', $value)->first()->level == 1) {
                        $fail('Категории 1-го уровня не могут принадлежать товары');
                    }
                }]
        ]);
    }

    private function categAndAdditCharsForModals(): array
    {
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
        $additCharacteristics = AdditionalChar::select('id', 'name', 'value')->orderBy('name')->get()->toArray();
        return [$categories, $additCharacteristics];
    }

    public function regenerateDb(): View|Factory|Application
    {
        Artisan::call('migrate:fresh --seed');
        return view('index');
    }
}
