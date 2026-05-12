<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update existing Products submenus
        DB::table('menus')->where('title', 'Products')->update([
            'route' => 'admin.posts.index',
            'params' => json_encode(['type' => 'product'])
        ]);

        // 2. Update Shop Menu and its submenus
        $shopMenu = DB::table('menus')->where('title', 'Shop')->first();
        if ($shopMenu) {
            DB::table('menus')->where('id', $shopMenu->id)->update([
                'route' => 'admin.shop.orders.index'
            ]);

            // Fix submenus
            DB::table('menus')->where('parent_id', $shopMenu->id)->where('title', 'Orders')->update([
                'route' => 'admin.shop.orders.index'
            ]);

            DB::table('menus')->where('parent_id', $shopMenu->id)->where('title', 'Settings')->update([
                'route' => 'admin.shop.settings'
            ]);

            // Add Reviews if missing
            $exists = DB::table('menus')->where('parent_id', $shopMenu->id)->where('title', 'Reviews')->exists();
            if (!$exists) {
                DB::table('menus')->insert([
                    'parent_id' => $shopMenu->id,
                    'title' => 'Reviews',
                    'route' => 'admin.shop.reviews.index',
                    'order' => 2,
                    'created_at' => now(), 'updated_at' => now()
                ]);
                
                // Shift others
                DB::table('menus')->where('parent_id', $shopMenu->id)->where('title', 'Customers')->update(['order' => 3]);
                DB::table('menus')->where('parent_id', $shopMenu->id)->where('title', 'Settings')->update(['order' => 4]);
            }
        }
    }

    public function down(): void
    {
        // No need to revert specifically for this hotfix
    }
};
