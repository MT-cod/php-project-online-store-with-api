<?php

namespace App\Http\RequestsProcessing\Api;

use App\Http\RequestsProcessing\Filter;
use App\Http\RequestsProcessing\Sorter;
use App\Http\Validators\Api\ApiAdditionalCharsIndexValidator;
use App\Models\AdditionalChar;

trait ApiReqAdditionalCharsProcessing
{
    use Filter;
    use Sorter;

    /**
     * Обработка запроса на список категорий с фильтрацией, сортировкой и разбитием на страницы.
     *
     * @return array
     */
    public function reqProcessingForIndex(): array
    {
        $req = request();

        $validated = new ApiAdditionalCharsIndexValidator($req);
        if ($validated->errors()) {
            return ['errors' => $validated->errors(), 'status' => 400];
        }

        $filteredData = $this->filtering($req->input('filter'), AdditionalChar::select());
        $sortedData = $this->sorting($req->input('sort'), $filteredData);
        $result = $sortedData->paginate($req->input('perpage') ?? 1000);

        return [
            'success' => 'Список доп характеристик успешно получен.',
            'data' => $result->toArray(),
            'status' => 200
        ];
    }

    /**
     * Обработка запроса на получение категории по id.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForShow(int $id): array
    {
        $char = AdditionalChar::find($id);
        if ($char) {
            return ['success' => 'Доп характеристика успешно получена.', 'data' => $char, 'status' => 200];
        }
        return ['errors' => "Не удалось найти доп характеристику с id:$id.", 'status' => 400];
    }

    /**
     * Обработка запроса на создание категории.
     *
     * @return array
     */
    public function reqProcessingForStore(): array
    {
        $req = request();
        $char = new AdditionalChar();
        $data['name'] = $req->input('name');
        $data['value'] = $req->input('value', '');
        $char->fill($data);
        if ($char->save()) {
            return ['success' => "Доп характеристика успешно создана.", 'data' => $char, 'status' => 200];
        }
        return ['errors' => 'Не удалось создать доп характеристику.', 'status' => 400];
    }

    /**
     * Обработка запроса на изменение категории.
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
                    $data['value'] = $val;
                    break;
            }
        }
        $char->fill($data);
        if ($char->save()) {
            return ['success' => "Параметры доп характеристики успешно изменены.", 'data' => $char, 'status' => 200];
        }
        return ['errors' => 'Ошибка изменения данных.', 'status' => 400];
    }

    /**
     * Обработка запроса на удаление категории.
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
                return ['errors' => 'Не удалось удалить характеристику.', 'status' => 400];
            }
            return ['success' => "Характеристика $char->name успешно удалена.", 'status' => 200];
        }
        return ['errors' => "Не удалось найти доп характеристику с id:$id", 'status' => 400];
    }
}
