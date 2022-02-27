<?php

namespace App\Http\RequestsProcessing;

use App\Http\Validators\MovementsIndexValidator;
use App\Models\Movement;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

trait ReqMovementsProcessing
{
    use Filter;
    use Sorter;

    /**
     * Обработка запроса на список заказов с фильтрацией, сортировкой и разбитием на страницы.
     *
     * @return array
     */
    public function reqProcessingForIndex(): array
    {
        $req = request();

        $validationErrors = (new MovementsIndexValidator($req))->errors();
        if ($validationErrors) {
            return [[], $validationErrors->first()];
        }

        $filteredData = $this->filtering($req->input('filter'), Movement::select());
        $sortedData = $this->sorting($req->input('sort'), $filteredData);
        $result = $sortedData->paginate($req->input('perpage') ?? 20)->withQueryString();

        return [$result, []];
    }

    /**
     * Обработка запроса на получение необходимых данных для формы создания нового заказа.
     *
     * @return array
     */
    public function reqProcessingForCreate(): array
    {
        $user_data = [];
        $user = Auth::user();
        if ($user) {
            //Если пользователь авторизован - работаем с табличными данными в БД
            $user_data['name'] = $user->name;
            $user_data['email'] = $user->email;
            $user_data['phone'] = $user->phone;
        }
        $basket = Basket::getActualDataOfBasket();
        return compact('user_data', 'basket');
    }

    /**
     * Обработка запроса создания заказа.
     *
     * @return bool
     */
    public function reqProcessingForStore(): bool
    {
        $basket = Basket::getActualDataOfBasket();
        if ($basket) {
            $req = request();
            $user = Auth::user();
            $order = new Order();
            $data['name'] = $req->name;
            $data['email'] = $req->email;
            $data['phone'] = $req->phone;
            $data['user_id'] = ($user) ? $user->id : 0;
            $data['address'] = $req->address ?? '-';
            $data['comment'] = $req->comment ?? '-';
            $order->fill($data);
            if ($order->save()) {
                array_walk($basket, static fn($item) => $order->goods()
                    ->attach($item['id'], ['price' => $item['price'], 'quantity' => $item['quantity']]));
                Basket::purgeBasket();//Заказ сделан, корзина больше не нужна - удаляем её
                return true;
            }
        }
        return false;
    }
}
