<?php

namespace App\Policies;

use App\Models\Goods;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class GoodsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Goods  $goods
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Goods $goods)
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can store models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Goods  $goods
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function store(User $user, Goods $goods)
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can edit the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Goods  $goods
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function edit(User $user, Goods $goods)
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Goods  $goods
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Goods $goods)
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Goods  $goods
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Goods $goods)
    {
        return Auth::check();
    }
}
