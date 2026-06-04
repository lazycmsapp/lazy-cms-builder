<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('shop_orders', 'tracking_number'))  $table->string('tracking_number')->nullable()->after('shipping_method');
            if (!Schema::hasColumn('shop_orders', 'tracking_carrier')) $table->string('tracking_carrier')->nullable()->after('tracking_number');
            if (!Schema::hasColumn('shop_orders', 'tracking_url'))     $table->string('tracking_url')->nullable()->after('tracking_carrier');
        });
    }

    public function down(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            foreach (['tracking_number', 'tracking_carrier', 'tracking_url'] as $col) {
                if (Schema::hasColumn('shop_orders', $col)) $table->dropColumn($col);
            }
        });
    }
};
