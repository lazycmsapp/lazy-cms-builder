<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_taxonomies', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Categories
            $table->string('singular_name')->nullable(); // e.g. Category
            $table->string('slug')->unique(); // e.g. category
            $table->text('description')->nullable();
            $table->json('post_types')->nullable(); // JSON array of CPT slugs it applies to
            $table->boolean('hierarchical')->default(true);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_taxonomies');
    }
};
