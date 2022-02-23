<?php

namespace App\Http\Controllers;

use App\Http\RequestsProcessing\ReqWarehousesProcessing;
use App\Models\Warehouse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class WarehousesController extends Controller
{
    use ReqWarehousesProcessing;

    public function index(): View|Factory|Application
    {
        return view('warehouse.index', ['warehouses' => Warehouse::all()]);
    }

    /*public function store(AdditionalCharsStoreValidator $req): JsonResponse
    {
        $validationErrors = $req->errors();
        if ($validationErrors) {
            return Response::json(['errors' => $validationErrors], 400);
        }

        [$result, $status] = $this->reqProcessingForStore();

        if (isset($result['success'])) {
            flash($result['success'])->success();
        }

        return Response::json($result, $status);
    }*/

    /*public function edit(int $id): array
    {
        return $this->reqProcessingForEdit($id);
    }*/

    /*public function update(AdditionalCharsUpdateValidator $req, $id): JsonResponse
    {
        $validationErrors = $req->errors();
        if ($validationErrors) {
            return Response::json(['errors' => $validationErrors], 400);
        }

        [$result, $status] = $this->reqProcessingForUpdate($id);

        if (isset($result['success'])) {
            flash($result['success'])->success();
        }

        return Response::json($result, $status);
    }*/

    /*public function destroy(int $id): RedirectResponse
    {
        [$result, $status] = $this->reqProcessingForDestroy($id);

        if (isset($result['errors'])) {
            flash($result['errors'])->error();
        }
        if (isset($result['success'])) {
            flash($result['success'])->success();
        }

        return Redirect::to($_SERVER['HTTP_REFERER']);
    }*/
}
