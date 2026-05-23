@php
    $s = $el['settings'] ?? [];

    $v = $s['visibility'] ?? ['mobile' => true, 'tablet' => true, 'desktop' => true];
    $visibilityClasses = '';
    if (!($v['mobile']  ?? true)) $visibilityClasses .= ' lazy-hide-mobile';
    if (!($v['tablet']  ?? true)) $visibilityClasses .= ' lazy-hide-tablet';
    if (!($v['desktop'] ?? true)) $visibilityClasses .= ' lazy-hide-desktop';

    $htmlContent  = $s['htmlContent']  ?? '';
    $cssClass     = $s['cssClass']     ?? '';
    $marginTop    = ($s['marginTop']    ?? 0) . ($s['marginTopUnit']    ?? 'px');
    $marginBottom = ($s['marginBottom'] ?? 0) . ($s['marginBottomUnit'] ?? 'px');

    $elemId = !empty($s['cssId']) ? $s['cssId'] : ('html-' . ($el['id'] ?? str_replace('.', '', uniqid('', true))));
    $outerStyle = "width:100%;margin-top:{$marginTop};margin-bottom:{$marginBottom};";
@endphp

@if($htmlContent)
<div id="{{ $elemId }}"
     class="lazy-html-block{{ $cssClass ? ' ' . $cssClass : '' }}{{ $visibilityClasses }}"
     style="{{ $outerStyle }}">
    {!! $htmlContent !!}
</div>
@endif
