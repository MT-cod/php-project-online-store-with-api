<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\RequestsProcessing\ApiReqGoodsProcessing;
use App\Models\Goods;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class GoodsApiController extends Controller
{
    use ApiReqGoodsProcessing;

    public function index(): JsonResponse
    {
        /*$goods = QueryBuilder::for(Goods::class)
            ->allowedFilters([
                'name',
                AllowedFilter::exact('category_id'),
                AllowedFilter::exact('price'),
                AllowedFilter::callback('has_addit_chars', function (Builder $query, $value) {
                    $query->whereHas('additionalChars', function (Builder $query) use ($value) {
                        $query->where('additional_char_id', $value);
                    });
                }),
                //AllowedFilter::exact('additionalChars.additional_char_id')
            ])
            ->allowedFields('additional_chars.name', 'additional_chars.value')
            ->allowedIncludes(['additionalChars'])
            ->paginate(50);*/
        /*$appendss = request()->query();
        if ($appendss != null) {
            $goods->appends($appendss);
        }*/

        //$data = Goods::all();
        $data = self::reqProcessingForIndex();
        if (isset($data['errors'])) {
            return Response::json(['error' => $data['errors']], 400);
        }
        return Response::json([
            'success' => 'Список товаров с дополнительными характеристиками успешно получен.',
            'data' => $data
        ], 200);
    }

    /**
     * Получаем товар по запрошенному slug
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function slug($slug)
    {
        $data = Goods::where('slug', $slug)->first();
        if ($data) {
            return Response::json(['success' => 'Товар успешно получен.', 'data' => $data], 200);
        }
        return Response::json(['error' => 'Не удалось получить товар по запрошенному slug'], 400);
    }
}
