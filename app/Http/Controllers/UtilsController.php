<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;

class UtilsController extends Controller
{
    public function regenerateDb(): RedirectResponse
    {
        try {
            session()->flush();
            Artisan::call('migrate:fresh --seed --force');
        } catch (\Throwable $e) {
            flash('При перегенерации возникло исключение. Запустите перегенерацию заново!')->error()->important();
            return Redirect::to('/');
        }
        return Redirect::to('/');
    }
}
