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
<div class="element-heading mb-4 {{ $visibilityClasses }}">
    <h2 style="
        text-align: {{ $s['textAlign'] ?? 'left' }}; 
        margin: 0; 
        padding: 0;
        @if(!empty($s['fontSize'])) font-size: {{ $s['fontSize'] }}{{ $s['fontSizeUnit'] ?? 'px' }}; @endif
        @if(!empty($s['letterSpacing'])) letter-spacing: {{ $s['letterSpacing'] }}{{ $s['letterSpacingUnit'] ?? 'px' }}; @endif
    " class="text-slate-800 font-bold leading-tight">
        {{ $s['title'] ?? 'New Heading' }}
    </h2>
</div>
