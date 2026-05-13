<?php

namespace Acme\CmsDashboard\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $table = 'shop_product_variations';
    protected $guarded = [];

    protected $casts = [
        'attributes_data' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(ProductData::class, 'product_id');
    }
}
