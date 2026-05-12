<x-cms-dashboard::layouts.admin>
    <x-slot name="title">Documentation - Lazy CMS</x-slot>

    <div class="px-6 py-4">
        <div class="flex items-center justify-between mb-8 border-b border-gray-200 pb-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900">Developer Documentation</h1>
                <p class="text-gray-500 mt-1">Master Lazy CMS and build stunning websites with freedom.</p>
            </div>
            <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-bold border border-blue-100">
                v4.0.0 Stable
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {{-- Navigation Sidebar --}}
            <div class="lg:col-span-1">
                <nav class="sticky top-6 space-y-1" id="doc-nav">
                    <a href="#getting-started" class="nav-link block px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-md transition-all duration-200">Getting Started</a>
                    <a href="#updating" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">Updating & Syncing</a>
                    <a href="#page-generation" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">Page Generation</a>
                    <a href="#custom-routes" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">Custom Routes</a>
                    <a href="#helpers" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">Helper Functions</a>
                    <a href="#loops" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">Displaying Posts (Loops)</a>
                    <a href="#custom-options" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">Custom Settings & Options</a>
                    <a href="#seo" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">SEO & Metadata</a>
                    <a href="#templates" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">Custom Templates</a>
                    <a href="#custom-widgets" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">Custom Widgets</a>
                    <a href="#hooks" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">Hooks (Actions & Filters)</a>
                    <a href="#rest-api" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">REST API & Headless</a>
                    <a href="#multilingual" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">Multilingual & Localization</a>
                    <a href="#rbac" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">Roles & Permissions (RBAC)</a>
                    <a href="#theme-isolation" class="nav-link block px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-all duration-200">Theme Isolation & Sync</a>
                </nav>
            </div>

            {{-- Content --}}
            <div class="lg:col-span-3 space-y-12 pb-20">
                
                {{-- Section: Getting Started --}}
                <section id="getting-started">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Getting Started</h2>
                    <p class="text-gray-700 mb-6">Lazy CMS is designed to give you full control over your content while keeping the development process simple.</p>
                    
                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm mb-8">
                        <h3 class="font-bold text-gray-800 mb-4">Fresh Installation</h3>
                        <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs space-y-4">
                            <div>
                                <span class="text-gray-500"># 1. Install via composer</span><br>
                                <code class="text-green-400">composer require tareqcodex/lazy-cms-rebuild</code>
                            </div>
                            <div>
                                <span class="text-gray-500"># 2. Run CMS installer</span><br>
                                <code class="text-green-400">php artisan lazy:install</code>
                                <p class="text-[10px] text-gray-400 mt-1 italic">// This handles migrations, assets, themes, and admin creation.</p>
                            </div>
                            <div>
                                <span class="text-gray-500"># 3. View all available commands</span><br>
                                <code class="text-green-400">php artisan lazy</code>
                                <p class="text-[10px] text-gray-400 mt-1 italic">// Lists all specialized Lazy CMS commands.</p>
                            </div>
                            <div>
                                <span class="text-gray-500"># 4. Seed demo data (Optional)</span><br>
                                <code class="text-green-400">php artisan lazy:seed</code>
                                <p class="text-[10px] text-gray-400 mt-1 italic">// Populates default menus and initial demo content.</p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Section: Updating --}}
                <section id="updating">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Updating & Syncing</h2>
                    <p class="text-gray-700 mb-6">Whenever you update the package or make changes to the core files, use the built-in update command to keep everything in sync.</p>

                    <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs space-y-4">
                        <div>
                            <span class="text-gray-500"># 1. Update composer package</span><br>
                            <code class="text-green-400">composer require tareqcodex/lazy-cms-rebuild</code>
                        </div>
                        <div>
                            <span class="text-gray-500"># 2. Run Sync Command</span><br>
                            <code class="text-green-400">php artisan lazy:update</code>
                            <p class="text-[10px] text-gray-400 mt-1 italic">// This automates migrations, refreshing assets/themes, and clearing cache.</p>
                        </div>
                    </div>
                </section>

                {{-- Section: Page Generation --}}
                <section id="page-generation">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Page Generation</h2>
                    <p class="text-gray-700 mb-6">Quickly scaffold new dashboard pages, controllers, and menu items with a single command.</p>

                    <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs space-y-4">
                        <div>
                            <span class="text-gray-500"># Create a new dashboard page (e.g. Portfolio)</span><br>
                            <code class="text-green-400">php artisan make:lazy-page Portfolio</code>
                            <p class="text-[10px] text-gray-400 mt-1 italic">// This creates a controller, a view, and adds a menu item in the sidebar.</p>
                        </div>
                    </div>
                </section>

                {{-- Section: Custom Routes --}}
                <section id="custom-routes">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Custom Routes</h2>
                    <p class="text-gray-700 mb-4">You can define your own routes in <code>routes/web.php</code>. These will take precedence over the CMS catch-all routes.</p>
                    <div class="bg-gray-900 rounded-xl p-6 text-gray-300 font-mono text-sm overflow-x-auto">
                        <pre><code>// Example: Custom Route for Blogs
Route::get('/blogs', function () {
    return view('blogs');
});</code></pre>
                    </div>
                </section>

                {{-- Section: Helpers --}}
                <section id="helpers">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Global Helper Functions</h2>
                    <div class="space-y-6">
                        {{-- get_lazy_posts --}}
                        <div class="border border-gray-200 rounded-xl p-6 bg-white shadow-sm">
                            <h3 class="font-bold text-blue-600 mb-2">get_lazy_posts($args)</h3>
                            <p class="text-sm text-gray-600 mb-2">Fetch posts with advanced options like pagination and filtering.</p>
                            <code class="block bg-gray-50 p-3 rounded text-sm mb-2">$posts = get_lazy_posts(['post_type' => 'post', 'limit' => 10, 'paginate' => true]);</code>
                        </div>

                        {{-- the_lazy_pagination --}}
                        <div class="border border-gray-200 rounded-xl p-6 bg-white shadow-sm">
                            <h3 class="font-bold text-blue-600 mb-2">the_lazy_pagination($items, $view)</h3>
                            <p class="text-sm text-gray-600 mb-4">Render pagination links with custom design support.</p>
                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs">
                                <pre><code>@verbatim
{!! the_lazy_pagination($postItems) !!}
{!! the_lazy_pagination($postItems, 'custom-view') !!}
@endverbatim</code></pre>
                            </div>
                        </div>

                        {{-- get_lazy_post --}}
                        <div class="border border-gray-200 rounded-xl p-6 bg-white shadow-sm">
                            <h3 class="font-bold text-blue-600 mb-2">get_lazy_post($slugOrId)</h3>
                            <p class="text-sm text-gray-600 mb-2">Get a single post/page data by its slug or ID.</p>
                            <code class="block bg-gray-50 p-3 rounded text-sm">$post = get_lazy_post('about-us');</code>
                        </div>

                        {{-- get_cms_option --}}
                        <div class="border border-gray-200 rounded-xl p-6 bg-white shadow-sm">
                            <h3 class="font-bold text-blue-600 mb-2">get_cms_option($key, $default)</h3>
                            <p class="text-sm text-gray-600 mb-2">Get any setting value from the CMS settings table.</p>
                            <code class="block bg-gray-50 p-3 rounded text-sm">$site_name = get_cms_option('site_title', 'Lazy CMS');</code>
                        </div>

                        {{-- get_lazy_excerpt --}}
                        <div class="border border-gray-200 rounded-xl p-6 bg-white shadow-sm">
                            <h3 class="font-bold text-blue-600 mb-2">get_lazy_excerpt($post, $limit)</h3>
                            <p class="text-sm text-gray-600 mb-2">Extracts plain text from builder JSON or rich text content.</p>
                            <code class="block bg-gray-50 p-3 rounded text-sm">$excerpt = get_lazy_excerpt($post, 150);</code>
                        </div>

                        {{-- get_lazy_categories --}}
                        <div class="border border-gray-200 rounded-xl p-6 bg-white shadow-sm">
                            <h3 class="font-bold text-blue-600 mb-2">get_lazy_categories($taxonomy)</h3>
                            <p class="text-sm text-gray-600 mb-2">Fetch all categories or custom taxonomy terms.</p>
                            <code class="block bg-gray-50 p-3 rounded text-sm">$cats = get_lazy_categories('category');</code>
                        </div>
                    </div>
                </section>

                {{-- Section: Archives --}}
                <section id="archives">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Archive & Filtering</h2>
                    <p class="text-gray-700 mb-4">CMS automatically handles archive pages for categories and tags.</p>
                    <div class="bg-gray-100 p-4 rounded-lg space-y-2 text-sm font-mono text-gray-700">
                        <div><span class="text-blue-600 font-bold">Category URL:</span> /category/{slug}</div>
                        <div><span class="text-blue-600 font-bold">Tag URL:</span> /tag/{slug}</div>
                    </div>
                </section>

                {{-- Section: Loops --}}
                <section id="loops">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Displaying Posts (Loops)</h2>
                    <p class="text-gray-700 mb-4">Use <code>the_lazy_loop()</code> for a quick grid, or write your own <code>@@foreach</code> for total freedom.</p>
                    
                    <h3 class="font-bold mt-6 mb-2">Method 1: Fast Grid</h3>
                    <div class="bg-gray-900 rounded-xl p-6 text-gray-300 font-mono text-sm mb-6">
                        <pre><code>the_lazy_loop(['post_type' => 'post', 'limit' => 6]);</code></pre>
                    </div>

                    <h3 class="font-bold mb-2">Method 2: Custom HTML with Pagination (Recommended)</h3>
                    <div class="bg-gray-900 rounded-xl p-6 text-gray-300 font-mono text-sm">
                        <pre><code>@verbatim
@php $items = get_lazy_posts(['post_type' => 'post', 'limit' => 6, 'paginate' => true]); @endphp

@foreach($items as $post)
    <div class="card">
        <h2>{{ $post->title }}</h2>
        <a href="{{ route('frontend.category', $post->categories->first()->slug) }}">
            {{ $post->categories->first()->name }}
        </a>
    </div>
@endforeach

<div class="pagination">
    {!! the_lazy_pagination($items) !!}
</div>
@endverbatim</code></pre>
                    </div>
                </section>

                {{-- Section: Custom Settings & Options Pages --}}
                <section id="custom-options">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Custom Settings & Options Pages</h2>
                    <p class="text-gray-700 mb-6">You can extend the CMS by adding new fields to existing settings or creating entirely new admin pages via <code>config/lazy-options.php</code>.</p>

                    <div class="space-y-8">
                        {{-- Adding to Main Settings --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-blue-600 mb-3">1. Add Fields to Main Settings</h3>
                            <p class="text-sm text-gray-600 mb-4">To add new inputs to the <b>General Settings</b> page, use the <code>hooks</code> array in your config.</p>
                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs overflow-x-auto">
                                <pre><code>@verbatim
// config/lazy-options.php
'hooks' => [
    'general-settings' => [
        'fields' => [
            'site_tagline' => [
                'type' => 'text',
                'label' => 'Site Tagline',
                'placeholder' => 'Enter slogan...',
            ],
        ]
    ]
]
@endverbatim</code></pre>
                            </div>
                        </div>

                        {{-- Creating New Pages --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-blue-600 mb-3">2. Create a Custom Admin Page</h3>
                            <p class="text-sm text-gray-600 mb-4">You can create standalone pages that appear in the sidebar using the <code>pages</code> array.</p>
                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs overflow-x-auto">
                                <pre><code>@verbatim
// config/lazy-options.php
'pages' => [
    'theme-settings' => [
        'title' => 'Theme Options',
        'icon'  => 'palette',
        'group' => 'Appearance',
        'fields' => [
            'primary_color' => [
                'type' => 'text',
                'label' => 'Primary Brand Color',
                'default' => '#007bff'
            ],
            'footer_text' => [
                'type' => 'textarea',
                'label' => 'Footer Copyright Text',
            ],
        ]
    ]
]
@endverbatim</code></pre>
                            </div>
                        </div>

                        {{-- Displaying Values --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-blue-600 mb-3">3. How to Show Values in Frontend</h3>
                            <p class="text-sm text-gray-600 mb-4">Values are automatically saved to the database. Use <code>get_cms_option()</code> to retrieve them anywhere.</p>
                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs space-y-4">
                                <div>
                                    <span class="text-gray-500">// Get simple text value</span><br>
                                    <code class="text-blue-400">@verbatim
{{ get_cms_option('site_tagline') }}
@endverbatim</code>
                                </div>
                                <div>
                                    <span class="text-gray-500">// Get image URL</span><br>
                                    <code class="text-blue-400">@verbatim
<img src="{{ asset(get_cms_option('header_logo')) }}">
@endverbatim</code>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Section: SEO & Metadata --}}
                <section id="seo">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">SEO & Metadata</h2>
                    <p class="text-gray-700 mb-6">Lazy CMS provides a built-in SEO engine that handles meta tags, social sharing (OpenGraph/X), and JSON-LD schema markup automatically.</p>

                    <div class="space-y-8">
                        {{-- Method 1: Automatic --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-blue-600 mb-3">1. The Automatic Component (Best)</h3>
                            <p class="text-sm text-gray-600 mb-4">Add this single line inside your <code>&lt;head&gt;</code> tag. It will handle everything based on the current post or page.</p>
                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs overflow-x-auto">
                                <pre><code>@verbatim
<!-- Inside layout/app.blade.php head section -->
<x-cms-dashboard::frontend.seo-meta :post="$post ?? null" :title="$title ?? null" />
@endverbatim</code></pre>
                            </div>
                        </div>

                        {{-- Method 2: Manual Access --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-blue-600 mb-3">2. Manual Value Access</h3>
                            <p class="text-sm text-gray-600 mb-4">If you want to access specific SEO values manually, use the <code>seo_meta</code> array on the post object.</p>
                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs space-y-4">
                                <pre><code>@verbatim
@php $seo = $post->seo_meta; @endphp

<!-- Get Meta Title -->
{{ $seo['title'] ?? $post->title }}

<!-- Get OpenGraph Image -->
@if(!empty($seo['og_image']))
    <meta property="og:image" content="{{ asset('storage/' . $seo['og_image']) }}">
@endif
@endverbatim</code></pre>
                            </div>
                        </div>

                        {{-- Section: Sitemap & Robots --}}
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-blue-700 mb-2">Sitemap & Robots.txt</h3>
                            <p class="text-sm text-blue-600">These files are served dynamically at the root of your site:</p>
                            <ul class="mt-3 list-disc list-inside text-sm text-blue-600 space-y-1">
                                <li><code>your-site.com/sitemap.xml</code></li>
                                <li><code>your-site.com/robots.txt</code></li>
                            </ul>
                        </div>
                    </div>
                </section>

                {{-- Section: Templates --}}
                <section id="templates">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Theme Development Guide</h2>
                    <p class="text-gray-700 mb-6">Lazy CMS allows you to build custom themes with total creative freedom. Follow this guide to create your first theme.</p>

                    <div class="space-y-8">
                        {{-- Theme Structure --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-blue-600 mb-3">1. Theme Structure & Location</h3>
                            <p class="text-sm text-gray-600 mb-4">Themes are located in <code>resources/views/themes/{theme-name}/</code>. A standard theme should follow this structure:</p>
                            <div class="bg-gray-100 p-4 rounded-lg font-mono text-xs text-gray-700 leading-relaxed">
                                your-theme-name/<br>
                                ├── layouts/ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; # Master layout (app.blade.php)<br>
                                ├── partials/ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; # Reusable parts (header, footer)<br>
                                ├── widgets/ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; # Custom theme widgets<br>
                                ├── index.blade.php &nbsp; # Home / Blog index<br>
                                ├── single.blade.php # Single post view<br>
                                ├── page.blade.php &nbsp;&nbsp; # Single page view<br>
                                ├── archive.blade.php # Category / Tag archives<br>
                                └── functions.php &nbsp;&nbsp; # Theme hooks & logic
                            </div>
                        </div>

                        {{-- Technical Requirements --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-blue-600 mb-3">2. Essential Technical Criteria</h3>
                            <p class="text-sm text-gray-600 mb-4">To ensure your theme works perfectly with core features, you must include these elements:</p>
                            
                            <div class="space-y-4">
                                <div class="p-3 bg-gray-50 rounded border-l-4 border-blue-400">
                                    <p class="text-xs font-bold text-gray-500 uppercase mb-1">SEO Support (In &lt;head&gt;)</p>
                                    <code class="text-xs">@verbatim
<x-cms-dashboard::frontend.seo-meta :post="$post ?? null" />
@endverbatim</code>
                                </div>

                                <div class="p-3 bg-gray-50 rounded border-l-4 border-blue-400">
                                    <p class="text-xs font-bold text-gray-500 uppercase mb-1">Head Hook (Before &lt;/head&gt;)</p>
                                    <code class="text-xs">@verbatim
{!! do_lazy_action('lazy_head') !!}
@endverbatim</code>
                                </div>

                                <div class="p-3 bg-gray-50 rounded border-l-4 border-blue-400">
                                    <p class="text-xs font-bold text-gray-500 uppercase mb-1">Footer Hook (Before &lt;/body&gt;)</p>
                                    <code class="text-xs">@verbatim
{!! do_lazy_action('lazy_footer') !!}
@endverbatim</code>
                                </div>
                            </div>
                        </div>

                        {{-- Template Hierarchy --}}
                        <div class="bg-amber-50 border border-amber-100 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-amber-800 mb-2">Template Overrides</h3>
                            <p class="text-sm text-amber-700">If you create a file in <code>resources/views/</code> that matches a page slug (e.g., <code>contact.blade.php</code>), it will override the theme's default <code>page.blade.php</code>.</p>
                        </div>
                    </div>
                </section>

                {{-- Section: Custom Widgets --}}
                <section id="custom-widgets">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Custom Widgets</h2>
                    <p class="text-gray-700 mb-6">Master the widget system by creating your own custom widgets within your theme. The system automatically detects any blade file in your theme's widget directory.</p>

                    <div class="space-y-8">
                        {{-- Step 1: Create File --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-blue-600 mb-3">1. Create Widget File</h3>
                            <p class="text-sm text-gray-600 mb-4">Create a new Blade file in your active theme's widget folder:</p>
                            <code class="block bg-gray-50 p-3 rounded text-sm mb-4">/resources/views/themes/lazy-theme/widgets/about-author.blade.php</code>
                            <p class="text-sm text-gray-600 mb-4">Once created, it will automatically appear in <b>Appearance > Widgets</b> as "About Author".</p>
                        </div>

                        {{-- Step 2: Example Code --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-blue-600 mb-3">2. Example Widget Code</h3>
                            <p class="text-sm text-gray-600 mb-4">Use the <code>$widget</code> variable to access settings and title.</p>
                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs overflow-x-auto">
                                <pre><code>@verbatim
<!-- about-author.blade.php -->
<div class="widget mb-10 p-6 bg-gray-50 rounded-xl">
    @if($widget->title)
        <h4 class="widget-title text-xl font-bold mb-4">{{ $widget->title }}</h4>
    @endif
    
    <div class="author-box flex items-center gap-4">
        <img src="{{ asset('theme/images/avatar.jpg') }}" class="w-16 h-16 rounded-full">
        <div>
            <p class="text-gray-600 text-sm">Hello, I'm a passionate developer building amazing things with Lazy CMS.</p>
        </div>
    </div>
</div>
@endverbatim</code></pre>
                            </div>
                        </div>

                        {{-- Step 3: Registering in Theme --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-blue-600 mb-3">3. Rendering Widgets</h3>
                            <p class="text-sm text-gray-600 mb-4">To display a widget area (like a sidebar) in your theme, use the global helper:</p>
                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs">
                                <pre><code>@verbatim
<!-- In your sidebar.blade.php -->
{!! render_lazy_widgets('primary-sidebar') !!}
@endverbatim</code></pre>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Section: Hooks System --}}
                <section id="hooks">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Hooks System (Actions & Filters)</h2>
                    <p class="text-gray-700 mb-6">Lazy CMS features a powerful hook architecture similar to WordPress, allowing you to extend the core functionality without modifying package files.</p>

                    {{-- Theme Functions.php --}}
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 mb-8">
                        <h3 class="text-lg font-bold text-blue-700 mb-2">Theme Functions File</h3>
                        <p class="text-sm text-blue-600 mb-4">Just like WordPress, you can create a <code>functions.php</code> file inside your theme folder to register hooks, add custom logic, or include scripts.</p>
                        <code class="block bg-white/50 p-2 rounded text-xs border border-blue-200">/resources/views/themes/lazy-theme/functions.php</code>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Actions --}}
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                                Action Hooks
                            </h3>
                            <p class="text-sm text-gray-600">Actions allow you to "do something" at specific points in the page lifecycle.</p>
                            
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Frontend Actions</h4>
                                <ul class="text-xs space-y-1 text-gray-700 mb-4">
                                    <li><code>lazy_head</code> - Inside frontend &lt;head&gt;</li>
                                    <li><code>lazy_footer</code> - Before frontend &lt;/body&gt;</li>
                                    <li><code>lazy_before_content</code> - Above post body</li>
                                    <li><code>lazy_after_content</code> - Below post body</li>
                                </ul>

                                <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Admin Panel Actions</h4>
                                <ul class="text-xs space-y-1 text-gray-700">
                                    <li><code>lazy_admin_head</code> - Inside admin &lt;head&gt;</li>
                                    <li><code>lazy_admin_footer</code> - Before admin &lt;/body&gt;</li>
                                    <li><code>lazy_admin_bar_right_before</code> - Top bar right side</li>
                                    <li><code>lazy_settings_form_top</code> - General Settings top</li>
                                    <li><code>lazy_settings_form_bottom</code> - General Settings bottom</li>
                                    <li><code>lazy_seo_settings_form_top</code> - SEO Settings top</li>
                                    <li><code>lazy_seo_settings_form_bottom</code> - SEO Settings bottom</li>
                                </ul>
                            </div>

                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs">
                                <pre><code>@verbatim
// Example: Add CSS to Admin
add_lazy_action('lazy_admin_head', function() {
    echo "<style>body { border-top: 4px solid red; }</style>";
});
@endverbatim</code></pre>
                            </div>
                        </div>

                        {{-- Filters --}}
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                                Filter Hooks
                            </h3>
                            <p class="text-sm text-gray-600">Filters allow you to modify data before it is rendered or saved.</p>

                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Available Filters</h4>
                                <ul class="text-xs space-y-2 text-gray-700">
                                    <li><code>lazy_the_content</code> - Filters post body HTML</li>
                                    <li><code>lazy_post_title</code> - Filters post title</li>
                                    <li><code>cms_theme_options</code> - Modify theme settings pages (via functions.php)</li>
                                    <li><code>lazy_general_settings_fields</code> - Simplified filter for General Settings</li>
                                    <li><code>lazy_users_edit_fields</code> - Simplified filter for User Edit page</li>
                                </ul>
                            </div>

                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs">
                                <pre><code>@verbatim
// Example: Simplified Field Hook
add_lazy_filter('lazy_general_settings_fields', function($fields) {
    $fields['copyright_text'] = [
        'type' => 'text',
        'label' => 'Copyright'
    ];
    return $fields;
});
@endverbatim</code></pre>
                            </div>

                            <h3 class="text-lg font-bold text-gray-800 mt-6 flex items-center gap-2">
                                <span class="w-2 h-2 bg-red-600 rounded-full"></span>
                                Removing Hooks & Fields
                            </h3>
                            <p class="text-sm text-gray-600">You can unregister any previously added hook or remove dynamic fields using removal helpers and high-priority filters.</p>
                            
                            <h4 class="text-xs font-bold text-gray-500 uppercase mt-4 mb-2">1. Removing Hooks</h4>
                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs mb-4">
                                <pre><code>@verbatim
remove_lazy_action('tag', 'callback', $priority);
remove_lazy_filter('tag', 'callback', $priority);
@endverbatim</code></pre>
                            </div>

                            <h4 class="text-xs font-bold text-gray-500 uppercase mt-4 mb-2">2. Removing Dynamic Fields</h4>
                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs">
                                <pre><code>@verbatim
add_lazy_filter('lazy_general_settings_fields', function($fields) {
    unset($fields['copyright_text']); // Remove a specific field
    return $fields;
}, 20); // Priority 20 to run after adding
@endverbatim</code></pre>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Multilingual & Localization Section -->
                <section id="multilingual" class="doc-section mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Multilingual & Localization</h2>
                    <p class="text-gray-700 mb-6">Reach a global audience with built-in translation support and dynamic URL structures.</p>

                    <div class="space-y-8">
                        {{-- Dynamic URL Logic --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-blue-600 mb-3">1. Dynamic URL Logic</h3>
                            <p class="text-sm text-gray-600 mb-4">The system automatically detects if multi-language is enabled. If disabled, your site uses clean URLs (e.g. <code>/about-us</code>). If enabled, it uses ISO prefixes (e.g. <code>/en/about-us</code>).</p>
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 text-xs text-blue-700">
                                <b>Auto-Hiding:</b> The language selector in the admin panel automatically hides itself when multi-language support is turned off.
                            </div>
                        </div>

                        {{-- Translatable Fields --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-blue-600 mb-3">2. Translatable Option Fields</h3>
                            <p class="text-sm text-gray-600 mb-4">To make a custom option field translatable, simply add <code>'translatable' => true</code> to the field definition in <code>config/lazy-options.php</code>.</p>
                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs overflow-x-auto">
                                <pre><code>@verbatim
// config/lazy-options.php
'fields' => [
    'footer_text' => [
        'type' => 'textarea',
        'label' => 'Footer Copyright',
        'translatable' => true, // Enables per-language inputs
    ],
]
@endverbatim</code></pre>
                            </div>
                        </div>

                        {{-- Language Switcher --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-blue-600 mb-3">3. Language Switcher Helpers</h3>
                            <p class="text-sm text-gray-600 mb-4">Use these helpers to render switchers in your frontend themes:</p>
                            <div class="bg-100 p-4 rounded-lg font-mono text-xs text-gray-700 space-y-2">
                                <code>{!! lazy_lang_dropdown() !!}</code> - A sleek dropdown with flags.<br>
                                <code>{!! lazy_lang_switcher() !!}</code> - A simple list/flex switcher.
                            </div>
                        </div>
                    </div>
                </section>

                <!-- REST API Section -->
                <section id="rest-api" class="doc-section mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">REST API & Headless</h2>
                    <p class="text-gray-700 mb-6">Build modern frontends with React, Vue, or Mobile apps using the built-in API.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-gray-800">Available Endpoints</h3>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <ul class="text-xs space-y-3 text-gray-700">
                                    <li class="flex items-center justify-between border-b border-gray-100 pb-2">
                                        <code class="text-blue-600">GET /api/v1/posts</code>
                                        <span class="text-[10px] font-bold text-gray-400">List all posts</span>
                                    </li>
                                    <li class="flex items-center justify-between border-b border-gray-100 pb-2">
                                        <code class="text-blue-600">GET /api/v1/settings</code>
                                        <span class="text-[10px] font-bold text-gray-400">Site settings</span>
                                    </li>
                                    <li class="flex items-center justify-between border-b border-gray-100 pb-2">
                                        <code class="text-blue-600">GET /api/v1/menus</code>
                                        <span class="text-[10px] font-bold text-gray-400">Navigation</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-gray-800">Filtering API Data</h3>
                            <p class="text-xs text-gray-600">Modify JSON responses using theme hooks:</p>
                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-[10px]">
                                <pre><code>@verbatim
add_lazy_filter('lazy_api_post_data', function($data, $post) {
    $data['reading_time'] = '5 min read';
    return $data;
});
@endverbatim</code></pre>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Section: RBAC --}}
                <section id="rbac">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Roles & Permissions (RBAC)</h2>
                    <p class="text-gray-700 mb-6">Version 4.0.0 introduces a powerful granular permission system. Access is controlled via Roles that are linked to specific Permissions.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                            <h3 class="font-bold text-gray-800">Predefined Roles</h3>
                            <ul class="mt-2 space-y-1 text-sm text-gray-600">
                                <li><b>Super Admin:</b> Unrestricted access.</li>
                                <li><b>Administrator:</b> Full settings & content access.</li>
                                <li><b>Editor:</b> Manage all posts & comments.</li>
                                <li><b>Author:</b> Manage only their own posts.</li>
                                <li><b>Contributor:</b> Write posts (pending review).</li>
                                <li><b>User:</b> Basic dashboard access.</li>
                            </ul>
                        </div>
                        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                            <h3 class="font-bold text-gray-800">Permission Syncing</h3>
                            <p class="text-xs text-gray-500 mt-2">When you add new menus or features, run the sync command to update permissions:</p>
                            <code class="block bg-gray-50 p-2 mt-2 text-[10px]">php artisan lazy:update</code>
                        </div>
                    </div>
                </section>

                {{-- Section: Theme Isolation --}}
                <section id="theme-isolation">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Theme Isolation & Sync</h2>
                    <p class="text-gray-700 mb-6">To maintain a clean and secure structure, Lazy CMS enforces strict theme-only view rendering for the frontend.</p>
                    
                    <div class="space-y-6">
                        <div class="bg-amber-50 border border-amber-100 p-4 rounded-xl text-sm text-amber-800">
                            <b>Strict Rule:</b> All frontend view files must be inside <code>resources/views/themes/{theme-name}/</code>. Views placed directly in the root <code>resources/views/</code> folder will return a 404 error for frontend requests.
                        </div>

                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="font-bold text-gray-800 mb-3">Automated Theme Refresh</h3>
                            <p class="text-sm text-gray-600 mb-4">When you update the package, use the sync command to ensure your local themes are updated with any core improvements.</p>
                            <div class="bg-gray-900 rounded-lg p-4 text-gray-300 font-mono text-xs">
                                <code class="text-green-400">php artisan lazy:update</code>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link');
            const sections = document.querySelectorAll('section[id]');

            // 1. Smooth Scroll on Click
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('href');
                    document.querySelector(id).scrollIntoView({
                        behavior: 'smooth'
                    });
                    
                    // Update URL hash without jumping
                    history.pushState(null, null, id);
                });
            });

            // 2. Intersection Observer for Scrollspy
            const options = {
                rootMargin: '-20% 0px -70% 0px',
                threshold: 0
            };

            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = '#' + entry.target.getAttribute('id');
                        
                        navLinks.forEach(link => {
                            link.classList.remove('text-blue-600', 'bg-blue-50');
                            link.classList.add('text-gray-600');

                            if (link.getAttribute('href') === id) {
                                link.classList.add('text-blue-600', 'bg-blue-50');
                                link.classList.remove('text-gray-600');
                            }
                        });
                    }
                });
            }, options);

            sections.forEach(section => observer.observe(section));
        });
    </script>
</x-cms-dashboard::layouts.admin>
