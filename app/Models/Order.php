<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'email', 'phone', 'address', 'comment', 'updated_at'];

    public function goods(): BelongsToMany
    {
        return $this->belongsToMany(
            Goods::class,
            'orders_goods',
            'order_id',
            'goods_id'
        )
            ->withPivot('price', 'quantity');
    }
}
