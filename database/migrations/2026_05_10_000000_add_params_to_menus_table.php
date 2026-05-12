<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('menus') && !Schema::hasColumn('menus', 'params')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->text('params')->nullable()->after('route');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('menus', 'params')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->dropColumn('params');
            });
        }
    }
};
