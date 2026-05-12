<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxonomy_terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taxonomy_id')->constrained('custom_taxonomies')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('taxonomy_terms')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('lang_code', 10)->default('en')->index();
            $table->unsignedBigInteger('origin_id')->nullable()->index();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->unique(['slug', 'taxonomy_id', 'lang_code'], 'terms_slug_tax_lang_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxonomy_terms');
    }
};
