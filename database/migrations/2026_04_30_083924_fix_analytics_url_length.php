<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cms_analytics', function (Blueprint $table) {
            // Drop index first because TEXT columns cannot be fully indexed without a length
            $table->dropIndex(['url']);
            
            $table->text('url')->change();
            $table->text('referrer')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('cms_analytics', function (Blueprint $table) {
            $table->string('url', 255)->change();
            $table->string('referrer', 255)->nullable()->change();
            
            $table->index('url');
        });
    }
};
