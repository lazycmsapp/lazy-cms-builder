<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $definitions = \Acme\CmsDashboard\Services\SchemaManager::getCoreDefinitions();

        foreach ($definitions as $table => $columns) {
            \Acme\CmsDashboard\Services\SchemaManager::ensureColumns($table, $columns);
        }

        // Handle specific logic like editor_type migration
        if (Schema::hasTable('posts')) {
            DB::table('posts')->where('editor_type', 'classic')->update(['editor_type' => 'rich']);
        }
    }

    public function down(): void
    {
        // Down migration remains manual or empty if we want to preserve data
    }
};
