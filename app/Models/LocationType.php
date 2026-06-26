<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationType extends Model
{
    use \App\Models\Traits\HasUserOwnership;
    use SoftDeletes;

    protected $fillable = ['name'];
}
