<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasUserOwnership
{
    protected static function bootHasUserOwnership()
    {
        static::creating(function (Model $model) {
            if (auth()->check() && !$model->user_id) {
                $model->user_id = auth()->id();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
