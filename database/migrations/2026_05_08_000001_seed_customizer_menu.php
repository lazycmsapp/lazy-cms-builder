<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $appearance = DB::table('menus')->where('title', 'Appearance')->first();

        if ($appearance) {
            $exists = DB::table('menus')
                ->where('parent_id', $appearance->id)
                ->where('title', 'Customizer')
                ->exists();

            if (!$exists) {
                DB::table('menus')->insert([
                    'parent_id' => $appearance->id,
                    'title'     => 'Customizer',
                    'route'     => 'admin.customizer.index',
                    'icon'      => null,
                    'group'     => null,
                    'order'     => 1,
                    'permission'=> null,
                    'created_at'=> now(),
                    'updated_at'=> now(),
                ]);

                // Re-order existing children
                DB::table('menus')->where('parent_id', $appearance->id)->where('title', 'Themes')->update(['order' => 2]);
                DB::table('menus')->where('parent_id', $appearance->id)->where('title', 'Menus')->update(['order' => 3]);
                DB::table('menus')->where('parent_id', $appearance->id)->where('title', 'Widgets')->update(['order' => 4]);
            }
        }
    }

    public function down(): void
    {
        $appearance = DB::table('menus')->where('title', 'Appearance')->first();
        if ($appearance) {
            DB::table('menus')
                ->where('parent_id', $appearance->id)
                ->where('title', 'Customizer')
                ->delete();
        }
    }
};
