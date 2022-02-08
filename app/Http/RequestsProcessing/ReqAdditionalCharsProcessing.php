<?php

namespace App\Http\RequestsProcessing;

use App\Http\Validators\AdditionalCharsIndexValidator;
use App\Models\AdditionalChar;

trait ReqAdditionalCharsProcessing
{
    use Filter;
    use Sorter;

    /**
     * Обработка запроса на список доп характеристик с фильтрацией, сортировкой и разбитием на страницы.
     *
     * @return array
     */
    public function reqProcessingForIndex(): array
    {
        $req = request();

        $validationErrors = (new AdditionalCharsIndexValidator($req))->errors();
        if ($validationErrors) {
            return [[], $validationErrors->first()];
        }

        $filteredData = $this->filtering($req->input('filter'), AdditionalChar::select());
        $sortedData = $this->sorting($req->input('sort'), $filteredData);
        $result = $sortedData->paginate($req->input('perpage') ?? 20)->withQueryString();

        return [$result, []];
    }

    /**
     * Обработка запроса на создание доп характеристики.
     *
     * @return array
     */
    public function reqProcessingForStore(): array
    {
        $req = request();
        $char = new AdditionalChar();
        $data['name'] = $req->name;
        $data['value'] = $req->value ?? '-';
        $char->fill($data);
        if ($char->save()) {
            return [['success' => "Доп характеристика &quot;$char->name&quot; успешно создана. Обновите список."], 200];
        }
        return [['errors' => 'Не удалось создать доп характеристику.'], 400];
    }

    /**
     * Обработка запроса на получение необходимых данных для формы изменения доп характеристики.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForEdit(int $id): array
    {
        $prepareAdditChar = AdditionalChar::findOrFail($id);
        $additChar = $prepareAdditChar->toArray();
        $additChar['created_at'] = $prepareAdditChar->created_at->format('d.m.Y H:i:s');
        $additChar['updated_at'] = $prepareAdditChar->updated_at->format('d.m.Y H:i:s');
        return compact('additChar');
    }

    /**
     * Обработка запроса на изменение доп характеристики.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForUpdate(int $id): array
    {
        $req = request();
        $char = AdditionalChar::find($id);
        $data = [];
        foreach ($req->input() as $row => $val) {
            switch ($row) {
                case 'name':
                    $data['name'] = $val;
                    break;
                case 'value':
                    $data['value'] = $val ?? '-';
                    break;
            }
        }
        $char->fill($data);
        if ($char->save()) {
            return [['success' => "Параметры доп характеристики успешно изменены."], 200];
        }
        return [['errors' => 'Ошибка изменения данных.'], 400];
    }

    /**
     * Обработка запроса на удаление доп характеристики.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForDestroy(int $id): array
    {
        $char = AdditionalChar::find($id);
        if ($char) {
            try {
                $char->goods()->detach();
                $char->delete();
            } catch (\Throwable $e) {
                return [['errors' => 'Не удалось удалить характеристику.'], 400];
            }
            return [['success' => "Характеристика успешно удалена."], 200];
        }
        return [['errors' => "Не удалось найти указанную доп характеристику."], 400];
    }
}
