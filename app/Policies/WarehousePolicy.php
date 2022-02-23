<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class WarehousePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can store models.
     *
     * @param User $user
     * @param Warehouse $warehouse
     * @return bool
     */
    public function store(User $user, Warehouse $warehouse): bool
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can edit the model.
     *
     * @param User $user
     * @param Warehouse $warehouse
     * @return bool
     */
    public function edit(User $user, Warehouse $warehouse): bool
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Warehouse $warehouse
     * @return bool
     */
    public function update(User $user, Warehouse $warehouse): bool
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Warehouse $warehouse
     * @return bool
     */
    public function delete(User $user, Warehouse $warehouse): bool
    {
        return Auth::check();
    }
}
