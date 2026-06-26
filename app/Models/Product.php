<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use \App\Models\Traits\HasUserOwnership;

    protected $fillable = [
        'name',
        'sku',
        'cost',
        'price',
        'weight',
        'strategy',
        'safety_stock',
        'lead_time',
        'is_active',
        'product_category_id',
        'product_type_id',
        'product_unit_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function type()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function unit()
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }
}
