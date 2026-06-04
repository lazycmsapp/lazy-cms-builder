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
            // The application identifies a term's taxonomy by slug (see CustomTaxonomy::terms()
            // and every controller/helper), so store the taxonomy slug directly rather than an FK id.
            $table->string('taxonomy_slug')->index();
            $table->string('cpt_slug')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('taxonomy_terms')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('lang_code', 10)->default('en')->index();
            $table->unsignedBigInteger('origin_id')->nullable()->index();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->unique(['slug', 'taxonomy_slug', 'lang_code'], 'terms_slug_taxslug_lang_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxonomy_terms');
    }
};
