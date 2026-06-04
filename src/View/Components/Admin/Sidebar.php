<?php

namespace Acme\CmsDashboard\View\Components\Admin;

use Acme\CmsDashboard\Models\Menu;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class Sidebar extends Component
{
    public $menuGroups;
    public $activeMenu;

    public function __construct(?string $activeMenu = null)
    {
        $this->activeMenu = $activeMenu;

        $this->menuGroups = Menu::with('children')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get()
            ->groupBy('group');
    }

    public static function isUrlActive($url, $strict = false)
    {
        if (!$url || $url === '#') return false;

        $targetUrl = parse_url($url);
        $targetPath = trim($targetUrl['path'] ?? '', '/');
        $currentPath = trim(request()->getPathInfo(), '/');

        // 1. Dashboard: Must be exact match 'admin'
        if ($targetPath === 'admin') {
            return $currentPath === 'admin';
        }

        // 2. Base path check
        $indexPaths = ['admin/posts', 'admin/pages', 'admin/users', 'admin/settings', 'admin/roles', 'admin/categories', 'admin/tags', 'admin/product-categories', 'admin/product-tags', 'admin/comments', 'admin/profile'];
        
        // Special case: Your Profile belongs to Users group
        if ($targetPath === 'admin/users' && ($currentPath === 'admin/profile' || str_starts_with($currentPath, 'admin/users/') && str_ends_with($currentPath, '/edit'))) {
            // If we are editing the CURRENT user, then Your Profile should be active
            $route = request()->route();
            if ($route && $route->getName() === 'admin.users.edit') {
                $userParam = $route->parameter('user');
                $userId = ($userParam instanceof \App\Models\User) ? $userParam->id : $userParam;
                if ((int)$userId === (int)auth()->id()) {
                    return false; // Parent itself not active, but children loop will find it
                }
            }
        }

        // Special case for Your Profile child item
        if ($targetPath === 'admin/profile') {
            if ($currentPath === 'admin/profile') return true;
            
            $route = request()->route();
            if ($route && $route->getName() === 'admin.users.edit') {
                $userParam = $route->parameter('user');
                $userId = ($userParam instanceof \App\Models\User) ? $userParam->id : $userParam;
                return (int)$userId === (int)auth()->id();
            }
        }
        
        if (in_array($targetPath, $indexPaths)) {
            // Index routes MUST be an exact match (ignoring query strings for now) 
            // unless it's a specific type (handled in step 3) or an edit/create page
            if ($currentPath !== $targetPath) {
                // If it's something like admin/posts/create, the index 'admin/posts' should be active 
                // ONLY if the types match (handled later) or if it's a generic index.
                // For now, let's allow it if it starts with the target path followed by /
                if ($strict || !str_starts_with($currentPath, $targetPath . '/')) {
                    return false;
                }
            }
        } elseif (!str_starts_with($currentPath, $targetPath)) {
            return false;
        }

        // 3. Query Parameter & Type Strict Check
        parse_str($targetUrl['query'] ?? '', $targetQuery);
        
        $currentType = request()->query('type');
        $currentCpt = request()->query('cpt_slug') ?? request()->query('cpt');

        // Fallback: Detect type from route if on edit/create page
        if (!$currentType) {
            $route = request()->route();
            if ($route) {
                $post = $route->parameter('post');
                if ($post instanceof \Acme\CmsDashboard\Models\Post) {
                    $currentType = $post->type;
                } elseif (is_numeric($post)) {
                    try {
                        $currentType = \Acme\CmsDashboard\Models\Post::where('id', $post)->value('type');
                    } catch (\Exception $e) {}
                } else {
                    $currentType = $route->parameter('type');
                }
            }
        }

        $targetType = $targetQuery['type'] ?? $targetQuery['cpt_slug'] ?? $targetQuery['cpt'] ?? null;

        // If target has a type/cpt_slug, current request MUST match it
        if ($targetType) {
            if ($currentType !== $targetType && $currentCpt !== $targetType) {
                return false;
            }
            
            // Even if types match, if target is an index path, current path MUST match exactly 
            // OR be a child of it (like /create or /1/edit)
            if (in_array($targetPath, $indexPaths) && $currentPath !== $targetPath) {
                if ($strict || !str_starts_with($currentPath, $targetPath . '/')) {
                    return false;
                }
            }

            return true;
        }

        // IMPORTANT: If target belongs to standard Posts/Pages but HAS NO TYPE (it's a root or general menu),
        // it should NOT be active if the CURRENT request has a custom type (CPT).
        if (!$targetType && !empty($currentType) && !in_array($currentType, ['post', 'page'])) {
            $isTargetPosts = str_starts_with($targetPath, 'admin/posts');
            $isTargetPages = str_starts_with($targetPath, 'admin/pages');

            if ($isTargetPosts || $isTargetPages) {
                return false;
            }
        }

        return true;
    }

    public static function canAccess($url)
    {
        if (!$url || $url === '#') return true;
        if (!auth()->check()) return false;
        $user = auth()->user();

        $targetUrl = parse_url($url);
        $targetPath = trim($targetUrl['path'] ?? '', '/');
        parse_str($targetUrl['query'] ?? '', $targetQuery);

        $type = $targetQuery['type'] ?? $targetQuery['cpt_slug'] ?? null;

        // Dashboard
        if ($targetPath === 'admin') return true;

        // Content / Posts / Pages
        if (str_contains($targetPath, 'admin/posts') || str_contains($targetPath, 'admin/pages')) {
            $pType = $type ?: (str_contains($targetPath, 'admin/pages') ? 'page' : 'post');
            if ($pType === 'page') return $user->hasPermission('manage_pages');
            if ($pType === 'post') return $user->hasPermission('manage_posts');
            
            // For CPTs, check multiple potential permission prefixes
            return $user->hasPermission('manage_' . $pType) || 
                   $user->hasPermission('access_' . $pType) ||
                   $user->hasPermission('access_all_' . $pType);
        }

        // Users
        if (str_contains($targetPath, 'admin/users') || str_contains($targetPath, 'admin/blacklist')) return $user->hasPermission('manage_users');
        
        // Roles
        if (str_contains($targetPath, 'admin/roles')) return $user->hasPermission('manage_roles');

        // Settings
        if (str_contains($targetPath, 'admin/settings') || $targetPath === 'admin/dashboard/settings') return $user->hasPermission('manage_settings');

        // Media
        if (str_contains($targetPath, 'admin/media')) return $user->hasPermission('manage_media');
        
        // Comments
        if (str_contains($targetPath, 'admin/comments')) return $user->hasPermission('manage_posts');

        // Appearance (Themes, Menus, Widgets)
        if (str_contains($targetPath, 'admin/themes')) return $user->hasPermission('access_themes') || $user->hasPermission('manage_settings');
        if (str_contains($targetPath, 'admin/menus')) return $user->hasPermission('access_menus') || $user->hasPermission('manage_settings');
        if (str_contains($targetPath, 'admin/widgets')) return $user->hasPermission('access_widgets') || $user->hasPermission('manage_settings');

        // Shop / eCommerce
        if (str_contains($targetPath, 'admin/shop')) return $user->hasPermission('manage_settings') || $user->hasPermission('manage_posts');

        // ACPT
        if (str_contains($targetPath, 'admin/acpt')) return $user->hasPermission('manage_settings');

        return false;
    }

    public function resolveRoute($menu)
    {
        if (is_string($menu)) return '#';
        if (is_array($menu)) $routeStr = $menu['route'] ?? '#';
        else $routeStr = $menu->route ?? '#';

        $title = is_array($menu) ? ($menu['title'] ?? '') : ($menu->title ?? '');
        $params = is_array($menu) ? ($menu['params'] ?? []) : ($menu->params ?? []);
        if (is_string($params)) $params = json_decode($params, true);
        if (!is_array($params)) $params = [];

        if (!$routeStr || $routeStr === '#') {
            if ($title === 'Categories') return route('admin.categories.index', $params);
            if ($title === 'Tags') return route('admin.tags.index', $params);
            if ($title === 'All Posts') return route('admin.posts.index', $params);
            if ($title === 'Add Post') return route('admin.posts.create', $params);
            if ($title === 'All Pages') return route('admin.pages.index', $params);
            if ($title === 'Add New' || $title === 'Add Page') return route('admin.pages.create', $params);
            if ($title === 'Comments') return route('admin.comments.index', $params);

            try {
                $postType = \Acme\CmsDashboard\Models\PostType::where('name', $title)->first();
                if ($postType) {
                    return route('admin.posts.index', ['type' => $postType->slug]);
                }
            } catch (\Exception $e) {}

            if ($title === 'Tools') {
                if (auth()->user()->hasPermission('access_backup_restore') || auth()->user()->hasPermission('manage_settings')) {
                    if (Route::has('admin.backup.index')) return route('admin.backup.index');
                }
                if (auth()->user()->hasPermission('access_languages')) {
                    if (Route::has('admin.languages.index')) return route('admin.languages.index');
                }
            }

            return '#';
        }

        if (str_starts_with($routeStr, '/') || str_starts_with($routeStr, 'http')) return url($routeStr);
        return Route::has($routeStr) ? route($routeStr, $params) : $routeStr;
    }

    public function getPermission($menu)
    {
        if (is_string($menu)) return 'access_dashboard';
        if (is_array($menu)) {
            if (!empty($menu['permission'])) return $menu['permission'];
            $title = strtolower($menu['title'] ?? '');
            $parentId = $menu['parent_id'] ?? null;
        } else {
            if (!empty($menu->permission)) return $menu->permission;
            $title = strtolower($menu->title ?? '');
            $parentId = $menu->parent_id ?? null;
        }
        
        if ($title === 'dashboard') return 'access_dashboard';
        if ($title === 'posts') return 'manage_posts';
        if ($title === 'pages') return 'manage_pages';
        if ($title === 'media') return 'manage_media';
        if ($title === 'users') return 'manage_users';
        if ($title === 'settings') return 'manage_settings';
        
        $titleStr = is_array($menu) ? ($menu['title'] ?? '') : ($menu->title ?? '');
        $slug = \Illuminate\Support\Str::slug($titleStr, '_');

        // Match RoleController unique slug logic for children
        if ($parentId && in_array($title, ['add new', 'categories', 'tags', 'all posts', 'all pages'])) {
            $parent = \Acme\CmsDashboard\Models\Menu::find($parentId);
            if ($parent) {
                $slug .= '_' . \Illuminate\Support\Str::slug($parent->title, '_');
            }
        }
        
        return 'access_' . $slug;
    }

    public function render()
    {
        return view('cms-dashboard::components.admin.sidebar', [
            'getPermission' => [$this, 'getPermission'],
            'resolveRoute' => [$this, 'resolveRoute'],
        ]);
    }
}
