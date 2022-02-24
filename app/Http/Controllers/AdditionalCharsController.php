<?php

namespace App\Http\Controllers;

use App\Http\RequestsProcessing\ReqAdditionalCharsProcessing;
use App\Http\Validators\AdditionalCharsStoreValidator;
use App\Http\Validators\AdditionalCharsUpdateValidator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class AdditionalCharsController extends Controller
{
    use ReqAdditionalCharsProcessing;

    public function index(): View|Factory|Application
    {
        [$additChars, $errors] = $this->reqProcessingForIndex();

        if ($errors) {
            flash($errors)->error();
            $_REQUEST = ['filter_expand' => "1"];
        }

        return view('additionalChar.index', compact('additChars'));
    }

    public function store(AdditionalCharsStoreValidator $req): JsonResponse
    {
        $validationErrors = $req->errors();
        if ($validationErrors) {
            return Response::json(['errors' => $validationErrors], 400);
        }

        [$result, $status] = $this->reqProcessingForStore();

        if (isset($result['success'])) {
            flash($result['success'])->success()->important();
        }

        return Response::json($result, $status);
    }

    public function edit(int $id): array
    {
        return $this->reqProcessingForEdit($id);
    }

    public function update(AdditionalCharsUpdateValidator $req, $id): JsonResponse
    {
        $validationErrors = $req->errors();
        if ($validationErrors) {
            return Response::json(['errors' => $validationErrors], 400);
        }

        [$result, $status] = $this->reqProcessingForUpdate($id);

        if (isset($result['success'])) {
            flash($result['success'])->success()->important();
        }

        return Response::json($result, $status);
    }

    public function destroy(int $id): RedirectResponse
    {
        [$result, $status] = $this->reqProcessingForDestroy($id);

        if (isset($result['errors'])) {
            flash($result['errors'])->error();
        }
        if (isset($result['success'])) {
            flash($result['success'])->success()->important();
        }

        return Redirect::to($_SERVER['HTTP_REFERER']);
    }
}
