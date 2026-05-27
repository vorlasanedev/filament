<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use \App\Models\Traits\HasUserOwnership;

    protected $fillable = [
        'name',
        'short_name',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
