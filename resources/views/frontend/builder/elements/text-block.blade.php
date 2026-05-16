@php
    $s = $el['settings'] ?? [];
    $v = $s['visibility'] ?? ['mobile' => true, 'tablet' => true, 'desktop' => true];
    $visibilityClasses = '';
    if (!($v['mobile']  ?? true)) $visibilityClasses .= ' lazy-hide-mobile';
    if (!($v['tablet']  ?? true)) $visibilityClasses .= ' lazy-hide-tablet';
    if (!($v['desktop'] ?? true)) $visibilityClasses .= ' lazy-hide-desktop';

    $elemId = 'text-block-' . uniqid();
    $appliedId = !empty($s['cssId']) ? $s['cssId'] : $elemId;
    $hoverColor = $s['hoverColor'] ?? null;

    $wrapperStyles = [
        'max-width: 100%',
        'text-align: ' . ($s['textAlign'] ?? 'center'),
        'padding-top: ' . ($s['paddingTop'] ?? 10) . 'px',
        'padding-right: ' . ($s['paddingRight'] ?? 0) . 'px',
        'padding-bottom: ' . ($s['paddingBottom'] ?? 10) . 'px',
        'padding-left: ' . ($s['paddingLeft'] ?? 0) . 'px',
        'margin-top: ' . ($s['marginTop'] ?? 0) . 'px',
        'margin-right: ' . ($s['marginRight'] ?? 0) . 'px',
        'margin-bottom: ' . ($s['marginBottom'] ?? 0) . 'px',
        'margin-left: ' . ($s['marginLeft'] ?? 0) . 'px',
        'color: ' . ($s['color'] ?? '#333333'),
        'font-family: ' . ($s['fontFamily'] ?? 'inherit'),
        'font-size: ' . ($s['fontSize'] ?? 16) . ($s['fontSizeUnit'] ?? 'px'),
        'font-weight: ' . ($s['fontWeight'] ?? '400'),
        'line-height: ' . ($s['lineHeight'] ?? '1.5'),
        'letter-spacing: ' . ($s['letterSpacing'] ?? 0) . 'px',
        'text-transform: ' . ($s['textTransform'] ?? 'none'),
    ];

    $contentStyles = [
        'text-align: inherit',
        'margin: 0',
        'width: 100%',
        'transition: color 0.3s ease',
        'color: inherit',
    ];
@endphp

@if($hoverColor || true)
<style>
    #{{ $appliedId }}:hover { color: {{ $hoverColor ?? ($s['color'] ?? '#333333') }} !important; }
    #{{ $appliedId }}:hover p, #{{ $appliedId }}:hover * { color: inherit !important; }
    .text-block-container-{{ $elemId }} .text-block-content, .text-block-container-{{ $appliedId }} .text-block-content { 
        text-align: inherit !important; 
        color: inherit !important; 
        font-size: inherit !important; 
        font-family: inherit !important; 
        font-weight: inherit !important; 
        line-height: inherit !important; 
        letter-spacing: inherit !important; 
        text-transform: inherit !important; 
        margin: 0 !important; 
        display: block;
        width: 100%;
    }
    /* List Styles */
    .text-block-container-{{ $appliedId }} ul { list-style-type: disc !important; margin-left: 20px !important; margin-bottom: 15px !important; }
    .text-block-container-{{ $appliedId }} ol { list-style-type: decimal !important; margin-left: 20px !important; margin-bottom: 15px !important; }
    .text-block-container-{{ $appliedId }} li { margin-bottom: 5px !important; }
</style>
@endif

<div class="element-text-block-wrapper text-block-container-{{ $appliedId }} {{ $s['cssClass'] ?? '' }} {{ $visibilityClasses }}"
     id="{{ $appliedId }}"
     style="{{ implode('; ', $wrapperStyles) }}">
    <div class="text-block-content" style="{{ implode('; ', $contentStyles) }}">
        {!! $s['content'] ?? '' !!}
    </div>
</div>
