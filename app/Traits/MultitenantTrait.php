<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait MultitenantTrait
{

    public static function bootMultitenantTrait()
    {
        if (auth()->check()) {
            static::creating(function ($model) {
                $model->user_id = auth()->id();
            });
    
            // if user is not administrator - name admin
            if (auth()->user()->name != 'admin') {
                static::addGlobalScope('user_id', function (Builder $builder) {
                    $builder->where('user_id', auth()->id());
                });
            }
        }

    }

}
