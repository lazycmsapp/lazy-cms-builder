<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Acme\CmsDashboard\Models\Widget;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    public function index()
    {
        $widgetAreas = [
            'primary-sidebar' => 'Primary Sidebar',
            'footer-1' => 'Footer Column 1',
            'footer-2' => 'Footer Column 2',
            'footer-3' => 'Footer Column 3',
            'footer-4' => 'Footer Column 4',
        ];

        $availableWidgets = [
            'search' => [
                'name' => 'Search Bar',
                'description' => 'A simple search form for your site.',
                'settings' => ['placeholder' => 'Search...']
            ],
            'recent_posts' => [
                'name' => 'Recent Posts',
                'description' => 'Displays a list of your most recent posts.',
                'settings' => ['limit' => 5]
            ],
            'categories' => [
                'name' => 'Categories List',
                'description' => 'Displays a list of categories or taxonomies.',
                'settings' => ['taxonomy' => 'category']
            ],
            'custom_html' => [
                'name' => 'Custom HTML',
                'description' => 'Add arbitrary HTML code.',
                'settings' => ['content' => '']
            ],
        ];

        // Scan active theme for custom widgets
        $activeTheme = get_cms_option('active_theme', 'lazy-theme');
        $themeWidgetPath = base_path("vendor/tareqcodex/lazy-cms-rebuild/resources/views/themes/{$activeTheme}/widgets");
        
        if (is_dir($themeWidgetPath)) {
            $files = scandir($themeWidgetPath);
            foreach ($files as $file) {
                if (str_ends_with($file, '.blade.php')) {
                    $slug = str_replace('.blade.php', '', $file);
                    if (!isset($availableWidgets[$slug])) {
                        $availableWidgets[$slug] = [
                            'name' => ucwords(str_replace(['-', '_'], ' ', $slug)),
                            'description' => "Custom widget provided by {$activeTheme} theme.",
                            'settings' => []
                        ];
                    }
                }
            }
        }

        $activeWidgets = Widget::orderBy('order')->get()->groupBy('area');

        return view('cms-dashboard::admin.widgets.index', compact('widgetAreas', 'availableWidgets', 'activeWidgets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'area' => 'required|string',
            'type' => 'required|string',
        ]);

        $order = Widget::where('area', $request->area)->max('order') + 1;

        $widget = Widget::create([
            'area' => $request->area,
            'type' => $request->type,
            'title' => ucwords(str_replace('_', ' ', $request->type)),
            'settings' => [],
            'order' => $order,
        ]);

        return back()->with('success', 'Widget added successfully!');
    }

    public function update(Request $request, Widget $widget)
    {
        $widget->update($request->only(['title', 'settings', 'order', 'is_active']));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Widget updated successfully!']);
        }

        return back()->with('success', 'Widget updated successfully!');
    }

    public function destroy(Widget $widget)
    {
        $widget->delete();
        return back()->with('success', 'Widget removed successfully!');
    }

    public function updateOrder(Request $request)
    {
        foreach ($request->widgets as $data) {
            Widget::where('id', $data['id'])->update(['order' => $data['order'], 'area' => $data['area']]);
        }
        return response()->json(['status' => 'success']);
    }
}
