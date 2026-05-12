<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('post_translations', function (Blueprint $table) {
            $table->string('slug')->after('locale')->nullable();
            $table->index('slug');
        });
    }

    public function down()
    {
        Schema::table('post_translations', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
