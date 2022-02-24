<?php

namespace App\Http\Controllers;

use App\Http\RequestsProcessing\ReqShopProcessing;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ShopController extends Controller
{
    use ReqShopProcessing;

    public function index(): Application|Factory|View|RedirectResponse
    {
        $result = $this->reqProcessingForShopIndex();

        if ($result['errors']) {
            flash($result['errors'])->error()->important();
            $_REQUEST = ['filter_expand' => "1"];
        }

        return view('shop.index', $result);
    }
}
