<?php

namespace Acme\CmsDashboard\Http\Controllers;

use Illuminate\Routing\Controller;
use Acme\CmsDashboard\Models\Post;
use Acme\CmsDashboard\Models\PostType;
use Acme\CmsDashboard\Models\Category;
use Acme\CmsDashboard\Models\Tag;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $posts = collect();
        $categories = collect();
        $tags = collect();

        // 1. Posts
        if (get_cms_option('sitemap_include_posts', '1') == '1') {
            $postItems = Post::where('type', 'post')
                ->where('status', 'published')
                ->latest()
                ->get();
            $posts = $posts->merge($postItems);
        }

        // 2. Pages
        if (get_cms_option('sitemap_include_pages', '1') == '1') {
            $pageItems = Post::where('type', 'page')
                ->where('status', 'published')
                ->latest()
                ->get();
            $posts = $posts->merge($pageItems);
        }

        // 2. Custom Post Types
        $cpts = PostType::where('is_builtin', false)->get();
        foreach ($cpts as $cpt) {
            if (get_cms_option('sitemap_include_cpt_' . $cpt->slug, '1') == '1') {
                $cptPosts = Post::where('type', $cpt->slug)
                    ->where('status', 'published')
                    ->latest()
                    ->get();
                $posts = $posts->merge($cptPosts);
            }
        }

        // 3. Categories
        if (get_cms_option('sitemap_include_categories', '1') == '1') {
            $categories = Category::has('posts')->get();
        }

        // 4. Tags
        if (get_cms_option('sitemap_include_tags', '0') == '1') {
            $tags = Tag::has('posts')->get();
        }

        $xml = view('cms-dashboard::frontend.sitemap', compact('posts', 'categories', 'tags'))->render();

        return Response::make($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
