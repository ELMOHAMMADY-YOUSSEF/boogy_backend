<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Image;
use App\Models\Feature;
use App\Models\Specification;
use App\Models\Review;
use App\Models\Color;
use App\Models\Size;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slogo',
        'brand',
        'quantity',
        'price',
        'oldPrice',
        'rating',
        'reviewCount',
        'sold',
        'categorie',
        'subCategorie',
        'details'
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function colors()
    {
        return $this->hasMany(Color::class);
    }

    public function sizes()
    {
        return $this->hasMany(Size::class);
    }

    public function features()
    {
        return $this->hasMany(Feature::class);
    }

    public function specifications()
    {
        return $this->hasMany(Specification::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
