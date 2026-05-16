@php
    $s = $el['settings'] ?? [];

    $v = $s['visibility'] ?? ['mobile' => true, 'tablet' => true, 'desktop' => true];
    $visibilityClasses = '';
    if (!($v['mobile']  ?? true)) $visibilityClasses .= ' lazy-hide-mobile';
    if (!($v['tablet']  ?? true)) $visibilityClasses .= ' lazy-hide-tablet';
    if (!($v['desktop'] ?? true)) $visibilityClasses .= ' lazy-hide-desktop';

    $url       = $s['url']       ?? $s['src'] ?? '';
    $alt       = $s['alt']       ?? '';
    $align     = $s['textAlign'] ?? $s['align'] ?? 'center';
    $linkUrl   = $s['linkUrl']   ?? '';
    $target    = $s['linkTarget'] ?? '_self';
    $width     = $s['width']     ?? '';
    $maxWidth  = $s['maxWidth']  ?? '';
    $imgStyle  = 'display: inline-block; max-width: 100%; vertical-align: middle;';
    if ($width  !== '') $imgStyle .= ' width: '     . $width  . ($s['widthUnit']  ?? 'px') . ';';
    $imgStyle .= ' height: auto;';
    if ($maxWidth !== '') $imgStyle .= ' max-width: ' . $maxWidth . ($s['maxWidthUnit'] ?? 'px') . ';';

    $borderRadius = $s['borderRadius'] ?? '0';
    if ($borderRadius !== '' && $borderRadius !== '0') {
        $imgStyle .= ' border-radius: ' . $borderRadius . ($s['borderRadiusUnit'] ?? 'px') . ';';
    }

    // Borders
    $borderTop    = $s['borderSizeTop']    ?? 0;
    $borderRight  = $s['borderSizeRight']  ?? 0;
    $borderBottom = $s['borderSizeBottom'] ?? 0;
    $borderLeft   = $s['borderSizeLeft']   ?? 0;
    $borderColor  = $s['borderColor']      ?? 'transparent';

    if ($borderTop || $borderRight || $borderBottom || $borderLeft) {
        $imgStyle .= " border-style: solid; border-color: {$borderColor};";
        if ($borderTop)    $imgStyle .= " border-top-width: {$borderTop}px;";
        if ($borderRight)  $imgStyle .= " border-right-width: {$borderRight}px;";
        if ($borderBottom) $imgStyle .= " border-bottom-width: {$borderBottom}px;";
        if ($borderLeft)   $imgStyle .= " border-left-width: {$borderLeft}px;";
    }

    $wrapperStyle = 'text-align: ' . $align . '; width: 100%;';
    if (isset($s['marginTop'])    && $s['marginTop']    !== '') $wrapperStyle .= ' margin-top: '    . $s['marginTop']    . 'px;';
    if (isset($s['marginRight'])   && $s['marginRight']   !== '') $wrapperStyle .= ' margin-right: '   . $s['marginRight']   . 'px;';
    if (isset($s['marginBottom']) && $s['marginBottom'] !== '') $wrapperStyle .= ' margin-bottom: ' . $s['marginBottom'] . 'px;';
    if (isset($s['marginLeft'])    && $s['marginLeft']    !== '') $wrapperStyle .= ' margin-left: '    . $s['marginLeft']    . 'px;';
    $hoverType = $s['hoverType'] ?? 'none';
    $hoverClass = ($hoverType !== 'none') ? 'hover-' . $hoverType : '';
@endphp

<div class="element-image {{ $visibilityClasses }} {{ $hoverClass }}" style="{{ $wrapperStyle }}">
    @if($url)
        @if($linkUrl)
            <a href="{{ $linkUrl }}" target="{{ $target }}" style="display: inline-block;">
        @endif

        <img src="{{ $url }}" alt="{{ $alt }}" style="{{ $imgStyle }} max-width: 100%;">

        @if($linkUrl)
            </a>
        @endif
    @else
        <div style="background: #f0f0f1; border: 2px dashed #c3c4c7; padding: 40px 20px; text-align: center; color: #8c8f94; font-size: 13px; border-radius: 4px;">
            No image selected
        </div>
    @endif
</div>
