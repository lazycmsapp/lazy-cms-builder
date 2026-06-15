<?php

namespace Acme\CmsDashboard\Models;

use Illuminate\Database\Eloquent\Model;

class ProductData extends Model
{
    protected $table = 'shop_products';
    protected $fillable = [
        'post_id', 'type', 'attributes_data', 'price', 'sale_price', 'sale_ends_at',
        'sku', 'stock_quantity', 'stock_status', 'manage_stock', 'product_type',
        'short_description', 'attributes',
    ];

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
