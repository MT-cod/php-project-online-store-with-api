<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'movement_type', 'order_id', 'updated_at'];

    public function goods(): BelongsToMany
    {
        return $this->belongsToMany(Goods::class, 'goods_movements', 'movement_id', 'goods_id');
    }

    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'goods_movements', 'movement_id', 'warehouse_id');
    }
}
