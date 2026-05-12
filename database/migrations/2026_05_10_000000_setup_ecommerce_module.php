<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Register Product Post Type if not exists
        $productTypeExists = DB::table('post_types')->where('slug', 'product')->exists();
        if (!$productTypeExists) {
            DB::table('post_types')->insert([
                'name' => 'Products',
                'slug' => 'product',
                'singular_name' => 'Product',
                'is_builtin' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'supports' => json_encode(['title', 'editor', 'thumbnail', 'excerpt', 'comments'])
            ]);
        }

        // 2. Product Data Table (for fast filtering)
        Schema::create('shop_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->decimal('price', 15, 2)->default(0)->index();
            $table->decimal('sale_price', 15, 2)->nullable()->index();
            $table->string('sku')->nullable()->index();
            $table->integer('stock_quantity')->default(0);
            $table->string('stock_status')->default('instock')->index(); // instock, outofstock, onbackorder
            $table->boolean('manage_stock')->default(false);
            $table->string('product_type')->default('simple'); // simple, variable, external, grouped
            $table->text('short_description')->nullable();
            $table->json('attributes')->nullable(); // weight, dimensions, etc.
            $table->timestamps();
        });

        // 3. Orders Table
        Schema::create('shop_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('order_number')->unique();
            $table->string('status')->default('pending')->index(); 
            $table->decimal('subtotal', 15, 2);
            $table->decimal('shipping_total', 15, 2)->default(0);
            $table->decimal('tax_total', 15, 2)->default(0);
            $table->decimal('discount_total', 15, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->decimal('total', 15, 2)->index();
            
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->text('address_line_1');
            $table->text('address_line_2')->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('postcode');
            $table->string('country');
            
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('customer_note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Order Items Table
        Schema::create('shop_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('shop_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('posts')->nullOnDelete();
            $table->string('product_name'); 
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->json('meta')->nullable(); 
            $table->timestamps();
        });

        // 5. Register Product Taxonomies
        $catExists = DB::table('custom_taxonomies')->where('slug', 'product_cat')->exists();
        if (!$catExists) {
            DB::table('custom_taxonomies')->insert([
                'name' => 'Product Categories',
                'slug' => 'product_cat',
                'singular_name' => 'Product Category',
                'post_types' => json_encode(['product']),
                'hierarchical' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $tagExists = DB::table('custom_taxonomies')->where('slug', 'product_tag')->exists();
        if (!$tagExists) {
            DB::table('custom_taxonomies')->insert([
                'name' => 'Product Tags',
                'slug' => 'product_tag',
                'singular_name' => 'Product Tag',
                'post_types' => json_encode(['product']),
                'hierarchical' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_order_items');
        Schema::dropIfExists('shop_orders');
        Schema::dropIfExists('shop_products');
        DB::table('post_types')->where('slug', 'product')->delete();
    }
};
