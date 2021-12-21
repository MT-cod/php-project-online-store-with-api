<?php

namespace App\Http\Controllers;

use App\Http\RequestsProcessing\ReqCategoriesProcessing;
use App\Http\Validators\CategoriesStoreValidator;
use App\Http\Validators\CategoriesUpdateValidator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class CategoriesController extends Controller
{
    use ReqCategoriesProcessing;

    public function index(): View|Factory|Application
    {
        return view('category.index', $this->reqProcessingForIndex());
    }

    public function create(): array
    {
        return $this->reqProcessingForCreate();
    }

    public function store(CategoriesStoreValidator $req): JsonResponse
    {
        $validationErrors = $req->errors();
        if ($validationErrors) {
            return Response::json(['errors' => $validationErrors], 400);
        }

        [$result, $status] = $this->reqProcessingForStore();

        return Response::json($result, $status);
    }

    public function edit(int $id): array
    {
        return $this->reqProcessingForEdit($id);
    }

    public function update(CategoriesUpdateValidator $req, int $id): JsonResponse
    {
        $validationErrors = $req->errors();
        if ($validationErrors) {
            return Response::json(['errors' => $validationErrors], 400);
        }

        [$result, $status] = $this->reqProcessingForUpdate($id);

        return Response::json($result, $status);
    }

    public function destroy(int $id): RedirectResponse
    {
        [$result, $status] = $this->reqProcessingForDestroy($id);

        if (isset($result['errors'])) {
            flash($result['errors'])->error();
        }
        if (isset($result['success'])) {
            flash($result['success'])->success();
        }

        return Redirect::to($_SERVER['HTTP_REFERER']);
    }
}
