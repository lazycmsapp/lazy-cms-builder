<?php

namespace Acme\CmsDashboard;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class CmsDashboardServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->role && $user->role->slug === 'super-admin' ? true : null;
        });

        // Share activeTheme globally with all views
        $activeTheme = get_cms_option('active_theme', 'lazy-theme');
        view()->share('activeTheme', $activeTheme);

        // Register Middlewares
        $this->app['router']->prependMiddlewareToGroup('web', \Acme\CmsDashboard\Http\Middleware\RedirectMiddleware::class);
        $this->app['router']->pushMiddlewareToGroup('web', \Acme\CmsDashboard\Http\Middleware\TrackVisits::class);
        $this->app['router']->pushMiddlewareToGroup('web', \Acme\CmsDashboard\Http\Middleware\LocalizationMiddleware::class);
        $this->app['router']->pushMiddlewareToGroup('web', \Acme\CmsDashboard\Http\Middleware\BuilderShortcodeMiddleware::class);

        $this->app->booted(function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'cms-dashboard');

        // Register View Composers for Magic Keys
        $viewMap = [
            'admin.users.edit' => 'users-edit',
            'admin.settings.index' => 'general-settings',
            
            'cms-dashboard::admin.users.edit' => 'users-edit',
            'cms-dashboard::admin.settings.index'         => 'general-settings',
        ];

        view()->composer('*', function ($view) use ($viewMap) {
            $viewName = $view->getName();
            $magicKey = $viewMap[$viewName] ?? null;

            if ($magicKey) {
                $dynamicFields = config("lazy-options.hooks.{$magicKey}.fields", []);
                $settings = \Illuminate\Support\Facades\DB::table('cms_settings')->pluck('value', 'key')->toArray();
                $view->with(compact('dynamicFields', 'settings'));
            }
        });

        Blade::componentNamespace('Acme\\CmsDashboard\\View\\Components', 'cms-dashboard');
        Blade::component('cms-dashboard::components.frontend.breadcrumbs', 'cms-breadcrumbs');

        // Register commands always (not just in console) so Artisan::call() works from web requests
        $this->commands([
            \Acme\CmsDashboard\Console\Commands\LazyList::class,
            \Acme\CmsDashboard\Console\Commands\MakeDashboardPage::class,
            \Acme\CmsDashboard\Console\Commands\InstallLazyCms::class,
            \Acme\CmsDashboard\Console\Commands\SeedLazyCms::class,
            \Acme\CmsDashboard\Console\Commands\UpdateLazyCms::class,
        ]);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/cms-dashboard'),
            ], 'cms-dashboard-views');

            $this->publishes([
                __DIR__ . '/../resources/views/themes' => resource_path('views/themes'),
            ], 'cms-dashboard-themes');

            $this->publishes([
                __DIR__ . '/../public/assets' => public_path('vendor/cms-dashboard'),
            ], 'cms-dashboard-assets');

            // 1. Views & Themes
            $this->publishes([
                __DIR__ . '/../resources/views/themes' => resource_path('views/themes'),
            ], 'lazy-themes');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/cms-dashboard'),
            ], 'lazy-views');
        }
    }

    public function register(): void
    {
        require_once __DIR__ . '/helpers.php';
        require_once __DIR__ . '/ecommerce_helpers.php';
        $this->mergeConfigFrom(__DIR__ . '/../config/lazy-options.php', 'lazy-options');

        // 1. Get Active Theme
        // We use a simple way to get it since DB might not be ready in early register
        $activeTheme = 'lazy-theme';
        try {
            // Check if we are running in web context and can access DB
            if (!$this->app->runningInConsole()) {
                $setting = \Illuminate\Support\Facades\DB::table('cms_settings')->where('key', 'active_theme')->first();
                if ($setting) $activeTheme = $setting->value;
            }
        } catch (\Exception $e) {}

        // 2. Load theme-specific functions.php (For Hooks/Logic)
        $functionsFile = resource_path("views/themes/{$activeTheme}/functions.php");
        if (!file_exists($functionsFile)) {
            $functionsFile = __DIR__ . "/../resources/views/themes/{$activeTheme}/functions.php";
        }
        
        if (file_exists($functionsFile)) {
            require_once $functionsFile;
        }

        // 3. Load theme-specific options.php (For Admin UI Config)
        $optionsFile = resource_path("views/themes/{$activeTheme}/options.php");
        if (!file_exists($optionsFile)) {
            $optionsFile = __DIR__ . "/../resources/views/themes/{$activeTheme}/options.php";
        }
        
        $themeOptions = [];
        if (file_exists($optionsFile)) {
            require_once $optionsFile;
        }

        // 4. Merge and Filter Options
        $baseOptions = config('lazy-options', []);
        
        // Merge themeOptions array if defined in options.php
        if (!empty($themeOptions)) {
            $baseOptions = array_replace_recursive($baseOptions, $themeOptions);
        }

        // Apply filters so users can add options via functions.php hooks
        $finalOptions = apply_lazy_filters('cms_theme_options', $baseOptions);
        
        // 5. Provide specific filters for Magic Keys (to make it cleaner for developers)
        if (isset($finalOptions['hooks'])) {
            foreach ($finalOptions['hooks'] as $key => $hookData) {
                // Example tag: lazy_general_settings_fields
                $filterTag = 'lazy_' . str_replace('-', '_', $key) . '_fields';
                $finalOptions['hooks'][$key]['fields'] = apply_lazy_filters($filterTag, $finalOptions['hooks'][$key]['fields'] ?? []);
            }
        }

        config(['lazy-options' => $finalOptions]);

        // 6. Set Theme Path as Priority
        $themePath = resource_path("views/themes/{$activeTheme}");
        if (!file_exists($themePath)) {
            $themePath = __DIR__ . "/../resources/views/themes/{$activeTheme}";
        }

        // We only add it to the top. We do NOT remove the root to avoid breaking Laravel
        $paths = config('view.paths', []);
        array_unshift($paths, $themePath);
        config(['view.paths' => array_unique($paths)]);
    }
}
