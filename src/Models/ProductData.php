<?php

namespace Acme\CmsDashboard\Models;

use Illuminate\Database\Eloquent\Model;

class ProductData extends Model
{
    protected $table = 'shop_products';
    protected $guarded = [];

    protected $casts = [
        'attributes_data' => 'array',
    ];

    public function variations()
    {
        return $this->hasMany(ProductVariation::class, 'product_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
