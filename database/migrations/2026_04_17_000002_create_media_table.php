<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $row) {
            $row->id();
            $row->string('filename');
            $row->string('path');
            $row->string('mime_type')->nullable();
            $row->unsignedBigInteger('original_size');
            $row->unsignedBigInteger('compressed_size')->nullable();
            $row->string('alt_text')->nullable();
            $row->string('title')->nullable();
            $row->text('caption')->nullable();
            $row->text('description')->nullable();
            $row->unsignedBigInteger('user_id')->nullable();
            $row->integer('width')->nullable();
            $row->integer('height')->nullable();
            $row->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
