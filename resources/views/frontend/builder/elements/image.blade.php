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
    $height    = $s['height']    ?? '';
    $maxWidth  = $s['maxWidth']  ?? '';

    $imgStyle  = 'display: block;';
    if ($width  !== '') $imgStyle .= ' width: '     . $width  . ($s['widthUnit']  ?? 'px') . ';';
    if ($height !== '') $imgStyle .= ' height: '    . $height . ($s['heightUnit'] ?? 'px') . ';';
    if ($maxWidth !== '') $imgStyle .= ' max-width: ' . $maxWidth . ($s['maxWidthUnit'] ?? 'px') . ';';

    $borderRadius = $s['borderRadius'] ?? '0';
    if ($borderRadius !== '' && $borderRadius !== '0') {
        $imgStyle .= ' border-radius: ' . $borderRadius . ($s['borderRadiusUnit'] ?? 'px') . ';';
    }

    $wrapperStyle = 'text-align: ' . $align . ';';
    if (isset($s['marginTop'])    && $s['marginTop']    !== '') $wrapperStyle .= ' margin-top: '    . $s['marginTop']    . ($s['marginTopUnit']    ?? 'px') . ';';
    if (isset($s['marginBottom']) && $s['marginBottom'] !== '') $wrapperStyle .= ' margin-bottom: ' . $s['marginBottom'] . ($s['marginBottomUnit'] ?? 'px') . ';';
@endphp

<div class="element-image {{ $visibilityClasses }}" style="{{ $wrapperStyle }}">
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
