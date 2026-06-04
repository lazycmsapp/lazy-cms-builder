<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('shop_orders', 'refunded_amount')) {
                $table->decimal('refunded_amount', 12, 2)->default(0)->after('total');
            }
        });
    }

    public function down(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            if (Schema::hasColumn('shop_orders', 'refunded_amount')) {
                $table->dropColumn('refunded_amount');
            }
        });
    }
};
