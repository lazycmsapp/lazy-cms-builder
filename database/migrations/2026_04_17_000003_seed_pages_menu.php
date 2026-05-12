<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add Pages to Sidebar Menus
        $id = DB::table('menus')->insertGetId([
            'title' => 'Pages',
            'route' => 'admin.pages.index',
            'icon' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>',
            'group' => 'Main',
            'order' => 3
        ]);

        DB::table('menus')->insert([
            [
                'parent_id' => $id,
                'title' => 'All Pages',
                'route' => 'admin.pages.index',
                'icon' => null,
                'group' => null,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'parent_id' => $id,
                'title' => 'Add New',
                'route' => 'admin.pages.create',
                'icon' => null,
                'group' => null,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    public function down(): void
    {
        $id = DB::table('menus')->where('title', 'Pages')->value('id');
        if ($id) {
            DB::table('menus')->where('parent_id', $id)->delete();
            DB::table('menus')->where('id', $id)->delete();
        }
    }
};
