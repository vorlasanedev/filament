<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    protected $fillable = [
        'reference',
        'type',
        'source_location_id',
        'destination_location_id',
        'status',
        'scheduled_date',
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
    ];

    public function sourceLocation()
    {
        return $this->belongsTo(Location::class, 'source_location_id');
    }

    public function destinationLocation()
    {
        return $this->belongsTo(Location::class, 'destination_location_id');
    }

    public function moves()
    {
        return $this->hasMany(StockMove::class);
    }
}
