@php
    $s = $el['settings'] ?? [];

    $v = $s['visibility'] ?? ['mobile' => true, 'tablet' => true, 'desktop' => true];
    $visibilityClasses = '';
    if (!($v['mobile']  ?? true)) $visibilityClasses .= ' lazy-hide-mobile';
    if (!($v['tablet']  ?? true)) $visibilityClasses .= ' lazy-hide-tablet';
    if (!($v['desktop'] ?? true)) $visibilityClasses .= ' lazy-hide-desktop';

    $tickerId  = !empty($s['cssId'])   ? $s['cssId']   : ('lz-ticker-' . ($el['id'] ?? str_replace('.', '', uniqid('', true))));
    $cssClass  = $s['cssClass'] ?? '';

    $items        = array_filter($s['items'] ?? [], fn($i) => !empty($i['text']));
    $label        = $s['label']      ?? '';
    $speed        = max(1, min(100, (int)($s['speed'] ?? 50)));
    $direction    = $s['direction']  ?? 'left';
    $pauseOnHover = ($s['pauseOnHover'] ?? true) !== false;
    $separator    = $s['separator']  ?? '•';

    $bgColor        = $s['bgColor']        ?? '#1e3a8a';
    $textColor      = $s['textColor']      ?? '#ffffff';
    $labelBgColor   = $s['labelBgColor']   ?? '#ef4444';
    $labelTextColor = $s['labelTextColor'] ?? '#ffffff';
    $fontSize       = $s['fontSize']       ?? '14px';
    $fontWeight     = $s['fontWeight']     ?? '500';
    $height         = max(28, (int)($s['height'] ?? 44));
    $borderRadius   = (int)($s['borderRadius'] ?? 0);
    $marginTop      = $s['marginTop']    ?? 0;
    $marginBottom   = $s['marginBottom'] ?? 0;
    $marginTopUnit  = $s['marginTopUnit']    ?? 'px';
    $marginBottomUnit = $s['marginBottomUnit'] ?? 'px';

    // Speed 1–100 → duration 100s–5s (inverse)
    $duration   = max(5, 105 - $speed);
    $animName   = 'lztick-' . ($el['id'] ?? uniqid());
    // Unique CSS class for targeting per-instance animation & pause rule
    $runClass   = 'lztr-' . preg_replace('/[^a-z0-9]/i', '', $animName);
    $isVertical = in_array($direction, ['up', 'down']);

    // Build per-item HTML
    $parts = [];
    foreach ($items as $item) {
        $txt = e($item['text'] ?? '');
        $parts[] = !empty($item['url'])
            ? '<a href="' . e($item['url']) . '" class="lztick-link">' . $txt . '</a>'
            : '<span>' . $txt . '</span>';
    }
    if (empty($parts)) {
        $parts = ['<span style="opacity:.5">Add items in the builder.</span>'];
    }

    if ($isVertical) {
        // Each item occupies the full bar height, stacked vertically
        $itemStyle = "height:{$height}px;display:flex;align-items:center;padding:0 20px;box-sizing:border-box;white-space:nowrap;";
        $content   = implode('', array_map(fn($p) => '<div class="lztick-vi" style="' . $itemStyle . '">' . $p . '</div>', $parts));
    } else {
        $sep     = $separator !== '' ? ' <span class="lztick-sep" aria-hidden="true">' . e($separator) . '</span> ' : '&nbsp;&nbsp;';
        $content = implode($sep, $parts);
    }
@endphp

<div id="{{ $tickerId }}" class="lztick-wrap {{ $visibilityClasses }} {{ $cssClass }}{{ $pauseOnHover ? ' lztick-pause-hover' : '' }}"
     style="width:100%;max-width:100%;background:{{ $bgColor }};color:{{ $textColor }};font-size:{{ $fontSize }};font-weight:{{ $fontWeight }};height:{{ $height }}px;border-radius:{{ $borderRadius }}px;margin-top:{{ $marginTop }}{{ $marginTopUnit }};margin-bottom:{{ $marginBottom }}{{ $marginBottomUnit }};overflow:hidden;display:flex;align-items:center;position:relative;box-sizing:border-box;">

    @if($label)
    <div class="lztick-label" style="background:{{ $labelBgColor }};color:{{ $labelTextColor }};padding:0 14px;height:100%;display:flex;align-items:center;font-weight:700;white-space:nowrap;flex-shrink:0;font-size:{{ $fontSize }};letter-spacing:.03em;text-transform:uppercase;">
        {{ $label }}
    </div>
    @endif

    <div style="flex:1;overflow:hidden;height:100%;">
        @if($isVertical)
        {{-- Vertical: two copies stacked, each animates independently --}}
        <div class="{{ $runClass }}" style="display:flex;flex-direction:column;">{!! $content !!}</div>
        <div class="{{ $runClass }}" aria-hidden="true" style="display:flex;flex-direction:column;">{!! $content !!}</div>
        @else
        {{-- Horizontal: two copies side-by-side, each animates independently --}}
        {{-- Per-copy animation with translateX(-100%) guarantees seamless loop --}}
        <div style="display:flex;align-items:center;height:100%;">
            <span class="{{ $runClass }}" style="flex-shrink:0;display:inline-flex;align-items:center;white-space:nowrap;padding-right:40px;height:100%;">{!! $content !!}</span>
            <span class="{{ $runClass }}" aria-hidden="true" style="flex-shrink:0;display:inline-flex;align-items:center;white-space:nowrap;padding-right:40px;height:100%;">{!! $content !!}</span>
        </div>
        @endif
    </div>
</div>

{{-- Per-instance styles: animation defined in stylesheet (not inline) so hover rule can override animation-play-state --}}
<style>
.{{ $runClass }} {
    animation: {{ $animName }} {{ $duration }}s linear infinite;
    will-change: transform;
}
#{{ $tickerId }}.lztick-pause-hover:hover .{{ $runClass }} {
    animation-play-state: paused;
}
@if($isVertical)
@keyframes {{ $animName }} {
    from { transform: translateY({{ $direction === 'down' ? '-100%' : '0%' }}); }
    to   { transform: translateY({{ $direction === 'down' ? '0%' : '-100%' }}); }
}
@else
@keyframes {{ $animName }} {
    from { transform: translateX({{ $direction === 'right' ? '-100%' : '0%' }}); }
    to   { transform: translateX({{ $direction === 'right' ? '0%' : '-100%' }}); }
}
@endif
</style>

@once('lztick-global-css')
<style>
.lztick-wrap .lztick-link { color: inherit; text-decoration: none; }
.lztick-wrap .lztick-link:hover { opacity: .75; text-decoration: underline; }
.lztick-wrap .lztick-sep { opacity: .5; margin: 0 4px; }
</style>
@endonce
