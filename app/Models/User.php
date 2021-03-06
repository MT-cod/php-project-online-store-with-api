<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function goodsInBasket(): BelongsToMany
    {
        return $this
            ->belongsToMany(Goods::class, 'baskets', 'user_id', 'goods_id')
            ->withPivot('quantity');
    }

    public function basket(): array
    {
        $allBasket = $this->goodsInBasket()->get() ?? [];
        foreach ($allBasket as $item) {
            $basket[$item->pivot->goods_id] = [
                'id' => $item->pivot->goods_id,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $item->pivot->quantity
            ];
        }
        return $basket ?? [];
    }

    public function basketForApi(): array
    {
        $allBasket = $this->goodsInBasket()->orderBy('name')->get() ?? [];
        foreach ($allBasket as $item) {
            $basket[] = [
                'id' => $item->pivot->goods_id,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $item->pivot->quantity
            ];
        }
        return $basket ?? [];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
