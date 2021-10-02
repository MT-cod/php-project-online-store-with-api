<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Category $cat
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Category $cat)
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can store models.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Category $cat
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function store(User $user, Category $cat)
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can edit the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Category $cat
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function edit(User $user, Category $cat)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Category $cat
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Category $cat)
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Category $cat
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Category $cat)
    {
        return Auth::check();
    }
}
