<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('menus')
            ->whereNull('parent_id')
            ->where('title', 'Shop')
            ->update(['route' => 'admin.shop.overview']);
    }

    public function down(): void
    {
        DB::table('menus')
            ->whereNull('parent_id')
            ->where('title', 'Shop')
            ->update(['route' => 'admin.shop.orders.index']);
    }
};
