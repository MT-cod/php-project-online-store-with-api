<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id', 'name', 'description', 'level'];

    public function goods(): BelongsToMany
    {
        return $this->belongsToMany(Goods::class);
    }
}
