<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Products Menu
        $productsId = DB::table('menus')->insertGetId([
            'title' => 'Products',
            'route' => 'admin.posts.index',
            'params' => json_encode(['type' => 'product']),
            'icon' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>',
            'group' => 'Main',
            'order' => 4
        ]);

        DB::table('menus')->insert([
            [
                'parent_id' => $productsId,
                'title' => 'All Products',
                'route' => 'admin.posts.index',
                'params' => json_encode(['type' => 'product']),
                'order' => 1,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'parent_id' => $productsId,
                'title' => 'Add New',
                'route' => 'admin.posts.create',
                'params' => json_encode(['type' => 'product']),
                'order' => 2,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'parent_id' => $productsId,
                'title' => 'Categories',
                'route' => 'admin.categories.index',
                'params' => json_encode(['type' => 'product']),
                'order' => 3,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'parent_id' => $productsId,
                'title' => 'Tags',
                'route' => 'admin.tags.index',
                'params' => json_encode(['type' => 'product']),
                'order' => 4,
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);

        // 2. Shop Menu
        $shopId = DB::table('menus')->insertGetId([
            'title' => 'Shop',
            'route' => 'admin.dashboard.index', // Temporary until ShopController created
            'icon' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>',
            'group' => 'Main',
            'order' => 5
        ]);

        DB::table('menus')->insert([
            [
                'parent_id' => $shopId,
                'title' => 'Orders',
                'route' => 'admin.dashboard.index', // Temporary
                'order' => 1,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'parent_id' => $shopId,
                'title' => 'Customers',
                'route' => 'admin.users.index',
                'order' => 2,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'parent_id' => $shopId,
                'title' => 'Settings',
                'route' => 'admin.settings.index',
                'order' => 3,
                'created_at' => now(), 'updated_at' => now()
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('menus')->whereIn('title', ['Products', 'Shop'])->delete();
    }
};
