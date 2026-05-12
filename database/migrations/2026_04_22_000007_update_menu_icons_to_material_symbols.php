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
        $mappings = [
            'Dashboard' => 'dashboard',
            'Posts' => 'article',
            'Media' => 'image',
            'Pages' => 'description',
            'Comments' => 'comment',
            'Menu' => 'menu',
            'ACPT' => 'build',
            'Users' => 'group',
            'Settings' => 'settings',
            'Movies' => 'movie',
            'Books' => 'menu_book',
            'Blacklist' => 'block',
        ];

        foreach ($mappings as $title => $icon) {
            DB::table('menus')->where('title', $title)->update(['icon' => $icon]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting to SVGs is complex, leaving empty for now or can restore if needed
    }
};
