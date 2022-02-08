<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;

class UtilsController extends Controller
{
    public function regenerateDb(): RedirectResponse
    {
        session()->flush();
        Artisan::call('migrate:fresh --seed');
        return Redirect::to('/');
    }
}
