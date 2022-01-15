<?php

namespace App\Http\Controllers;

use App\Http\RequestsProcessing\ReqBasketsProcessing;
use App\Http\Validators\BasketsStoreValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class BasketsController extends Controller
{
    use ReqBasketsProcessing;

    /**
     * Получение данных корзины.
     *
     * @return array
     */
    public function index(): array
    {
        return $this->reqProcessingForIndex();
    }

    /**
     * Добавление позиции в корзину.
     *
     * @param BasketsStoreValidator $req
     * @return RedirectResponse
     */
    public function store(BasketsStoreValidator $req): RedirectResponse
    {
        $validationErrors = $req->errors();
        if ($validationErrors) {
            flash($validationErrors->first())->error();
        } else if ($this->reqProcessingForStoreNewPosition(request())) {
            flash('Товар успешно добавлен в корзину')->success();
        } else {
            flash('Не удалось добавить товар в корзину')->error();
        }

        return Redirect::to($_SERVER['HTTP_REFERER']);
    }

    /**
     * Обновление данных корзины.
     *
     * @param Request $req
     * @return JsonResponse
     */
    public function update(Request $req): JsonResponse
    {
        [$result, $status] = $this->reqProcessingForUpdateBasket($req);
        return Response::json($result, $status);
    }

    /**
     * Удаляем позицию или очищаем всю корзину.
     *
     * @param int $id
     * @return RedirectResponse|JsonResponse
     */
    public function destroy(int $id): JsonResponse|RedirectResponse
    {
        [$result, $status] = $this->reqProcessingForDestroy($id);

        if ($id) {
            return Response::json($result, $status);
        }
        if (array_key_exists('success', $result)) {
            flash($result['success'])->success();
        } else {
            flash($result['errors'])->error();
        }
        return Redirect::to($_SERVER['HTTP_REFERER']);
    }
}
