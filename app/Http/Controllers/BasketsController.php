<?php

namespace App\Http\Controllers;

use App\Http\RequestsProcessing\ReqBasketsProcessing;
use App\Http\Validators\BasketsStoreValidator;
use App\Models\Basket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class BasketsController extends Controller
{
    use ReqBasketsProcessing;

    public function index()
    {
        return $this->reqProcessingForIndex();
    }

    public function store(BasketsStoreValidator $req): RedirectResponse
    {
        $validationErrors = $req->errors();
        if ($validationErrors) {
            flash($validationErrors)->error();
        } else {
            if ($this->reqProcessingForStoreNewPosition(request())) {
                flash('Товар успешно добавлен в корзину')->success();
            } else {
                flash('Не удалось добавить товар в корзину')->error();
            }
        }

        return Redirect::to($_SERVER['HTTP_REFERER']);
    }

    /**
     * Обновление инфы по кол-ву позиций в корзине при корректировке пользователем.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user) {
                //Пользователь авторизован - работаем с табличными данными в БД
                $reqBasket = $request['basket'];
                $basket = array_map(fn($qua) => ['quantity' => $qua], $reqBasket);
                $user->goodsInBasket()->sync($basket);
            } else {
                //Пользователь не авторизован - работаем с данными корзины в сессии
                $basket = $request['basket'];
                array_walk($basket, fn($qua, $id) => session(['basket.' . $id . '.quantity' => $qua]));
            }
            $resultBasket = Basket::getActualDataOfBasket();
        } catch (\Exception $e) {
            $resultBasket = Basket::getActualDataOfBasket();
            return Response::json(['error' => 'Ошибка изменения данных', 'basket' => $resultBasket], 422);
        }
        return Response::json(['success' => 'Корзина успешно обновлена', 'basket' => $resultBasket], 200);
    }

    /**
     * Удаляем позицию или очищаем всю корзину.
     *
     * @param int $id
     * @return RedirectResponse|JsonResponse
     */
    public function destroy($id)
    {
        $user = Auth::user();
        //Если передано id=0, то очищаем корзину полностью
        if ($id == 0) {
            if ($user) {
                //Пользователь авторизован - работаем с табличными данными в БД
                $user->goodsInBasket()->detach();
            } else {
                //Пользователь не авторизован - работаем с данными о корзине в сессии
                session()->forget('basket');
            }
            return Redirect::to($_SERVER['HTTP_REFERER']);
        }
        //id не равно 0, будем удалять позицию из корзины по id товара
        if ($user) {
            //Пользователь авторизован - работаем с табличными данными
            $user->goodsInBasket()->detach($id);
        } else {
            //Пользователь не авторизован - работаем с данными о корзине в сессии
            session()->forget('basket.' . $id);
        }
        $basket = Basket::getActualDataOfBasket();
        return Response::json(compact('basket'), 200);
    }
}
