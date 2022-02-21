<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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

    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'goods_warehouses', 'goods_id', 'warehouse_id');
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

    /**
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(40)->height(30);
        $this->addMediaConversion('normal')->width(300)->height(200);
    }

    public function basketOwners(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'baskets', 'goods_id', 'user_id');
    }
}
