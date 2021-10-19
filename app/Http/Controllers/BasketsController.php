<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class BasketsController extends Controller
{
    /**
     * Return all basket.
     *
     * @return array
     */
    public function index()
    {
        $basket = [];
        $user = Auth::user();
        if ($user) {
            if ($user->basket()) {
                $basket = $user->basket();
                return compact('basket');
            }
            if (session()->has('basket')) {
                $basket = session('basket');
                return compact('basket');
            }
        }
        if (session()->has('basket')) {
            $basket = session('basket');
        }
        return compact('basket');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            //Пользователь авторизован - работаем с табличными данными в БД
            $user->goodsInBasket()->attach($request['id'], ['quantity' => $request['quantity']]);
        } else {
            //Пользователь не авторизован - работаем с данными корзины в сессии
            session([
                'basket.' . $request['id'] . '.id' => $request['id'],
                'basket.' . $request['id'] . '.name' => $request['name'],
                'basket.' . $request['id'] . '.quantity' => $request['quantity']
            ]);
        }
        flash('Товар "' . $request['name'] . '" добавлен в корзину')->success();
        return Redirect::to($_SERVER['HTTP_REFERER']);
    }

    /**
     * Update the specified resource in storage.
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
        } catch (\Exception $e) {
            return Response::json(['error' => 'Ошибка изменения данных'], 422);
        }
        return Response::json(['success' => 'Корзина успешно обновлена'], 200);
    }

    /**
     * Удаляем позицию или очищаем всю корзину.
     *
     * @param int $id
     * @return RedirectResponse|JsonResponse
     */
    public function destroy($id)
    {
        $basket = [];
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
            $basket = $user->basket();
        } else {
            //Пользователь не авторизован - работаем с данными о корзине в сессии
            session()->forget('basket.' . $id);
            if (session()->has('basket')) {
                $basket = session('basket');
            }
        }
        return Response::json(compact('basket'), 200);
    }
}
