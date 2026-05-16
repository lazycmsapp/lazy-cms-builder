@php
    $s = $el['settings'] ?? [];
    $v = $s['visibility'] ?? ['mobile' => true, 'tablet' => true, 'desktop' => true];
    $visibilityClasses = '';
    if (!($v['mobile']  ?? true)) $visibilityClasses .= ' lazy-hide-mobile';
    if (!($v['tablet']  ?? true)) $visibilityClasses .= ' lazy-hide-tablet';
    if (!($v['desktop'] ?? true)) $visibilityClasses .= ' lazy-hide-desktop';

    $elemId = 'btn-' . uniqid();
    $appliedId = !empty($s['cssId']) ? $s['cssId'] : $elemId;
    
    $wrapperStyles = [
        'display' => 'flex',
        'width' => '100%',
        'justify-content' => $s['textAlign'] === 'left' ? 'flex-start' : ($s['textAlign'] === 'right' ? 'flex-end' : 'center'),
        'margin-top' => getUnitVal($s['marginTop'] ?? 10, 'px'),
        'margin-bottom' => getUnitVal($s['marginBottom'] ?? 10, 'px'),
    ];

    $btnStyles = [
        'display' => ($s['buttonSpan'] ?? false) ? 'block' : 'inline-block',
        'width' => ($s['buttonSpan'] ?? false) ? '100%' : 'auto',
        'padding-top' => getUnitVal($s['paddingTop'] ?? 12, 'px'),
        'padding-bottom' => getUnitVal($s['paddingBottom'] ?? 12, 'px'),
        'padding-left' => getUnitVal($s['paddingLeft'] ?? 30, 'px'),
        'padding-right' => getUnitVal($s['paddingRight'] ?? 30, 'px'),
        'margin-left' => getUnitVal($s['marginLeft'] ?? 0, 'px'),
        'margin-right' => getUnitVal($s['marginRight'] ?? 0, 'px'),
        'background-color' => (($s['buttonStyle'] ?? 'default') === 'custom' && !empty($s['bgGradientStartColor']) && !empty($s['bgGradientEndColor'])) ? 'transparent' : ($s['bgColor'] ?? '#0091ea'),
        'background-image' => (($s['buttonStyle'] ?? 'default') === 'custom' && !empty($s['bgGradientStartColor']) && !empty($s['bgGradientEndColor']))
            ? (($s['bgGradientType'] ?? 'linear') === 'radial'
                ? "radial-gradient(circle at center, {$s['bgGradientStartColor']} " . ($s['bgGradientStartPosition'] ?? 0) . "%, {$s['bgGradientEndColor']} " . ($s['bgGradientEndPosition'] ?? 100) . "%)"
                : "linear-gradient(" . ($s['bgGradientAngle'] ?? 180) . "deg, {$s['bgGradientStartColor']} " . ($s['bgGradientStartPosition'] ?? 0) . "%, {$s['bgGradientEndColor']} " . ($s['bgGradientEndPosition'] ?? 100) . "%)")
            : 'none',
        'color' => $s['color'] ?? '#ffffff',
        'border-radius' => getUnitVal($s['borderRadius'] ?? 5, 'px'),
        'border-top-width' => getUnitVal($s['borderSizeTop'] ?? 0, 'px'),
        'border-right-width' => getUnitVal($s['borderSizeRight'] ?? 0, 'px'),
        'border-bottom-width' => getUnitVal($s['borderSizeBottom'] ?? 0, 'px'),
        'border-left-width' => getUnitVal($s['borderSizeLeft'] ?? 0, 'px'),
        'border-style' => 'solid',
        'border-color' => $s['borderColor'] ?? '#000000',
        'font-family' => $s['fontFamily'] ?? 'inherit',
        'font-size' => getUnitVal($s['fontSize'] ?? 16, 'px'),
        'font-weight' => $s['fontWeight'] ?? '600',
        'line-height' => $s['lineHeight'] ?? 'normal',
        'letter-spacing' => getUnitVal($s['letterSpacing'] ?? 0, 'px'),
        'text-transform' => $s['textTransform'] ?? 'none',
        'text-decoration' => 'none',
        'transition' => 'all 0.3s ease',
        'cursor' => 'pointer',
        'text-align' => 'center',
    ];

    $isCustom = ($s['buttonStyle'] ?? 'default') === 'custom';
    $hoverColor = $s['hoverColor'] ?? '#ffffff';
    $hoverBgColor = $s['hoverBgColor'] ?? '#007cc0';
    $hoverStart = $s['bgGradientHoverStartColor'] ?? '#007cc0';
    $hoverEnd   = $s['bgGradientHoverEndColor']   ?? '#005fa3';
    $icon = $s['icon'] ?? '';
    $iconPos = $s['iconPosition'] ?? 'left';

    $hoverBgImage = 'none';
    if ($isCustom && !empty($s['bgGradientStartColor'])) {
         if (($s['bgGradientType'] ?? 'linear') === 'radial') {
             $hoverBgImage = "radial-gradient(circle at center, {$hoverStart} " . ($s['bgGradientStartPosition'] ?? 0) . "%, {$hoverEnd} " . ($s['bgGradientEndPosition'] ?? 100) . "%)";
         } else {
             $hoverBgImage = "linear-gradient(" . ($s['bgGradientAngle'] ?? 180) . "deg, {$hoverStart} " . ($s['bgGradientStartPosition'] ?? 0) . "%, {$hoverEnd} " . ($s['bgGradientEndPosition'] ?? 100) . "%)";
         }
    }
@endphp

<style>
    #{{ $appliedId }}:hover {
        @if($isCustom)
            background-image: {{ $hoverBgImage }} !important;
            background-color: transparent !important;
        @else
            background-color: {{ $hoverBgColor }} !important;
            background-image: none !important;
        @endif
        color: {{ $hoverColor }} !important;
    }
</style>

<div class="element-button-wrapper {{ $s['cssClass'] ?? '' }} {{ $visibilityClasses }}"
     style="{{ collect($wrapperStyles)->map(fn($v, $k) => "$k: $v")->implode('; ') }}">
    <a href="{{ $s['linkUrl'] ?? '#' }}" 
       id="{{ $appliedId }}"
       target="{{ $s['linkTarget'] ?? '_self' }}"
       style="{{ collect($btnStyles)->map(fn($v, $k) => "$k: $v")->implode('; ') }}">
        @if($icon && $iconPos !== 'right')
            <i class="{{ $icon }} mr-2"></i>
        @endif
        {{ $s['text'] ?? 'Click Here' }}
        @if($icon && $iconPos === 'right')
            <i class="{{ $icon }} ml-2"></i>
        @endif
    </a>
</div>
