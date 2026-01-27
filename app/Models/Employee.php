<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Translatable\HasTranslations;

class Employee extends Model
{

    use LogsActivity;
    use HasTranslations;

    // use SoftDeletes;
    protected $fillable=[
        'first_name',
        'last_name',
        'email',
        'phone',
        'position_id',
        'salary',
        'user_id',
    ];

    public $translatable = [
        'first_name',
        'last_name',
    ];
    
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logFillable()
        ->logOnlyDirty();
    }
}
