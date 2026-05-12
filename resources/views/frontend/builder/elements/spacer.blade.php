@php
    $s = $el['settings'] ?? [];

    $v = $s['visibility'] ?? ['mobile' => true, 'tablet' => true, 'desktop' => true];
    $visibilityClasses = '';
    if (!($v['mobile']  ?? true)) $visibilityClasses .= ' lazy-hide-mobile';
    if (!($v['tablet']  ?? true)) $visibilityClasses .= ' lazy-hide-tablet';
    if (!($v['desktop'] ?? true)) $visibilityClasses .= ' lazy-hide-desktop';

    $height = $s['height'] ?? $s['spacerHeight'] ?? 40;
    $unit   = $s['heightUnit'] ?? 'px';
@endphp

<div class="element-spacer {{ $visibilityClasses }}"
     style="height: {{ $height }}{{ $unit }}; display: block; width: 100%;">
</div>
