<?php

namespace App\Http\Controllers;

use App\Models\AdditionalChar;
use App\Models\Category;
use App\Models\Goods;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ShopController extends Controller
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
            : null;

        [$categories, $additCharacteristics] = $this->categAndAdditCharsForIndex();

        return view('index', compact('goods', 'categories', 'additCharacteristics'));
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

    private function categAndAdditCharsForIndex(): array
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
}
