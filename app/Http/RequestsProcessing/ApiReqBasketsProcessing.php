<?php

namespace App\Http\RequestsProcessing;

use Illuminate\Http\Request;

trait ApiReqBasketsProcessing
{
    /**
     * Обработка запроса на добавление позиции.
     *
     * @param Request $request
     * @return array
     */
    public function reqProcessingForStore(Request $request): array
    {
        if ($request->input('basket')) {
            $basket = array_map(fn($qua) => ['quantity' => $qua], $request->input('basket'));
            $request->user()->goodsInBasket()->sync($basket);
        }
        return $request->user()->basketForApi();
    }
}
