<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('lang_code', 5)->default('en')->after('status');
            $table->unsignedBigInteger('origin_id')->nullable()->after('lang_code');
            
            $table->index('lang_code');
            $table->index('origin_id');
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['lang_code', 'origin_id']);
        });
    }
};
