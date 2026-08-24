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
        'can_be_sold',
        'can_be_purchased',
        'is_favorite',
        'image',
        'track_inventory',
        'invoicing_policy',
        'sales_taxes',
        'purchase_taxes',
        'barcode',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'can_be_sold' => 'boolean',
        'can_be_purchased' => 'boolean',
        'is_favorite' => 'boolean',
        'track_inventory' => 'boolean',
        'sales_taxes' => 'array',
        'purchase_taxes' => 'array',
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
