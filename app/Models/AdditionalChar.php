<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdditionalChar extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'value', 'updated_at'];

    public function goods(): BelongsToMany
    {
        return $this->belongsToMany(Goods::class, 'goods_additional_chars', 'additional_char_id', 'goods_id');
    }
}
