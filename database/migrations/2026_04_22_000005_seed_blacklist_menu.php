<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $usersMenu = DB::table('menus')->where('title', 'Users')->first();
        if ($usersMenu) {
            DB::table('menus')->insert([
                'parent_id' => $usersMenu->id,
                'title' => 'Blacklist',
                'route' => 'admin.blacklist.index',
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('menus')->where('title', 'Blacklist')->where('route', 'admin.blacklist.index')->delete();
    }
};
