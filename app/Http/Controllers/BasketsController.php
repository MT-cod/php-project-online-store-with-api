<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        if (session()->has('basket')) {
            $basket = session('basket');
        }
        return compact('basket');
    }

    /**
     * Show resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        /*return null;*/
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        //$_SESSION['basket'][] = ['id' => $request['id'], 'name' => $request['name'], 'quantity' => $request['quantity']];
        //$basket_id = 'basket.' . $request['id'];
        //session()->push('basket.' . $request['id'] . '.id', 'test');
        //session()->flush();
        session([
            'basket.' . $request['id'] . '.id' => $request['id'],
            'basket.' . $request['id'] . '.name' => $request['name'],
            'basket.' . $request['id'] . '.quantity' => $request['quantity']
            ]);
        //session()->push('basket', ['id' => $request['id'], 'name' => $request['name'], 'quantity' => $request['quantity']]);
        //session()->push('basket', ['id' => $request['id'], 'name' => $request['name'], 'quantity' => $request['quantity']]);
        //session(['basket' => $request['name']]);
        flash('Товар "' . $request['name'] . '" добавлен в корзину')->success();
        return Redirect::to($_SERVER['HTTP_REFERER']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        /*$_SESSION['basket'][] = $request['basket'];
        return Response::json(['success' => 'Корзина успешно обновлена'], 200);*/
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
