<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('navigation_menus', function (Blueprint $table) {
            $table->string('lang_code', 10)->nullable()->after('location')->index();
        });
    }

    public function down(): void
    {
        Schema::table('navigation_menus', function (Blueprint $table) {
            $table->dropColumn('lang_code');
        });
    }
};
