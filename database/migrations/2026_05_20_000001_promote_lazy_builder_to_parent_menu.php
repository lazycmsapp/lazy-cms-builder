<?php

use Illuminate\Database\Migrations\Migration;
use Acme\CmsDashboard\Models\Menu;

return new class extends Migration
{
    public function up(): void
    {
        $lazyBuilder = Menu::where('title', 'Lazy Builder')
            ->where('route', 'admin.lazy-builder.sections')
            ->first();

        if ($lazyBuilder) {
            $lazyBuilder->update([
                'parent_id' => null,
                'group'     => 'Main',
                'icon'      => 'view_quilt',
                'order'     => 42,
            ]);
        }
    }

    public function down(): void
    {
        $appearanceMenu = Menu::where('title', 'Appearance')->first();
        $lazyBuilder = Menu::where('title', 'Lazy Builder')
            ->where('route', 'admin.lazy-builder.sections')
            ->first();

        if ($lazyBuilder && $appearanceMenu) {
            $lazyBuilder->update([
                'parent_id' => $appearanceMenu->id,
                'group'     => null,
                'icon'      => null,
                'order'     => 5,
            ]);
        }
    }
};
