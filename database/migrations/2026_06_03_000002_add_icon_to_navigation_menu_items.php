<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('navigation_menu_items', function (Blueprint $table) {
            if (!Schema::hasColumn('navigation_menu_items', 'icon')) {
                $table->string('icon')->nullable()->after('classes');
            }
            if (!Schema::hasColumn('navigation_menu_items', 'show_only_icon')) {
                $table->boolean('show_only_icon')->default(false)->after('icon');
            }
        });
    }

    public function down(): void
    {
        Schema::table('navigation_menu_items', function (Blueprint $table) {
            if (Schema::hasColumn('navigation_menu_items', 'show_only_icon')) {
                $table->dropColumn('show_only_icon');
            }
            if (Schema::hasColumn('navigation_menu_items', 'icon')) {
                $table->dropColumn('icon');
            }
        });
    }
};
