<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $orders = [];
        foreach (Order::all('id', 'created_at', 'name', 'email', 'phone', 'completed') as $order) {
            $resOrder = $order->toArray();
            $resOrder['created_at'] = $order->created_at->format('d.m.Y H:i:s');
            $orders[] = $resOrder;
        }
        /*$allOrders = Order::select('id', 'created_at', 'name', 'email', 'phone', 'completed');
        if (isset($_REQUEST['filter']['name']) && ($_REQUEST['filter']['name'] !== '')) {
            $allOrders->where('name', 'like', '%' . $_REQUEST['filter']['name'] . '%');
        }
        //$allOrders->created_at->format('d.m.Y H:i:s');
        $orders = $allOrders->orderByDesc('id')->get()->toArray();*/
        return view('order.index', compact('orders'));
    }

    /**
     * Передаем данные для окна формирования заказа.
     *
     * @return array
     */
    public function create()
    {
        $user_data = [];
        $user = Auth::user();
        if ($user) {
            //Если пользователь авторизован - работаем с табличными данными в БД
            $user_data['name'] = $user->name;
            $user_data['email'] = $user->email;
            $user_data['phone'] = $user->phone;
        }
        $basket = BasketsController::getActualDataOfBasket();
        return compact('user_data', 'basket');
    }

    /**
     * Создаём новый заказ в БД.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $order = new Order();
        $data = $this->validateInputData($request);
        $data['user_id'] = ($user) ? $user->id : 0;
        //$data['name'] = $request->input('name');
        //$data['email'] = $request->input('email');
        //$data['phone'] = $request->input('phone');
        $data['address'] = $request->input('address', '');
        $data['comment'] = $request->input('comment', '');
        $order->fill($data);

        if ($order->save()) {
            $basket = BasketsController::getActualDataOfBasket();
            array_walk($basket, fn($val) => $order->goods()
                ->attach($val['id'], ['price' => $val['price'], 'quantity' => $val['quantity']]));
            //Заказ сделан, корзина больше не нужна - удаляем её
            if ($user) {
                //Пользователь авторизован - работаем с табличными данными в БД
                $user->goodsInBasket()->detach();
            } else {
                //Пользователь не авторизован - работаем с данными о корзине в сессии
                session()->forget('basket');
            }
            flash('Спасибо! Ваш заказ передан в обработку.')->success();
        } else {
            flash('Ошибка данных.')->error();
        }
        return Redirect::to($_SERVER['HTTP_REFERER']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

//Общие функции контроллера-----------------------------------------------------------------

    private function validateInputData(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email:rfc',
            'phone' => 'required|max:10'
        ]);
        return $validated;
    }
}
