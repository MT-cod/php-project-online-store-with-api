<?php

namespace App\Http\Controllers;

use App\Http\RequestsProcessing\ReqOrdersProcessing;
use App\Http\Validators\OrdersStoreValidator;
use App\Http\Validators\OrdersUpdateValidator;
use App\Models\Basket;
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
    use ReqOrdersProcessing;

    public function index(): Factory|View|Application
    {
        [$orders, $errors] = $this->reqProcessingForIndex();

        if ($errors) {
            flash($errors)->error()->important();
            $_REQUEST = ['filter_expand' => "1"];
        }

        return view('order.index', compact('orders'));
    }

    public function create(): array
    {
        return $this->reqProcessingForCreate();
    }

    public function store(OrdersStoreValidator $req): RedirectResponse
    {
        $validationErrors = $req->errors();
        if ($validationErrors) {
            flash($validationErrors)->error()->important();
        } elseif ($this->reqProcessingForStore()) {
            flash('Спасибо! Ваш заказ передан в обработку.')->success()->important();
        } else {
            flash('Не удалось создать заказ.')->error()->important();
        }

        return Redirect::to($_SERVER['HTTP_REFERER']);
    }

    public function edit(int $id): array
    {
        return $this->reqProcessingForEdit($id);
    }

    public function update(OrdersUpdateValidator $req, $id): RedirectResponse
    {
        $validationErrors = $req->errors();
        if ($validationErrors) {
            flash($validationErrors->first())->error()->important();
        } else {
            [$result, $status] = $this->reqProcessingForUpdate($id);

            if (isset($result['errors'])) {
                flash($result['errors'])->error()->important();
            }
            if (isset($result['success'])) {
                flash($result['success'])->success()->important();
            }
        }

        return Redirect::to($_SERVER['HTTP_REFERER']);
    }
}
