@php
    $s = $el['settings'] ?? [];

    // Device Visibility
    $v = $s['visibility'] ?? ['mobile' => true, 'tablet' => true, 'desktop' => true];
    $visibilityClasses = '';
    if (!($v['mobile']  ?? true)) $visibilityClasses .= ' lazy-hide-mobile';
    if (!($v['tablet']  ?? true)) $visibilityClasses .= ' lazy-hide-tablet';
    if (!($v['desktop'] ?? true)) $visibilityClasses .= ' lazy-hide-desktop';
    if (!($v['mobile'] ?? true) && !($v['tablet'] ?? true) && !($v['desktop'] ?? true)) {
        $visibilityClasses = ' lazy-hide-all';
    }
@endphp
<div class="element-text mb-4 {{ $visibilityClasses }}">
    <div class="prose prose-slate max-w-none" style="
        text-align: {{ $s['textAlign'] ?? 'left' }};
        @if(!empty($s['fontSize'])) font-size: {{ $s['fontSize'] }}{{ $s['fontSizeUnit'] ?? 'px' }}; @endif
    ">
        {!! $s['content'] ?? 'Start typing your content here...' !!}
    </div>
</div>
