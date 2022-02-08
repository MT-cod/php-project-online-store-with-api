<?php

namespace App\Http\RequestsProcessing;

use App\Http\Validators\OrdersIndexValidator;
use App\Models\Basket;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

trait ReqOrdersProcessing
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

        $validationErrors = (new OrdersIndexValidator($req))->errors();
        if ($validationErrors) {
            return [[], $validationErrors->first()];
        }

        $filteredData = $this->filtering($req->input('filter'), Order::select());
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

    /**
     * Обработка запроса на получение необходимых данных для формы изменения заказа.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForEdit(int $id): array
    {
        $order = Order::findOrFail($id);
        $data = $order->toArray();
        $data['created_at'] = $order->created_at->format('d.m.Y H:i:s');
        $data['updated_at'] = $order->updated_at->format('d.m.Y H:i:s');
        $data['basket'] = $order->goods()->get()->toArray();
        return $data;
    }

    /**
     * Обработка запроса на изменение заказа.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForUpdate(int $id): array
    {
        $req = request();
        $order = Order::find($id);
        $order->completed = $req->completed;
        if ($order->save()) {
            return ($req->completed)
                ? [['success' => "Заказ №$id завершён."], 200]
                : [['success' => "Заказ №$id снова в обработке."], 500];
        }
        return [['errors' => "Не удалось обновить заказ."], 500];
    }
}
