<?php

namespace App\Http\Controllers;

use App\Http\RequestsProcessing\ReqMovementsProcessing;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class MovementsController extends Controller
{
    use ReqMovementsProcessing;

    public function index(): Factory|View|Application
    {
        [$movements, $errors] = $this->reqProcessingForIndex();

        if ($errors) {
            flash($errors)->error();
            $_REQUEST = ['filter_expand' => "1"];
        }

        return view('movement.index', compact('movements'));
    }

    /*public function create(): array
    {
        return $this->reqProcessingForCreate();
    }

    public function store(OrdersStoreValidator $req): RedirectResponse
    {
        $validationErrors = $req->errors();
        if ($validationErrors) {
            flash($validationErrors)->error();
        } else if ($this->reqProcessingForStore()) {
            flash('Спасибо! Ваш заказ передан в обработку.')->success()->important();
        } else {
            flash('Не удалось создать заказ.')->error()->important();
        }

        return Redirect::to($_SERVER['HTTP_REFERER']);
    }*/
}
