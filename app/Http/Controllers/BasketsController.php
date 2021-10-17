<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        //$basket = [];
        $user = Auth::user();
        $basket = $user ? $basket = $user->basket() : [];
        if (!$basket && session()->has('basket')) {
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
        //session()->flush();
        session([
            'basket.' . $request['id'] . '.id' => $request['id'],
            'basket.' . $request['id'] . '.name' => $request['name'],
            'basket.' . $request['id'] . '.quantity' => $request['quantity']
            ]);
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
            $basket = $request['basket'];
            array_walk($basket, fn($val, $id) => session(['basket.' . $id . '.quantity' => $val]));
        } catch (\Exception $e) {
            return Response::json(['error' => 'Ошибка изменения данных'], 422);
        }
        return Response::json(['success' => 'Корзина успешно обновлена'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse|JsonResponse
     */
    public function destroy($id)
    {
        if ($id == 0) {
            session()->forget('basket');
            return Redirect::to($_SERVER['HTTP_REFERER']);
        }
        session()->forget('basket.' . $id);
        $basket = [];
        if (session()->has('basket')) {
            $basket = session('basket');
        }
        return Response::json(compact('basket'), 200);
    }
}
