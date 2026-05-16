<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Acme\CmsDashboard\Models\Menu;

return new class extends Migration
{
    public function up(): void
    {
        $appearanceMenu = Menu::where('title', 'Appearance')->first();
        if ($appearanceMenu) {
            Menu::create([
                'parent_id' => $appearanceMenu->id,
                'title' => 'Lazy Builder',
                'route' => 'admin.lazy-builder.sections',
                'order' => 5, // After Customizer, Themes, Menus, Widgets
            ]);
        }
    }

    public function down(): void
    {
        Menu::where('title', 'Lazy Builder')
            ->where('route', 'admin.lazy-builder.sections')
            ->delete();
    }
};
