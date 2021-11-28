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
        $allOrders = Order::select('id', 'created_at', 'name', 'email', 'phone', 'completed');
        if (isset($_REQUEST['filter']['name']) && ($_REQUEST['filter']['name'] !== '')) {
            $allOrders->where('name', 'like', '%' . $_REQUEST['filter']['name'] . '%');
        }
        foreach ($allOrders->get() as $order) {
            $resOrder = $order->toArray();
            $resOrder['created_at'] = $order->created_at->format('d.m.Y H:i:s');
            $orders[] = $resOrder;
        }
        /*$orders = $allOrders->orderByDesc('id')->get()->toArray();*/
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
     * Создаём новый заказ из корзины.
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
        $data['address'] = $request->input('address', '');
        $data['comment'] = $request->input('comment', '');
        $order->fill($data);

        if ($order->save()) {
            $basket = BasketsController::getActualDataOfBasket();
            array_walk($basket, fn($item) => $order->goods()
                ->attach($item['id'], ['price' => $item['price'], 'quantity' => $item['quantity']]));
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
     * Передаём данные для формы работы с заказом.
     *
     * @param int $id
     * @return array
     */
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $data = $order->toArray();
        $data['created_at'] = $order->created_at->format('d.m.Y H:i:s');
        $data['updated_at'] = $order->updated_at->format('d.m.Y H:i:s');
        $data['basket'] = $order->goods()->get()->toArray();
        return $data;
    }

    /**
     * Изменяем заказ.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $flash = '';
        if (
            $request->input('completed') !== null &&
            ($request->input('completed') == 1 || $request->input('completed') == 0)
        ) {
            $order->completed = (int) $request->input('completed');
            $flash = ($request->input('completed')) ? "Заказ №$id завершён." : "Заказ №$id снова в обработке.";
        }
        if ($order->save()) {
            flash($flash)->success();
        } else {
            flash('Ошибка данных.')->error();
        }
        return Redirect::to($_SERVER['HTTP_REFERER']);
    }

//Общие функции контроллера-----------------------------------------------------------------

    private function validateInputData(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email:rfc|max:255',
            'phone' => 'required|max:20'
        ]);
        return $validated;
    }
}
