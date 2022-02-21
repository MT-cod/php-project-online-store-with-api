<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'address', 'priority', 'updated_at'];

    public function goods(): BelongsToMany
    {
        return $this->belongsToMany(Goods::class, 'goods_warehouses', 'warehouse_id', 'goods_id');
    }
}
