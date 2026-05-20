<?php

use Illuminate\Database\Migrations\Migration;
use Acme\CmsDashboard\Models\Menu;

return new class extends Migration
{
    public function up(): void
    {
        $lazyBuilder = Menu::where('title', 'Lazy Builder')
            ->where('route', 'admin.lazy-builder.sections')
            ->whereNull('parent_id')
            ->first();

        if ($lazyBuilder) {
            // Add "Sections" as first child (pointing to existing sections page)
            Menu::firstOrCreate(
                ['title' => 'Sections', 'parent_id' => $lazyBuilder->id],
                ['route' => 'admin.lazy-builder.sections', 'order' => 1]
            );

            // Add "Library" as second child
            Menu::firstOrCreate(
                ['title' => 'Library', 'parent_id' => $lazyBuilder->id],
                ['route' => 'admin.lazy-builder.library', 'order' => 2]
            );
        }
    }

    public function down(): void
    {
        Menu::where('title', 'Library')->where('route', 'admin.lazy-builder.library')->delete();
        Menu::where('title', 'Sections')->where('route', 'admin.lazy-builder.sections')
            ->whereNotNull('parent_id')->delete();
    }
};
