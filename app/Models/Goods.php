<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Goods extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'slug', 'price', 'category_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function additionalChars(): BelongsToMany
    {
        return $this->belongsToMany(AdditionalChar::class, 'goods_additional_chars', 'goods_id', 'additional_char_id');
    }

    public static function goodsList(): array
    {
        $res = [];
        foreach (Goods::all() as $val) {
            $res[$val->id] = $val->toArray();
            //передадим даты сразу в читабельном формате
            $res[$val->id]['created_at'] = $val->created_at->format('d.m.Y');
            $res[$val->id]['updated_at'] = $val->updated_at->format('d.m.Y');
            $res[$val->id]['additional_chars'] = $val->additionalChars()->get()->toArray();
        }
        return $res;
    }
}
