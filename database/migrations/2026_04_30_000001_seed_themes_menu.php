<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Find Appearance Menu (Parent)
        $appearanceMenu = DB::table('menus')->where('title', 'Appearance')->first();

        if ($appearanceMenu) {
            // Update Appearance route to themes index (WordPress style)
            DB::table('menus')->where('id', $appearanceMenu->id)->update([
                'route' => 'admin.themes.index'
            ]);

            // 2. Check if Themes menu already exists under Appearance
            $themesMenu = DB::table('menus')
                ->where('parent_id', $appearanceMenu->id)
                ->where('title', 'Themes')
                ->first();

            if (!$themesMenu) {
                // Add Themes menu
                DB::table('menus')->insert([
                    'parent_id' => $appearanceMenu->id,
                    'title' => 'Themes',
                    'route' => 'admin.themes.index',
                    'icon'  => null,
                    'group' => 'Main',
                    'order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        $appearanceMenu = DB::table('menus')->where('title', 'Appearance')->first();
        if ($appearanceMenu) {
            DB::table('menus')->where('parent_id', $appearanceMenu->id)->where('title', 'Themes')->delete();
            DB::table('menus')->where('id', $appearanceMenu->id)->update([
                'route' => 'admin.menus.index'
            ]);
        }
    }
};
