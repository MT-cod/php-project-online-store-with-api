<?php

namespace App\Http\RequestsProcessing;

use App\Models\Category;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait ApiReqOrdersProcessing
{
    private Request $req;

    /**
     * Обработка запроса на список заказов с фильтрацией, сортировкой и разбитием на страницы.
     *
     * @return array
     */
    public function reqProcessingForIndex(): array
    {
        $this->req = request();
        $orders = Order::select();
    //отфильтруем
        if ($this->req->input('filter')) {
            if ($this->validateFilter()) {
                return ['errors' => $this->validateFilter()];
            }
            $filteredOrders = $this->filtering($orders);
        } else {
            $filteredOrders = $orders;
        }
    //отсортируем
        if ($this->req->input('sort')) {
            if ($this->validateSort()) {
                return ['errors' => $this->validateSort()];
            }
            $sortedOrders = $this->sorting($filteredOrders);
        } else {
            $sortedOrders = $filteredOrders;
        }
    //разобьём результат на страницы
        if ($this->req->input('perpage')) {
            if ($this->validatePerpage()) {
                return ['errors' => $this->validatePerpage()];
            }
            $result = $sortedOrders->paginate($this->req->input('perpage'));
        } else {
            $result = $sortedOrders->get();
        }

        return $result->toArray();
    }

    /**
     * Обработка запроса создания заказа.
     *
     * @return array
     */
    public function reqProcessingForStore(): array
    {
        $this->req = request();
        $order = new Order();
        $user = $this->req->user();
        $basket = ($user->basket()) ?: [];
        if (!$basket) {
            return ['errors' => 'Ошибка создания заказа. Корзина пользователя пуста.'];
        }
        $data['name'] = $this->req->input('name');
        $data['email'] = $this->req->input('email');
        $data['phone'] = $this->req->input('phone');
        $data['user_id'] = $user->id;
        $data['address'] = $this->req->input('address', '');
        $data['comment'] = $this->req->input('comment', '');
        $order->fill($data);
        if ($order->save()) {
            //$basket = BasketsController::getActualDataOfBasket();
            array_walk($basket, fn($item) => $order->goods()
                ->attach($item['id'], ['price' => $item['price'], 'quantity' => $item['quantity']]));
            //Заказ сделан, корзина больше не нужна - удаляем её
            $user->goodsInBasket()->detach();
        } else {
            return ['errors' => 'Не удалось создать заказ.'];
        }

        return $order->toArray();
    }


//-----------------------------------------------------------------------------------------
    private function validateFilter(): array
    {
        $validator = Validator::make($this->req->all(), [
            'filter.create_min' => ['nullable', 'date'],
            'filter.create_max' => ['nullable', 'date'],
            'filter.update_min' => ['nullable', 'date'],
            'filter.update_max' => ['nullable', 'date'],
            'filter.name' => ['nullable', 'string', 'max:255'],
            'filter.email' => ['nullable', 'email', 'max:255'],
            'filter.phone' => ['nullable', 'string', 'max:255'],
            'filter.address' => ['nullable', 'string', 'max:1000'],
            'filter.completed' => ['nullable', 'boolean']
        ]);
        return ($validator->fails()) ? $validator->errors()->all() : [];
    }

    private function filtering(Builder $data): Builder
    {
        if ($this->req->input('filter.create_min')) {
            $data->whereDate('created_at', '>=', $this->req->input('filter.create_min'));
        }
        if ($this->req->input('filter.create_max')) {
            $data->whereDate('created_at', '<=', $this->req->input('filter.create_max'));
        }
        if ($this->req->input('filter.update_min')) {
            $data->whereDate('updated_at', '>=', $this->req->input('filter.update_min'));
        }
        if ($this->req->input('filter.update_max')) {
            $data->whereDate('updated_at', '<=', $this->req->input('filter.update_max'));
        }
        if ($this->req->input('filter.name')) {
            $data->where('name', 'like', '%' . $this->req->input('filter.name') . '%');
        }
        if ($this->req->input('filter.email')) {
            $data->where('email', 'like', '%' . $this->req->input('filter.email') . '%');
        }
        if ($this->req->input('filter.phone')) {
            $data->where('phone', 'like', '%' . $this->req->input('filter.phone') . '%');
        }
        if ($this->req->input('filter.address')) {
            $data->where('address', 'like', '%' . $this->req->input('filter.address') . '%');
        }
        $completed = $this->req->input('filter.completed');
        if ($completed !== null) {
            $data->where('completed', $completed);
        }
        return $data;
    }

    private function validateSort(): array
    {
        $validator = Validator::make($this->req->all(), [
            'sort.*' => ['nullable', 'string', 'max:255', Rule::in(['asc', 'desc'])]
        ]);
        return ($validator->fails()) ? $validator->errors()->all() : [];
    }

    private function sorting(Builder $data): Builder
    {
        $sortColumns = ['created_at', 'updated_at', 'name', 'email', 'phone'];
        foreach ($sortColumns as $column) {
            if ($this->req->input('sort.' . $column)) {
                $data->orderBy($column, $this->req->input('sort.' . $column));
            }
        }
        return $data;
    }

    private function validatePerpage(): array
    {
        $validator = Validator::make($this->req->all(), ['perpage' => ['nullable', 'integer']]);
        return ($validator->fails()) ? $validator->errors()->all() : [];
    }
}
