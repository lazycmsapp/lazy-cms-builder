<?php

namespace Acme\CmsDashboard\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Acme\CmsDashboard\Models\Post;
use Acme\CmsDashboard\Http\Resources\PostResource;
use Acme\CmsDashboard\Models\Category;
use Illuminate\Support\Facades\DB;

class CmsApiController extends Controller
{
    public function __construct()
    {
        if (get_cms_option('enable_rest_api', '1') !== '1') {
            abort(403, 'REST API is disabled in settings.');
        }
    }

    /**
     * Get list of posts
     */
    public function posts(Request $request)
    {
        $limit = $request->query('limit', 10);
        $type = $request->query('type', 'post');
        
        $posts = Post::where('type', $type)
            ->where('status', 'published')
            ->with(['user', 'categories', 'tags'])
            ->latest()
            ->paginate($limit);

        return PostResource::collection($posts);
    }

    /**
     * Get single post by slug
     */
    public function singlePost($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->with(['user', 'categories', 'tags'])
            ->firstOrFail();

        return new PostResource($post);
    }

    /**
     * Get settings
     */
    public function settings()
    {
        $settings = DB::table('cms_settings')->pluck('value', 'key');
        
        // Filter out sensitive settings if needed
        $publicSettings = $settings->only([
            'site_title', 'tagline', 'timezone', 'home_page_id'
        ]);

        return response()->json([
            'success' => true,
            'data' => $publicSettings
        ]);
    }

    /**
     * Get navigation menus
     */
    public function menus()
    {
        $menus = \Acme\CmsDashboard\Models\Menu::with('items')->get();
        return response()->json([
            'success' => true,
            'data' => $menus
        ]);
    }
}
