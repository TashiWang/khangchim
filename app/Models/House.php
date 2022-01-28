<?php

namespace App\Models;

use App\Traits\MultitenantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;
    use MultitenantTrait;

    protected $fillable = [
        'address',
        'area_id',
        'user_id',
        'contact',
        'number_of_room',
        'number_of_toilet',
        'number_of_balcony',
        'rent',
        'featured_image',
        'images',
        'images',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
