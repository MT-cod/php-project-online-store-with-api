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
     * Display the specified resource.
     *
     * @return array
     */
    public function index()
    {
        //$data = session()->all();
        $basket = [];
        if (session()->has('basket')) {
            $basket = ['basket' => session('basket')];
        }
        //return Response::json($basket, 200);
        return $basket;//compact('basket');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $_SESSION['basket'][] = $request['basket'];
        return Response::json(['success' => 'Корзина успешно обновлена'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Basket  $basket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Basket $basket)
    {
        //
    }
}
