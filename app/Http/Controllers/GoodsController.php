<?php

namespace App\Http\Controllers;

use App\Http\RequestsProcessing\ReqGoodsProcessing;
use App\Http\Validators\GoodsStoreValidator;
use App\Models\AdditionalChar;
use App\Models\Category;
use App\Models\Goods;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GoodsController extends Controller
{
    use ReqGoodsProcessing;

    public function index(): Factory|View|Application
    {
        [$goods, $errors] = $this->reqProcessingForGoodsIndex();

        if ($errors) {
            flash($errors)->error();
            $_REQUEST = ['filter_expand' => "1"];
        }

        $categories = Category::categsForSelectsWithMarkers();

        $additCharacteristics = AdditionalChar::additCharsForFilters();

        return view('goods.index', compact('goods', 'categories', 'additCharacteristics'));
    }

    public function create(): array
    {
        return $this->reqProcessingForGoodsCreate();
    }

    public function store(GoodsStoreValidator $req): JsonResponse
    {
        $validationErrors = $req->errors();
        if ($validationErrors) {
            return Response::json(['errors' => $validationErrors], 400);
        }

        [$result, $status] = $this->reqProcessingForGoodsStore();

        return Response::json($result, $status);
    }

    public function show(int $id): array
    {
        return $this->reqProcessingForGoodsShow($id);
        /*$item = Goods::findOrFail($id);
        $res = $item->toArray();
        $res['category'] = $item->category()->first()->name;
        $res['created_at'] = $item->created_at->format('d.m.Y H:i:s');
        $res['updated_at'] = $item->updated_at->format('d.m.Y H:i:s');
        $res['additional_chars'] = $item->additionalChars()->get()->toArray();
        return $res;*/
    }

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

        $categories = Category::categsForSelectsWithMarkers();

        $additCharacteristics = AdditionalChar::additCharsForFilters();

        return compact('item', 'categories', 'additCharacteristics');
    }

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
     * @throws ValidationException
     */
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

    public function regenerateDb(): RedirectResponse
    {
        session()->flush();
        Artisan::call('migrate:fresh --seed');
        return Redirect::to('/');
    }
}
