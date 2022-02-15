<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Goods extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = ['name', 'description', 'slug', 'price', 'category_id', 'updated_at'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function additionalChars(): BelongsToMany
    {
        return $this->belongsToMany(AdditionalChar::class, 'goods_additional_chars', 'goods_id', 'additional_char_id');
    }

    public static function goodsList(int $categoryId = 0): array
    {
        $res = [];
        $goods = ($categoryId) ? self::orderBy('name')->where('category_id', $categoryId)->get() : self::all();
        foreach ($goods as $item) {
            $res[$item->id] = $item->toArray();
            //передадим даты сразу в читабельном формате
            $res[$item->id]['created_at'] = $item->created_at->format('d.m.Y');
            $res[$item->id]['updated_at'] = $item->updated_at->format('d.m.Y');
            $res[$item->id]['additional_chars'] = $item->additionalChars()->get()->toArray();
        }
        return $res;
    }

    public function basketOwners(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'baskets', 'goods_id', 'user_id');
    }
}
