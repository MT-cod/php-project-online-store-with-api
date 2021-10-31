<?php

namespace App\Http\Controllers;

use App\Models\AdditionalChar;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class AdditionalCharsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $additCharsSelect = AdditionalChar::select('id', 'name', 'value');
        if (isset($_REQUEST['filter']['name']) && ($_REQUEST['filter']['name'] !== '')) {
            $additCharsSelect->where('name', 'like', '%' . $_REQUEST['filter']['name'] . '%');
        }
        $additChars = $additCharsSelect->with('goods:name')->orderBy('name')->get()->toArray();
        return view('additionalChar.index', compact('additChars'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $additChar = new AdditionalChar();
        $data = $this->validateName($request, $additChar);
        $data['value'] = $request->input('value', '');
        $additChar->fill($data);
        if ($additChar->save()) {
            return Response::json(['success' => "Характеристика &quot;$additChar->name&quot; успешно создана"], 200);
        }
        return Response::json(['error' => 'Ошибка данных'], 422);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return array
     */
    public function edit($id)
    {
        $prepare_additChar = AdditionalChar::findOrFail($id);
        $additChar = $prepare_additChar->toArray();
        $additChar['created_at'] = $prepare_additChar->created_at->format('d.m.Y H:i:s');
        $additChar['updated_at'] = $prepare_additChar->updated_at->format('d.m.Y H:i:s');
        return compact('additChar');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $additChar = AdditionalChar::findOrFail($id);
        $data = $this->validateName($request, $additChar);
        $data['value'] = $request->input('value', '');
        $additChar->fill($data);
        if ($additChar->save()) {
            return Response::json(['success' => 'Параметры характеристики успешно изменены'], 200);
        }
        return Response::json(['error' => 'Ошибка изменения данных'], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id)
    {
        $additChar = AdditionalChar::findOrFail($id);
        try {
            $additChar->goods()->detach();
            $additChar->delete();
            flash("Характеристика &quot;$additChar->name&quot; успешно удалена")->success();
        } catch (\Exception $e) {
            flash('Не удалось удалить характеристику')->error();
        } finally {
            return Redirect::to($_SERVER['HTTP_REFERER']);
        }
    }

//Общие функции контроллера-----------------------------------------------------------------

    private function validateName(Request $request, AdditionalChar $additChars): array
    {
        return $this->validate($request, [
            'name' => [
                'required',
                function ($attribute, $value, $fail) use ($additChars): void {
                    if ((AdditionalChar::where($attribute, $value)->first() !== null) && ($value !== $additChars->name)) {
                        $fail('Доп характеристика с таким именем уже существует');
                    }
                }]
        ]);
    }
}
