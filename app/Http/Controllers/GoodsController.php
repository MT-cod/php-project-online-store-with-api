<?php

namespace App\Http\Controllers;

use App\Http\RequestsProcessing\ReqGoodsProcessing;
use App\Http\Validators\GoodsStoreValidator;
use App\Http\Validators\GoodsUpdateValidator;
use App\Models\AdditionalChar;
use App\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class GoodsController extends Controller
{
    use ReqGoodsProcessing;

    public function index(): Factory|View|Application
    {
        [$goods, $errors] = $this->reqProcessingForGoodsIndex();

        if ($errors) {
            flash($errors)->error();
            $_REQUEST = ['filter_expand' => "1"];
        }

        $categories = Category::categsForSelectsWithMarkers();
        $additCharacteristics = AdditionalChar::additCharsForFilters();

        return view('goods.index', compact('goods', 'categories', 'additCharacteristics'));
    }

    public function create(): array
    {
        return $this->reqProcessingForGoodsCreate();
    }

    public function store(GoodsStoreValidator $req): JsonResponse|RedirectResponse
    {
        $validationErrors = $req->errors();
        if ($validationErrors) {
            return Response::json(['errors' => $validationErrors], 400);
        }

        [$result, $status] = $this->reqProcessingForGoodsStore();

        if (isset($result['success'])) {
            flash($result['success'])->success();
        }

        return Response::json($result, $status);
    }

    public function show(int $id): array
    {
        return $this->reqProcessingForGoodsShow($id);
    }

    public function edit(int $id): array
    {
        $item = $this->reqProcessingForGoodsEdit($id);
        $categories = Category::categsForSelectsWithMarkers();
        $additCharacteristics = AdditionalChar::additCharsForFilters();

        return compact('item', 'categories', 'additCharacteristics');
    }

    public function update(GoodsUpdateValidator $req, int $id): JsonResponse
    {
        $validationErrors = $req->errors();
        if ($validationErrors) {
            return Response::json(['errors' => $validationErrors], 400);
        }

        [$result, $status] = $this->reqProcessingForGoodsUpdate($id);

        return Response::json($result, $status);
    }

    public function destroy(int $id): RedirectResponse
    {
        [$result, $status] = $this->reqProcessingForGoodsDestroy($id);

        if (isset($result['errors'])) {
            flash($result['errors'])->error();
        }
        if (isset($result['success'])) {
            flash($result['success'])->success();
        }

        return Redirect::to($_SERVER['HTTP_REFERER']);
    }
}
