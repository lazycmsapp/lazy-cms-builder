<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('lang_code', 10)->default('en')->after('slug');
            $table->unsignedBigInteger('origin_id')->nullable()->after('lang_code');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->string('lang_code', 10)->default('en')->after('slug');
            $table->unsignedBigInteger('origin_id')->nullable()->after('lang_code');
        });

        Schema::table('taxonomy_terms', function (Blueprint $table) {
            $table->string('lang_code', 10)->default('en')->after('slug');
            $table->unsignedBigInteger('origin_id')->nullable()->after('lang_code');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['lang_code', 'origin_id']);
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn(['lang_code', 'origin_id']);
        });

        Schema::table('taxonomy_terms', function (Blueprint $table) {
            $table->dropColumn(['lang_code', 'origin_id']);
        });
    }
};
