<?php

namespace App\Http\RequestsProcessing\Api;

use App\Http\RequestsProcessing\Filter;
use App\Http\RequestsProcessing\Sorter;
use App\Http\Validators\Api\ApiOrdersIndexValidator;
use App\Models\Order;

trait ApiReqOrdersProcessing
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

        $validationErrors = (new ApiOrdersIndexValidator($req))->errors();
        if ($validationErrors) {
            return ['errors' => $validationErrors, 'status' => 400];
        }

        $filteredData = $this->filtering($req->input('filter'), Order::select());
        $sortedData = $this->sorting($req->input('sort'), $filteredData);
        $result = $sortedData->paginate($req->input('perpage') ?? 1000);

        return ['success' => 'Список заказов успешно получен.', 'data' => $result->toArray(), 'status' => 200];
    }

    /**
     * Обработка запроса на получение списка заказов авторизированного пользователя.
     *
     * @return array
     */
    public function reqProcessingForOwnOrders(): array
    {
        $req = request();

        $validationErrors = (new ApiOrdersIndexValidator($req))->errors();
        if ($validationErrors) {
            return ['errors' => $validationErrors, 'status' => 400];
        }

        $filteredData = $this->filtering($req->input('filter'), Order::where('user_id', $req->user()->id));
        $sortedData = $this->sorting($req->input('sort'), $filteredData);
        $result = $sortedData->paginate($req->input('perpage') ?? 1000);

        return [
            'success' => 'Список заказов пользователя успешно получен.',
            'data' => $result->toArray(),
            'status' => 200
        ];
    }

    /**
     * Обработка запроса на получение данных заказа с товарами.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForShow(int $id): array
    {
        $order = Order::find($id);
        if ($order) {
            $data = $order->toArray();
            $data['basket'] = $order->goods()->get()->toArray();
            return ['success' => 'Заказ успешно получен.', 'data' => $data, 'status' => 200];
        }
        return ['errors' => "Не удалось найти заказ id:$id.", 'status' => 400];
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
            return ['errors' => 'Ошибка создания заказа. Корзина пользователя пуста.', 'status' => 400];
        }
        $data['name'] = $req->input('name');
        $data['email'] = $req->input('email');
        $data['phone'] = $req->input('phone');
        $data['user_id'] = $user->id;
        $data['address'] = $req->address ?? '-';
        $data['comment'] = $req->comment ?? '-';
        $order->fill($data);
        if ($order->save()) {
            array_walk($basket, static fn($item) => $order->goods()
                ->attach($item['id'], ['price' => $item['price'], 'quantity' => $item['quantity']]));
            //Заказ сделан, корзина больше не нужна - удаляем её
            $user->goodsInBasket()->detach();
            return ['success' => 'Заказ успешно создан.', 'data' => $order->toArray(), 'status' => 201];
        }
        return ['errors' => 'Не удалось создать заказ.', 'status' => 500];
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
        $order->completed = $req->input('completed');
        if ($order->save()) {
            return ['success' => 'Заказ успешно обновлен.', 'data' => $order->toArray(), 'status' => 200];
        }
        return ['errors' => "Не удалось обновить заказ id:$id.", 'status' => 500];
    }
}
