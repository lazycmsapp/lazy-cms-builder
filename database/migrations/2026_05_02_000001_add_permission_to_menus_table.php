<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Acme\CmsDashboard\Models\Permission;
use Acme\CmsDashboard\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->string('permission')->nullable()->after('group');
        });

        $newPermissions = [
            ['slug' => 'manage_posts',      'name' => 'Manage Posts',         'description' => 'Access Posts section in sidebar'],
            ['slug' => 'manage_pages',      'name' => 'Manage Pages',         'description' => 'Access Pages section in sidebar'],
            ['slug' => 'manage_comments',   'name' => 'Manage Comments',      'description' => 'Access Comments section in sidebar'],
            ['slug' => 'manage_forms',      'name' => 'Manage Forms',         'description' => 'Access Forms section in sidebar'],
            ['slug' => 'manage_appearance', 'name' => 'Manage Appearance',    'description' => 'Access Appearance (Themes, Menus, Widgets)'],
            ['slug' => 'manage_acpt',       'name' => 'Manage Custom Fields', 'description' => 'Access ACPT section in sidebar'],
            ['slug' => 'manage_tools',      'name' => 'Manage Tools',         'description' => 'Access Tools section in sidebar'],
            ['slug' => 'manage_analytics',  'name' => 'View Analytics',       'description' => 'Access Analytics section in sidebar'],
        ];

        foreach ($newPermissions as $perm) {
            Permission::firstOrCreate(['slug' => $perm['slug']], $perm);
        }

        // Assign permission slugs to parent menus by title
        $parentMap = [
            'Posts'      => 'manage_posts',
            'Media'      => 'manage_media',
            'Pages'      => 'manage_pages',
            'Comments'   => 'manage_comments',
            'Forms'      => 'manage_forms',
            'Appearance' => 'manage_appearance',
            'ACPT'       => 'manage_acpt',
            'Users'      => 'manage_users',
            'Tools'      => 'manage_tools',
            'Analytics'  => 'manage_analytics',
            'Settings'   => 'manage_settings',
        ];

        foreach ($parentMap as $title => $slug) {
            DB::table('menus')
                ->where('title', $title)
                ->whereNull('parent_id')
                ->update(['permission' => $slug]);
        }

        // "Roles" child under Users gets its own stricter permission
        DB::table('menus')
            ->where('title', 'Roles')
            ->whereNotNull('parent_id')
            ->update(['permission' => 'manage_roles']);

        // Sync all permissions to administrator role
        $admin = Role::where('slug', 'administrator')->first();
        if ($admin) {
            $admin->permissions()->syncWithoutDetaching(Permission::pluck('id')->toArray());
        }
    }

    public function down(): void
    {
        DB::table('menus')->update(['permission' => null]);

        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('permission');
        });
    }
};
