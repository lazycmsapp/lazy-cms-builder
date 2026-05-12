<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_field_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('rules')->nullable(); // JSON to store location rules (e.g., [post_type => drama])
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_group_id')->constrained('custom_field_groups')->onDelete('cascade');
            $table->string('label');
            $table->string('name');
            $table->string('type')->default('text'); // text, textarea, select, image, file, etc.
            $table->text('instructions')->nullable();
            $table->boolean('required')->default(false);
            $table->json('params')->nullable(); // JSON for field-specific settings (options, placeholder, etc.)
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
        Schema::dropIfExists('custom_field_groups');
    }
};
