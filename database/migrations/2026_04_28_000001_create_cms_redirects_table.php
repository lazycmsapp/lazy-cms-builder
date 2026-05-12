<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('old_url')->index();
            $table->string('new_url');
            $table->integer('status_code')->default(301); // 301 (Permanent) or 302 (Temporary)
            $table->unsignedBigInteger('hits')->default(0);
            $table->timestamp('last_hit_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_redirects');
    }
};
