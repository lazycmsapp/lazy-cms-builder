<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if ACPT parent menu exists
        $parentId = DB::table('menus')->where('title', 'ACPT')->value('id');

        if (!$parentId) {
            $parentId = DB::table('menus')->insertGetId([
                'title' => 'ACPT',
                'route' => 'admin.acpt.cpt.index',
                'icon' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>',
                'group' => 'Advanced',
                'order' => 10,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Add sub-menus
        $subMenus = [
            ['title' => 'Post Types', 'route' => 'admin.acpt.cpt.index', 'order' => 1],
            ['title' => 'Taxonomies', 'route' => 'admin.acpt.taxonomies.index', 'order' => 2],
            ['title' => 'Custom Fields', 'route' => 'admin.acpt.fields.index', 'order' => 3],
        ];

        foreach ($subMenus as $sub) {
            $exists = DB::table('menus')->where('parent_id', $parentId)->where('title', $sub['title'])->exists();
            if (!$exists) {
                DB::table('menus')->insert([
                    'parent_id' => $parentId,
                    'title' => $sub['title'],
                    'route' => $sub['route'],
                    'order' => $sub['order'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    public function down(): void
    {
        // Optional
    }
};
