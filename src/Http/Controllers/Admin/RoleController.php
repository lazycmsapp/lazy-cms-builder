<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Acme\CmsDashboard\Models\Role;
use Acme\CmsDashboard\Models\Permission;
use Acme\CmsDashboard\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')
            ->orderByRaw("CASE WHEN slug = 'super-admin' THEN 0 WHEN slug = 'administrator' THEN 1 ELSE 2 END")
            ->orderBy('name')
            ->get();
        return view('cms-dashboard::admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $data = $this->getDynamicPermissions();
        return view('cms-dashboard::admin.roles.create', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:roles,slug',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array'
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $role = Role::create($validated);

        if ($request->has('permissions')) {
            $this->syncPermissions($role, $request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        if ($role->slug === 'super-admin' && !auth()->user()->hasRole('super-admin')) {
            return redirect()->route('admin.roles.index')->with('error', 'Only Super Admin can manage Super Admin role.');
        }

        $data = $this->getDynamicPermissions();
        $data['role'] = $role;
        $data['rolePermissions'] = $role->permissions->pluck('slug')->toArray();
        
        return view('cms-dashboard::admin.roles.edit', $data);
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'nullable|array'
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $role->update($validated);

        if ($request->has('permissions')) {
            $this->syncPermissions($role, $request->permissions);
        } else {
            $role->permissions()->detach();
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    protected function syncPermissions(Role $role, array $permissionSlugs)
    {
        $ids = [];
        foreach ($permissionSlugs as $slug) {
            $permission = Permission::firstOrCreate(
                ['slug' => $slug],
                ['name' => ucwords(str_replace(['_', '-'], ' ', $slug))]
            );
            $ids[] = $permission->id;
        }
        $role->permissions()->sync($ids);
    }

    protected function getDynamicPermissions()
    {
        // 1. Get all Menus grouped by their group
        $menuGroups = Menu::with('children')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get()
            ->groupBy('group');

        // 2. Format into a structured array for the view
        $dynamicPermissions = [];

        foreach ($menuGroups as $groupName => $menus) {
            $groupKey = Str::slug($groupName ?: 'Main');
            $dynamicPermissions[$groupName] = [];

            foreach ($menus as $menu) {
                $slug = $menu->permission ?: $this->generatePermissionSlug($menu);
                
                $item = [
                    'title' => $menu->title,
                    'slug' => $slug,
                    'children' => []
                ];

                foreach ($menu->children as $child) {
                    $childSlug = $child->permission ?: $this->generatePermissionSlug($child, $menu);
                    $item['children'][] = [
                        'title' => $child->title,
                        'slug' => $childSlug
                    ];
                }

                $dynamicPermissions[$groupName][] = $item;
            }
        }

        // 3. Add Custom Options Pages from config
        $customPages = config('lazy-options.pages') ?? [];
        if (!empty($customPages)) {
            $dynamicPermissions['Custom Options'] = [];
            foreach ($customPages as $slug => $page) {
                $dynamicPermissions['Custom Options'][] = [
                    'title' => $page['title'] ?? $slug,
                    'slug' => 'manage_options_' . $slug,
                    'children' => []
                ];
            }
        }

        return [
            'dynamicPermissions' => $dynamicPermissions
        ];
    }

    protected function generatePermissionSlug($menu, $parent = null)
    {
        if ($menu->permission) return $menu->permission;
        
        // Custom generation logic
        $title = strtolower($menu->title);
        if ($title === 'dashboard') return 'access_dashboard';
        if ($title === 'posts') return 'manage_posts';
        if ($title === 'pages') return 'manage_pages';
        if ($title === 'media') return 'manage_media';
        if ($title === 'users') return 'manage_users';
        if ($title === 'settings') return 'manage_settings';
        
        $slug = Str::slug($menu->title, '_');
        
        // Make child slugs unique by appending parent slug if it's a common title
        if ($parent && in_array($title, ['add new', 'categories', 'tags', 'all posts', 'all pages'])) {
            $slug .= '_' . Str::slug($parent->title, '_');
        }
        
        return 'access_' . $slug;
    }

    public function destroy(Role $role)
    {
        if (in_array($role->slug, ['administrator', 'super-admin', 'subscriber'])) {
            return redirect()->route('admin.roles.index')->with('error', 'Cannot delete system roles.');
        }

        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
