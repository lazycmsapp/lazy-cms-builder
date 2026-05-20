@php
    $s = $el['settings'] ?? [];

    // Visibility
    $v = $s['visibility'] ?? ['mobile' => true, 'tablet' => true, 'desktop' => true];
    $visCls = '';
    if (!($v['mobile']  ?? true)) $visCls .= ' lazy-hide-mobile';
    if (!($v['tablet']  ?? true)) $visCls .= ' lazy-hide-tablet';
    if (!($v['desktop'] ?? true)) $visCls .= ' lazy-hide-desktop';

    // Load post card config
    $postCardId = $s['post_card_id'] ?? '';
    $cardCfg    = [];
    if ($postCardId) {
        $rawCards = get_cms_option('lazy_post_cards', '[]');
        $allCards = json_decode($rawCards, true) ?: [];
        foreach ($allCards as $c) {
            if (($c['id'] ?? '') === $postCardId) { $cardCfg = $c['config'] ?? []; break; }
        }
    }

    // Resolve post type
    $postType = $s['post_type'] === 'custom'
        ? ($s['post_type_custom'] ?? 'post')
        : ($s['post_type'] ?? 'post');

    // Resolve category/tag/author filter
    $queryArgs = [
        'post_type' => $postType,
        'limit'     => max(1, (int)($s['posts_count'] ?? 6)),
        'offset'    => max(0, (int)($s['posts_offset'] ?? 0)),
        'order'     => $s['order']    ?? 'desc',
        'orderby'   => $s['order_by'] ?? 'created_at',
    ];
    $postsBy = $s['posts_by'] ?? 'all';
    if ($postsBy !== 'all' && !empty($s['posts_by_value'])) {
        if ($postsBy === 'category') $queryArgs['category'] = $s['posts_by_value'];
        if ($postsBy === 'tag')      $queryArgs['tag']      = $s['posts_by_value'];
        if ($postsBy === 'author')   $queryArgs['author']   = $s['posts_by_value'];
    }
    $posts = get_lazy_posts($queryArgs);

    // Grid / spacing settings
    $cols         = max(1, (int)($s['columns']        ?? 3));
    $colsTablet   = max(1, (int)($s['columns_tablet'] ?? 2));
    $colsMobile   = max(1, (int)($s['columns_mobile'] ?? 1));
    $colSpacing   = max(0, (int)($s['column_spacing'] ?? 24));
    $rowSpacing   = max(0, (int)($s['row_spacing']    ?? 24));
    $cardAlign    = $s['card_alignment'] ?? 'left';
    $bpSm         = (int) get_cms_option('theme_small_screen_breakpoint',  '800');
    $bpMed        = (int) get_cms_option('theme_medium_screen_breakpoint', '1100');
    $bpSm1        = $bpSm + 1;

    // Margin
    $marginStyle  = "margin-top:"    . ($s['marginTop']    ?? 0) . ($s['marginTopUnit']    ?? 'px') . ";";
    $marginStyle .= "margin-right:"  . ($s['marginRight']  ?? 0) . ($s['marginRightUnit']  ?? 'px') . ";";
    $marginStyle .= "margin-bottom:" . ($s['marginBottom'] ?? 0) . ($s['marginBottomUnit'] ?? 'px') . ";";
    $marginStyle .= "margin-left:"   . ($s['marginLeft']   ?? 0) . ($s['marginLeftUnit']   ?? 'px') . ";";

    // Post card builder layout
    $cardLayout = is_array($cardCfg['layout'] ?? null) ? $cardCfg['layout'] : [];

    // Legacy card config fields
    $cardStyle    = $cardCfg['card_style']      ?? 'shadow';
    $hoverEffect  = $cardCfg['hover_effect']    ?? 'lift';
    $imageRatio   = $cardCfg['image_ratio']     ?? '16/9';
    $showImage    = $cardCfg['show_image']      ?? true;
    $showTitle    = $cardCfg['show_title']      ?? true;
    $showExcerpt  = $cardCfg['show_excerpt']    ?? true;
    $showDate     = $cardCfg['show_date']       ?? true;
    $showAuthor   = $cardCfg['show_author']     ?? false;
    $showCats     = $cardCfg['show_categories'] ?? false;
    $showReadMore = $cardCfg['show_read_more']  ?? true;
    $readMoreText = $cardCfg['read_more_text']  ?? 'Read More';

    $ratioParts   = explode('/', $imageRatio);
    $ratioPadding = (count($ratioParts) === 2 && (int)$ratioParts[0] > 0)
        ? round(((int)$ratioParts[1] / (int)$ratioParts[0]) * 100, 2) . '%'
        : '56.25%';

    $cardStyleCss = match($cardStyle) {
        'flat'  => 'border border-[#e5e7eb]',
        'boxed' => 'border border-[#e5e7eb] p-4',
        default => 'shadow-md',
    };

    $hoverCss = match($hoverEffect) {
        'lift'    => 'lazy-card-lift',
        'border'  => 'lazy-card-border',
        'scale'   => 'lazy-card-scale',
        'fade'    => 'lazy-card-fade',
        default   => '',
    };

    $nothingMsg = $s['nothing_found_message'] ?? 'No posts found.';
    $gridId     = 'card-' . str_replace('.', '', uniqid('', true));
    $cssId      = $s['cssId']    ?? '';
    $cssCls     = $s['cssClass'] ?? '';

    $textAlignCss = match($cardAlign) {
        'center' => 'text-align:center;',
        'right'  => 'text-align:right;',
        default  => 'text-align:left;',
    };
@endphp

<style>
#{{ $gridId }} {
    display: grid;
    grid-template-columns: repeat({{ $cols }}, 1fr);
    column-gap: {{ $colSpacing }}px;
    row-gap: {{ $rowSpacing }}px;
}
@media(min-width:{{ $bpSm1 }}px) and (max-width:{{ $bpMed }}px){
    #{{ $gridId }}{ grid-template-columns: repeat({{ $colsTablet }}, 1fr); }
}
@media(max-width:{{ $bpSm }}px){
    #{{ $gridId }}{ grid-template-columns: repeat({{ $colsMobile }}, 1fr); }
}
.lazy-card-lift { transition: transform .2s, box-shadow .2s; }
.lazy-card-lift:hover { transform: translateY(-6px); box-shadow: 0 12px 28px rgba(0,0,0,.12); }
.lazy-card-border { transition: border-color .2s; }
.lazy-card-border:hover { border-color: #2271b1 !important; }
.lazy-card-scale { transition: transform .2s; }
.lazy-card-scale:hover { transform: scale(1.03); }
.lazy-card-fade img { transition: opacity .3s; }
.lazy-card-fade:hover img { opacity: .75; }
</style>

<div class="lazy-card-grid {{ $visCls }} {{ $cssCls }}"
     @if($cssId) id="{{ $cssId }}" @endif
     style="{{ $marginStyle }}{{ $textAlignCss }}">
    <div id="{{ $gridId }}">
        @forelse($posts as $post)
        @php
            $permalink  = get_lazy_permalink($post);
            $imgSrc     = $post->featured_image ?? null;
            if ($imgSrc && !str_starts_with($imgSrc, 'http')) $imgSrc = asset('storage/' . $imgSrc);
            $__rawContent    = $post->content ?? '';
            $__decoded       = json_decode($__rawContent, true);
            $__isBuilderJson = is_array($__decoded);
            if ($__isBuilderJson) {
                $__extractText = function(array $items) use (&$__extractText): string {
                    $out = '';
                    foreach ($items as $item) {
                        $t = $item['type'] ?? '';
                        if (in_array($t, ['text_block', 'special_text'])) {
                            $out .= ' ' . strip_tags($item['settings']['content'] ?? '');
                        } elseif ($t === 'title') {
                            $out .= ' ' . ($item['settings']['title'] ?? '');
                        }
                        foreach ($item['columns'] ?? [] as $col) {
                            $out .= ' ' . $__extractText($col['elements'] ?? []);
                        }
                    }
                    return trim($out);
                };
                $__plainContent = $__extractText($__decoded);
            } else {
                $__plainContent = strip_tags($__rawContent);
            }
            $rawExcerpt = $__plainContent;
            $excerpt    = $post->excerpt ?? (mb_strlen($rawExcerpt) > 120 ? mb_substr($rawExcerpt, 0, 120) . '…' : $rawExcerpt);
            $dateStr    = $post->published_at ? $post->published_at->format('M j, Y') : $post->created_at->format('M j, Y');
            $authorName = optional($post->user)->name ?? '';
            $categories = $post->categories ?? collect();
            // Post card builder context variables
            $postFeaturedImage = $imgSrc;
            $postPermalink     = $permalink;
            $postTitle         = $post->title ?? '';
            $postContent       = $__plainContent;
            $postExcerpt       = $post->excerpt ?? null;
            $postPublishedAt   = $post->published_at ?? null;
            $postCreatedAt     = $post->created_at ?? null;
            $postAuthor        = optional($post->user)->name ?? '';
            $postCategories    = $post->categories ?? collect();
        @endphp
        @if(!empty($cardLayout))
        <div style="break-inside:avoid">
            @include('cms-dashboard::frontend.builder.render', ['layout' => $cardLayout])
        </div>
        @else
        <article class="lazy-post-card {{ $cardStyleCss }} {{ $hoverCss }} rounded-lg overflow-hidden bg-white" style="break-inside:avoid">

            @if($showImage && $imgSrc)
            <a href="{{ $permalink }}" class="block overflow-hidden">
                <div style="position:relative;padding-bottom:{{ $ratioPadding }};overflow:hidden;">
                    <img src="{{ $imgSrc }}" alt="{{ $post->title }}"
                         style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                </div>
            </a>
            @endif

            <div class="{{ $cardStyle === 'boxed' ? '' : 'p-5' }}">
                @if($showCats && $categories->count())
                <div class="flex flex-wrap gap-1 mb-2">
                    @foreach($categories->take(3) as $cat)
                    <a href="{{ url('/category/' . ($cat->slug ?? '')) }}"
                       style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#2271b1;background:#f0f6fb;padding:2px 8px;border-radius:99px;text-decoration:none;">
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </div>
                @endif

                @if($showTitle)
                <h3 style="font-size:16px;font-weight:700;line-height:1.35;margin:0 0 8px;">
                    <a href="{{ $permalink }}" style="color:inherit;text-decoration:none;">{{ $post->title }}</a>
                </h3>
                @endif

                @if($showExcerpt && $excerpt)
                <p style="font-size:13px;color:#6b7280;line-height:1.6;margin:0 0 10px;">{{ $excerpt }}</p>
                @endif

                @if($showDate || $showAuthor)
                <div style="font-size:11px;color:#9ca3af;font-weight:600;display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                    @if($showDate)
                    <span>{{ $dateStr }}</span>
                    @endif
                    @if($showAuthor && $authorName)
                    <span>· {{ $authorName }}</span>
                    @endif
                </div>
                @endif

                @if($showReadMore)
                <a href="{{ $permalink }}"
                   style="display:inline-block;font-size:12px;font-weight:700;color:#2271b1;text-decoration:none;border:1.5px solid #2271b1;padding:5px 14px;border-radius:5px;transition:background .2s,color .2s;"
                   onmouseover="this.style.background='#2271b1';this.style.color='#fff';"
                   onmouseout="this.style.background='';this.style.color='#2271b1';">
                    {{ $readMoreText }}
                </a>
                @endif
            </div>
        </article>
        @endif
        @empty
        <div style="grid-column:1/-1;padding:40px;text-align:center;color:#9ca3af;font-size:13px;border:2px dashed #e5e7eb;border-radius:8px;">
            {{ $nothingMsg }}
        </div>
        @endforelse
    </div>
</div>
