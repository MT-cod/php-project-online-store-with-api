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
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ShopController extends Controller
{
    /**
     * Собираем и выводим все необходимые данные магазина.
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

        $carouselData = Goods::where('price', Goods::max('price'))->get()->toArray();
        $carouselData[] = Goods::where('price', Goods::min('price'))->get()->toArray()[0];
        $carouselData[] = Goods::where('id', Goods::min('id'))->get()->toArray()[0];
        $carouselData[] = Goods::where('id', Goods::max('id'))->get()->toArray()[0];

        $user = Auth::user();
        if ($user) {
            //Если пользователь авторизован
            $baskCount = 0;
            if ($user->basket()) {
                $baskCount = count($user->basket());
            } elseif (session()->has('basket')) {
                //Пользователь авторизовался после того, как добавил товары в корзину.
                //Присваиваем неавторизованную корзину авторизованному пользователю
                $sessBasket = session('basket');
                $basketToDb = array_map(fn($qua) => ['quantity' => $qua['quantity']], $sessBasket);
                $user->goodsInBasket()->attach($basketToDb);
                session()->forget('basket');
                $baskCount = count($user->basket());
            }
        } else {
            //Если пользователь не авторизован - работаем с данными корзины в сессии
            $baskCount = (session()->has('basket')) ? count(session('basket')) : 0;
        }

        return view(
            'index',
            compact('goods', 'categories', 'additCharacteristics', 'carouselData', 'baskCount')
        );
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
