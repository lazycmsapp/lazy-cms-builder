<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('shop_orders') && !Schema::hasColumn('shop_orders', 'is_read')) {
            Schema::table('shop_orders', function (Blueprint $table) {
                $table->boolean('is_read')->default(false)->after('customer_note');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('shop_orders') && Schema::hasColumn('shop_orders', 'is_read')) {
            Schema::table('shop_orders', function (Blueprint $table) {
                $table->dropColumn('is_read');
            });
        }
    }
};
