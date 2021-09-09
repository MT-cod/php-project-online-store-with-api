<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id', 'name', 'description', 'level'];

    public function goods(): HasMany
    {
        return $this->hasMany(Goods::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function childrens(): HasMany
    {
        return $this->hasMany(self::class,'parent_id','id');
    }

    public static function categoriesTree($categs = []): array
    {
        $res = [];
        if (empty($categs)) {
            $categs = self::orderBy('name')->where('level', 1)->get();
        }
        foreach ($categs as $cat) {
            $childs = [];
            if ($cat->childrens()->count() > 0) {
                $childs = ['childrens' => self::categoriesTree($cat->childrens()->get())];
            }
            $res[$cat->id] = $cat->toArray() + $childs;
            //передадим даты сразу в читабельном формате
            $res[$cat->id]['created_at'] = $cat->created_at->format('d.m.Y');
            $res[$cat->id]['updated_at'] = $cat->updated_at->format('d.m.Y');
        }
        return $res;
    }

    public static function categTreeWithGoods($categs = []): array
    {
        $res = [];
        if (empty($categs)) {
            $categs = self::orderBy('name')->where('level', 1)->get();
        }
        foreach ($categs as $cat) {
            $childs = [];
            $goods = [];
            if ($cat->childrens()->count() > 0) {
                $childs = ['childrens' => self::categTreeWithGoods($cat->childrens()->get())];
            }
            if ($cat->goods()->count() > 0) {
                $goods = ['goods' => Goods::goodsList($cat->id)];
            }
            $res[$cat->id] = $cat->toArray() + $childs + $goods;
            //передадим даты сразу в читабельном формате
            $res[$cat->id]['created_at'] = $cat->created_at->format('d.m.Y');
            $res[$cat->id]['updated_at'] = $cat->updated_at->format('d.m.Y');
        }
        return $res;
    }

    public static function categoriesList(): array
    {
        $res = [];
        foreach (self::all() as $val) {
            $res[$val->id] = $val->toArray();
            //передадим даты сразу в читабельном формате
            $res[$val->id]['created_at'] = $val->created_at->format('d.m.Y');
            $res[$val->id]['updated_at'] = $val->updated_at->format('d.m.Y');
        }
        return $res;
    }
}
