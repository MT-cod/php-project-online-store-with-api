<?php

namespace App\Http\RequestsProcessing;

use Illuminate\Database\Eloquent\Builder;

trait Sorter
{
    private function sorting(array|null $reqSorters, Builder $data): Builder
    {
        if ($reqSorters) {
            $notNullSorts = array_filter($reqSorters, static fn($val) => !is_null($val));
            array_walk($notNullSorts, static fn($val, $column) => $data->orderBy($column, $notNullSorts[$column]));
        }

        return $data;
    }
}
