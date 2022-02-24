<?php

namespace App\Http\RequestsProcessing;

use App\Models\AdditionalChar;
use App\Models\Warehouse;
use Illuminate\Auth\Access\AuthorizationException;

trait ReqWarehousesProcessing
{
    use Filter;
    use Sorter;

    /**
     * Обработка запроса на создание склада.
     *
     * @return array
     */
    public function reqProcessingForStore(): array
    {
        try {
            $req = request();
            $warehouse = new Warehouse();
            $this->authorize('store', $warehouse);
            $data['name'] = $req->name;
            $data['description'] = $req->description ?? '-';
            $data['address'] = $req->address ?? '-';
            $data['priority'] = $req->priority;
            $warehouse->fill($data);
            if ($warehouse->save()) {
                return [[
                    'success' => "Склад &quot;$warehouse->name&quot; успешно создан.",
                    'referer' => $_SERVER['HTTP_REFERER']
                ], 200];
            }
            return [['errors' => 'Не удалось создать склад.'], 400];
        } catch (\Throwable $e) {
            return [['errors' => 'Не удалось создать склад.'], 400];
        }
    }

    /**
     * Обработка запроса на получение необходимых данных для формы изменения склада.
     *
     * @param int $id
     * @return array
     * @throws AuthorizationException
     */
    public function reqProcessingForEdit(int $id): array
    {
        $prepareWarehouse = Warehouse::findOrFail($id);
        $this->authorize('edit', $prepareWarehouse);
        $warehouse = $prepareWarehouse->toArray();
        $warehouse['created_at'] = $prepareWarehouse->created_at->format('d.m.Y H:i:s');
        $warehouse['updated_at'] = $prepareWarehouse->updated_at->format('d.m.Y H:i:s');
        return compact('warehouse');
    }

    /**
     * Обработка запроса на изменение склада.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForUpdate(int $id): array
    {
        try {
            $req = request();
            $warehouse = Warehouse::find($id);
            $this->authorize('edit', $warehouse);
            $data = [];
            foreach ($req->input() as $row => $val) {
                switch ($row) {
                    case 'name':
                        $data['name'] = $val;
                        break;
                    case 'description':
                        $data['description'] = $val ?? '-';
                        break;
                    case 'address':
                        $data['address'] = $val ?? '-';
                        break;
                    case 'priority':
                        $data['priority'] = $val;
                        break;
                }
            }
            $warehouse->fill($data);
            if ($warehouse->save()) {
                return [[
                    'success' => "Параметры склада &quot;$warehouse->name&quot; успешно изменены.",
                    'referer' => $_SERVER['HTTP_REFERER']
                ], 200];
            }
            return [['errors' => 'Ошибка изменения данных.'], 400];
        } catch (\Throwable $e) {
            return [['errors' => 'Ошибка изменения данных.'], 400];
        }
    }

    /**
     * Обработка запроса на удаление склада.
     *
     * @param int $id
     * @return array
     */
    public function reqProcessingForDestroy(int $id): array
    {
        $warehouse = Warehouse::find($id);
        if ($warehouse) {
            try {
                $warehouse->delete();
            } catch (\Throwable $e) {
                return [['errors' => "Не удалось удалить склад &quot;$warehouse->name&quot;."], 400];
            }
            return [['success' => "Склад &quot;$warehouse->name&quot; успешно удалён."], 200];
        }
        return [['errors' => "Не удалось найти указанный склад."], 400];
    }
}
