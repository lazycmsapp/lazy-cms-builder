<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        \Acme\CmsDashboard\Services\SchemaManager::ensureColumns('shop_reviews', [
            'parent_id' => [
                'type' => 'unsignedBigInteger', 
                'nullable' => true, 
                'after' => 'post_id', 
                'index' => true
            ]
        ]);
    }

    public function down(): void
    {
        Schema::table('shop_reviews', function (Blueprint $table) {
            if (Schema::hasColumn('shop_reviews', 'parent_id')) {
                $table->dropColumn('parent_id');
            }
        });
    }
};
