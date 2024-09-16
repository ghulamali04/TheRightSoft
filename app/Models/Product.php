<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'product_id');
    }
}
