<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} | {{ get_cms_option('site_title', 'Lazy Builder') }}</title>
    
    <!-- Meta -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/cms-dashboard/css/material-symbols.css') }}" />

    <!-- Tailwind (Corrected Paths) -->
    <script src="{{ asset('vendor/cms-dashboard/js/tailwind.min.js') }}"></script>
    <script>
        // Fallback to CDN if local fails
        if (typeof tailwind === 'undefined') {
            document.write('<script src="https://cdn.tailwindcss.com"><\/script>');
        }
    </script>

    <!-- Pickr Color Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>

    <script>
        window.builderBreakpoints = {
            small: {{ get_cms_option('theme_small_screen_breakpoint', '800') }},
            medium: {{ get_cms_option('theme_medium_screen_breakpoint', '1100') }}
        };

        window.builderPagePadding = {
            top: '{{ get_cms_option('theme_page_padding_top', '60px') }}',
            bottom: '{{ get_cms_option('theme_page_padding_bottom', '60px') }}'
        };

        @php
            try {
                $allMenus = \Illuminate\Support\Facades\DB::table('navigation_menus')->get();
                $allItems = \Illuminate\Support\Facades\DB::table('navigation_menu_items')
                    ->orderBy('order')
                    ->get();
                
                $menuData = [];
                $menuNames = [];
                foreach($allMenus as $m) {
                    $menuNames[$m->id] = $m->name;
                    $menuItems = $allItems->where('navigation_menu_id', $m->id);
                    $grouped = $menuItems->groupBy('parent_id');
                    
                    $topLevel = $grouped->get(null, collect([]))->map(function($item) use ($grouped) {
                        return [
                            'id' => $item->id,
                            'title' => $item->title,
                            'url' => $item->url,
                            'children' => $grouped->get($item->id, collect([]))->map(function($child) use ($grouped) {
                                return [
                                    'id' => $child->id,
                                    'title' => $child->title,
                                    'url' => $child->url,
                                    'children' => $grouped->get($child->id, collect([]))->map(function($gchild) {
                                        return [
                                            'id' => $gchild->id,
                                            'title' => $gchild->title,
                                            'url' => $gchild->url
                                        ];
                                    })->values()->toArray()
                                ];
                            })->values()->toArray()
                        ];
                    })->values()->toArray();
                    
                    $menuData[$m->id] = $topLevel;
                }
                $menuDataJson = json_encode($menuData);
                $menuNamesJson = json_encode($menuNames);
            } catch(\Exception $e) {
                $menuDataJson = '{}';
                $menuNamesJson = '{}';
            }
        @endphp
        window.lazyMenuData = {!! $menuDataJson !!};
        window.lazyMenusList = {!! $menuNamesJson !!};
        @php
            $builderPostCards = json_decode(get_cms_option('lazy_post_cards', '[]'), true) ?: [];
        @endphp
        window.lazyPostCards = {!! json_encode($builderPostCards) !!};
        @php
            try {
                $__previewPosts = get_lazy_posts(['limit' => 6, 'order' => 'desc', 'orderby' => 'created_at']);
                $__previewPostsData = $__previewPosts->map(function($p) {
                    $img = $p->featured_image ?? null;
                    if ($img && !str_starts_with($img, 'http')) $img = asset('storage/' . $img);
                    $raw = $p->content ?? '';
                    $excerpt = $p->excerpt ?? (is_array(json_decode($raw, true)) ? '' : mb_substr(strip_tags($raw), 0, 80));
                    return ['title' => $p->title ?? 'Post', 'image' => $img, 'excerpt' => $excerpt];
                })->values()->toArray();
            } catch(\Exception $e) { $__previewPostsData = []; }
        @endphp
        window.lazyRecentPosts = {!! json_encode($__previewPostsData) !!};
    </script>

    @include('cms-dashboard::admin.lazy-builder.partials.styles')
</head>
<body class="bg-[#f1f1f1]">

    <div id="lazy-builder-app" class="builder-wrapper" :class="{ 'is-preview': isPreview, 'dragging-no-transition': isDragging }" v-cloak>
        
        <!-- Toast Container -->
        <div class="fixed top-14 right-5 z-[9999] flex flex-col gap-2 pointer-events-none">
            <transition-group name="toast">
                <div v-for="toast in toasts" :key="toast.id" 
                     class="px-5 py-3 rounded shadow-2xl text-white font-bold text-sm pointer-events-auto flex items-center gap-3 min-w-[200px]"
                     :class="toast.type === 'success' ? 'bg-[#00a32a]' : 'bg-[#d63638]'">
                    <i class="fa" :class="toast.type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'"></i>
                    @{{ toast.message }}
                </div>
            </transition-group>
        </div>
        
        <!-- Topbar -->
        <header class="builder-topbar">
            @include('cms-dashboard::admin.lazy-builder.partials.topbar_content')
        </header>

        <!-- Sidebar -->
        <template v-if="!isPreview">
            @include('cms-dashboard::admin.lazy-builder.partials.sidebar')
        </template>

        <!-- Canvas -->
        @include('cms-dashboard::admin.lazy-builder.partials.canvas')

        <!-- Modals -->
        @include('cms-dashboard::admin.lazy-builder.partials.modals.column-select')
        @include('cms-dashboard::admin.lazy-builder.partials.modals.element-select')
        @include('cms-dashboard::admin.lazy-builder.partials.modals.library')
        @include('cms-dashboard::admin.lazy-builder.partials.modals.context-menu')
    </div>

    @include('cms-dashboard::components.admin.media-modal')

    <!-- Scripts (Corrected Paths) -->
    <script src="{{ asset('vendor/cms-dashboard/js/vue.global.js') }}"></script>
    <script>
        // Fallback to CDN if local fails
        if (typeof Vue === 'undefined') {
            document.write('<script src="https://unpkg.com/vue@3/dist/vue.global.js"><\/script>');
        }
    </script>
    
    @include('cms-dashboard::admin.lazy-builder.partials.scripts')
</body>
</html>
