<?php

namespace App\Http\RequestsProcessing;

use App\Http\Validators\ApiOrdersIndexValidator;
use App\Models\Order;
use Illuminate\Http\Request;

trait ApiReqOrdersProcessing
{
    use Filter;
    use Sorter;

    private Request $req;

    /**
     * Обработка запроса на список заказов с фильтрацией, сортировкой и разбитием на страницы.
     *
     * @return array
     */
    public function reqProcessingForIndex(): array
    {
        $req = request();

        $validated = new ApiOrdersIndexValidator($req);
        if ($validated->errors()) {
            return ['errors' => $validated->errors()];
        }

        $filteredData = $this->filtering($req->input('filter'), Order::select());
        $sortedData = $this->sorting($req->input('sort'), $filteredData);
        $result = $sortedData->paginate($req->input('perpage') ?? 1000);

        return $result->toArray();
    }

    /**
     * Обработка запроса создания заказа.
     *
     * @return array
     */
    public function reqProcessingForStore(): array
    {
        $req = request();
        $order = new Order();
        $user = $req->user();
        $basket = ($user->basket()) ?: [];
        if (!$basket) {
            return ['errors' => 'Ошибка создания заказа. Корзина пользователя пуста.'];
        }
        $data['name'] = $req->input('name');
        $data['email'] = $req->input('email');
        $data['phone'] = $req->input('phone');
        $data['user_id'] = $user->id;
        $data['address'] = $req->input('address', '');
        $data['comment'] = $req->input('comment', '');
        $order->fill($data);
        if ($order->save()) {
            array_walk($basket, static fn($item) => $order->goods()
                ->attach($item['id'], ['price' => $item['price'], 'quantity' => $item['quantity']]));
            //Заказ сделан, корзина больше не нужна - удаляем её
            $user->goodsInBasket()->detach();
        } else {
            return ['errors' => 'Не удалось создать заказ.'];
        }

        return $order->toArray();
    }
}
