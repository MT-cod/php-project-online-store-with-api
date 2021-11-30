<?php

namespace App\Http\RequestsProcessing;

use Illuminate\Database\Eloquent\Builder;

trait Sorter
{
    private function sorting(array|null $reqSorters, Builder $data): Builder
    {
        if ($reqSorters) {
            array_walk($reqSorters, static fn($val, $column) => $data->orderBy($column, $reqSorters[$column]));
        }

        return $data;
    }
}
