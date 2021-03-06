<?php

namespace App\Models;

use App\Models\Area;
use App\Models\User;
use App\Traits\MultitenantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    use MultitenantTrait;

    protected $fillable = [
        'name',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function houses()
    {
        return $this->hasMany(House::class);
    }
}
