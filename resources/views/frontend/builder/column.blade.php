@once
<style>
    @media (max-width: 640px) { .lazy-hide-mobile { display: none !important; } }
    @media (min-width: 641px) and (max-width: 1024px) { .lazy-hide-tablet { display: none !important; } }
    @media (min-width: 1025px) { .lazy-hide-desktop { display: none !important; } }
    .lazy-hide-all { display: none !important; }
</style>
@endonce
@php
    $s     = $column['settings'] ?? [];
    $basisRaw = $column['basis'] ?? null;
    if ($basisRaw === null) {
        $totalColumns = max(1, count($container['columns'] ?? [1]));
        $basisRaw = (100 / $totalColumns) . '%';
    }

    $flexBasis = $basisRaw;
    $totalCols = count($container['columns'] ?? [1]);
    $gapVal = intval($container['settings']['columnGap'] ?? 20);
    
    if ($basisRaw === 'auto') {
        $flexBasis = 'auto';
    } elseif (is_string($basisRaw) && strpos($basisRaw, '%') !== false) {
        if ($totalCols === 1) {
            $flexBasis = $basisRaw;
        } else {
            $subtract = ($gapVal * ($totalCols - 1)) / $totalCols;
            $flexBasis = "calc({$basisRaw} - {$subtract}px)";
        }
    } elseif (is_numeric($basisRaw)) {
        if ($totalCols === 1) {
            $flexBasis = "{$basisRaw}%";
        } else {
            $subtract = ($gapVal * ($totalCols - 1)) / $totalCols;
            $flexBasis = "calc({$basisRaw}% - {$subtract}px)";
        }
    }

    $flexGrow = (isset($s['flexGrow']) && $s['flexGrow'] !== '') ? $s['flexGrow'] : 0;
    $flexShrink = (isset($s['flexShrink']) && $s['flexShrink'] !== '') ? $s['flexShrink'] : 0;
    $maxWidth = $flexBasis === 'auto' ? 'none' : $flexBasis;

    // Device Visibility (Responsive)
    $v = $s['visibility'] ?? ['mobile' => true, 'tablet' => true, 'desktop' => true];
    $visibilityClasses = '';
    if (!($v['mobile']  ?? true)) $visibilityClasses .= ' lazy-hide-mobile';
    if (!($v['tablet']  ?? true)) $visibilityClasses .= ' lazy-hide-tablet';
    if (!($v['desktop'] ?? true)) $visibilityClasses .= ' lazy-hide-desktop';
    if (!($v['mobile'] ?? true) && !($v['tablet'] ?? true) && !($v['desktop'] ?? true)) {
        $visibilityClasses = ' lazy-hide-all';
    }

    $containerAlign = $container['settings']['alignItems'] ?? 'stretch';
    $colAlignment = (!empty($s['alignment']) && $s['alignment'] !== 'default') ? $s['alignment'] : 'default';
    $heightMode = $container['settings']['height'] ?? 'auto';
    $hasDefinedHeight = in_array($heightMode, ['full', 'custom'], true);
    $isEmpty = empty($column['elements']);
    
    // Comprehensive stretch detection logic
    $shouldStretch = ($colAlignment === 'stretch') 
                     || (in_array($colAlignment, ['', 'default', null], true) && $containerAlign === 'stretch');

    $globalGap = $container['settings']['columnGap'] ?? 3;
    $pLeft = (isset($s['columnSpacingLeft']) && $s['columnSpacingLeft'] !== '') ? $s['columnSpacingLeft'] . '%' : $globalGap . '%';
    $pRight = (isset($s['columnSpacingRight']) && $s['columnSpacingRight'] !== '') ? $s['columnSpacingRight'] . '%' : $globalGap . '%';

    $outerStyles = [
        "flex-basis: {$flexBasis}",
        "flex-grow: " . ($shouldStretch ? '1' : ($s['flexGrow'] ?? '0')),
        "flex-shrink: " . ($s['flexShrink'] ?? '0'),
        "max-width: {$maxWidth}",
        "min-height: " . ($shouldStretch ? ($isEmpty ? '100px' : '100% !important') : 'auto'),
        "padding-left: {$pLeft}",
        "padding-right: {$pRight}",
        'display: flex !important',
        'flex-direction: column !important',
    ];

    if ($shouldStretch) {
        $outerStyles[] = 'align-self: stretch !important';
        $outerStyles[] = 'flex-grow: 1 !important';
        // Use min-height: 100% for better browser support in auto-height contexts
        $outerStyles[] = 'min-height: ' . ($isEmpty ? '100px' : '100% !important');
    } else {
        $outerStyles[] = 'align-self: ' . ($colAlignment === 'default' ? $containerAlign : $colAlignment) . ' !important';
        $outerStyles[] = 'height: auto';
    }

    if (isset($s['marginTop']) && $s['marginTop'] !== '') $outerStyles[] = 'margin-top: ' . $s['marginTop'] . ($s['marginTopUnit'] ?? 'px');
    if (isset($s['marginBottom']) && $s['marginBottom'] !== '') $outerStyles[] = 'margin-bottom: ' . $s['marginBottom'] . ($s['marginBottomUnit'] ?? 'px');

    $innerStyles = [
        'min-height: ' . ($shouldStretch ? '100% !important' : '8px'),
        'flex: ' . ($shouldStretch ? '1 1 auto !important' : '0 1 auto'),
        'flex-grow: ' . ($shouldStretch ? '1 !important' : '0'),
        'padding-top: '    . ($s['paddingTop']    ?? 10) . ($s['paddingTopUnit'] ?? 'px'),
        'padding-bottom: ' . ($s['paddingBottom'] ?? 10) . ($s['paddingBottomUnit'] ?? 'px'),
        'padding-left: '   . ($s['paddingLeft']   ?? 10) . ($s['paddingLeftUnit'] ?? 'px'),
        'padding-right: '  . ($s['paddingRight']  ?? 10) . ($s['paddingRightUnit'] ?? 'px'),
        'box-sizing: border-box',
    ];
    if (isset($s['marginLeft']) && $s['marginLeft'] !== '') $innerStyles[] = 'margin-left: ' . $s['marginLeft'] . ($s['marginLeftUnit'] ?? 'px');
    if (isset($s['marginRight']) && $s['marginRight'] !== '') $innerStyles[] = 'margin-right: ' . $s['marginRight'] . ($s['marginRightUnit'] ?? 'px');

    $contentLayout = ($s['contentLayout'] ?? '') ?: 'column';
    if ($contentLayout && $contentLayout !== 'block') {
        $innerStyles[] = 'display: flex';
        $innerStyles[] = 'flex-wrap: wrap';
        $innerStyles[] = 'flex-direction: ' . ($contentLayout === 'row' ? 'row' : 'column');
        $gw = intval($s['gapWidth']  ?? 0);
        $gh = intval($s['gapHeight'] ?? 0);
        if ($gw > 0 || $gh > 0) $innerStyles[] = 'gap: ' . $gh . 'px ' . $gw . 'px';
        if ($contentLayout === 'row') {
            if (!empty($s['contentAlignH'])) $innerStyles[] = 'justify-content: ' . $s['contentAlignH'];
            if (!empty($s['contentAlignV'])) $innerStyles[] = 'align-items: '     . $s['contentAlignV'];
        } else {
            if (!empty($s['contentAlignV'])) $innerStyles[] = 'justify-content: ' . $s['contentAlignV'];
            if (!empty($s['contentAlignH'])) $innerStyles[] = 'align-items: '     . $s['contentAlignH'];
        }
    } elseif ($contentLayout === 'block') {
        $innerStyles[] = 'display: block';
    }

    if (!empty($s['textColor']))   $innerStyles[] = 'color: '            . $s['textColor'];

    $hexToRgba = function($hex, $opacity) {
        if (empty($hex) || $hex === 'transparent') return 'transparent';
        if (strpos($hex, 'rgba') !== false) return $hex;
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return "rgba($r, $g, $b, $opacity)";
    };

    // Background Logic
    if (!empty($s['bgColor'])) {
        $innerStyles[] = "background-color: " . $hexToRgba($s['bgColor'], $s['bgColorOpacity'] ?? 1);
    }

    $bgImages = [];
    if (!empty($s['bgGradientStartColor']) && !empty($s['bgGradientEndColor'])) {
        $gType = $s['bgGradientType'] ?? 'linear';
        $angle = $s['bgGradientAngle'] ?? 180;
        $start = $hexToRgba($s['bgGradientStartColor'], $s['bgGradientStartOpacity'] ?? 1);
        $end   = $hexToRgba($s['bgGradientEndColor'],   $s['bgGradientEndOpacity']   ?? 1);
        $startPos = $s['bgGradientStartPosition'] ?? 0;
        $endPos = $s['bgGradientEndPosition'] ?? 100;

        if ($gType === 'linear') {
            $bgImages[] = "linear-gradient({$angle}deg, {$start} {$startPos}%, {$end} {$endPos}%)";
        } else {
            $bgImages[] = "radial-gradient(circle at center, {$start} {$startPos}%, {$end} {$endPos}%)";
        }
    }

    if (!empty($s['bgImage'])) {
        $bgImages[] = "url('{$s['bgImage']}')";
        $innerStyles[] = "background-position: " . ($s['bgImagePosition'] ?? 'center center');
        $innerStyles[] = "background-repeat: " . ($s['bgImageRepeat'] ?? 'no-repeat');
        $innerStyles[] = "background-size: " . ($s['bgImageSize'] ?? 'cover');
        $innerStyles[] = "background-attachment: " . (($s['bgImageParallax'] ?? 'none') === 'fixed' ? 'fixed' : 'scroll');
        if (!empty($s['bgImageBlendMode']) && $s['bgImageBlendMode'] !== 'normal') {
            $innerStyles[] = "background-blend-mode: {$s['bgImageBlendMode']}";
        }
    }

    if (!empty($bgImages)) {
        $innerStyles[] = "background-image: " . implode(', ', $bgImages);
    }
    if (isset($s['fontSize']) && $s['fontSize'] !== '') $innerStyles[] = 'font-size: ' . $s['fontSize'] . ($s['fontSizeUnit'] ?? 'px');
    if (!empty($s['fontWeight'])) $innerStyles[] = 'font-weight: '           . $s['fontWeight'];
    if (!empty($s['lineHeight'])) $innerStyles[] = 'line-height: '           . $s['lineHeight'];
    if (isset($s['letterSpacing']) && $s['letterSpacing'] !== '') $innerStyles[] = 'letter-spacing: ' . $s['letterSpacing'] . ($s['letterSpacingUnit'] ?? 'px');
    if (!empty($s['textAlign']))  $innerStyles[] = 'text-align: '             . $s['textAlign'];

    foreach (['Top', 'Right', 'Bottom', 'Left'] as $side) {
        $val = intval($s['borderSize' . $side] ?? 0);
        if ($val > 0) {
            $innerStyles[] = 'border-' . strtolower($side) . ': ' . $val . 'px solid ' . ($s['borderColor'] ?? '#000000');
        }
    }
    foreach (['TopLeft' => 'top-left', 'TopRight' => 'top-right', 'BottomRight' => 'bottom-right', 'BottomLeft' => 'bottom-left'] as $k => $css) {
        $val = $s['borderRadius' . $k] ?? null;
        if ($val !== null && $val !== '') $innerStyles[] = 'border-' . $css . '-radius: ' . $val . ($s['borderRadius' . $k . 'Unit'] ?? 'px');
    }
    if (!empty($s['boxShadow'])) {
        $inset = ($s['boxShadowStyle'] ?? '') === 'inner' ? 'inset ' : '';
        $innerStyles[] = 'box-shadow: ' . $inset
            . intval($s['boxShadowPositionHorizontal'] ?? 0) . 'px '
            . intval($s['boxShadowPositionVertical']   ?? 0) . 'px '
            . intval($s['boxShadowBlurRadius']         ?? 0) . 'px '
            . intval($s['boxShadowSpreadRadius']       ?? 0) . 'px '
            . ($s['boxShadowColor'] ?? '#000000');
    }

    $htmlTag = $s['htmlTag'] ?? 'div';
    $link = !empty($s['linkUrl']) ? $s['linkUrl'] : null;
@endphp

@php
    $hoverClass = (!empty($s['hoverType']) && $s['hoverType'] !== 'none') ? 'hover-effect-' . $s['hoverType'] : '';
@endphp

<{{ $htmlTag }} class="lazy-column {{ $hoverClass }} {{ $visibilityClasses }} {{ $s['cssClass'] ?? '' }}"
    @if(!empty($s['cssId'])) id="{{ $s['cssId'] }}" @endif
    style="{{ implode('; ', $outerStyles) }}">
    
    @if($link)
        <a href="{{ $link }}" target="{{ $s['linkTarget'] ?? '_self' }}" style="text-decoration: none; color: inherit; display: flex !important; flex-direction: column !important; flex-grow: 1 !important; height: 100% !important; width: 100%;">
    @endif

    <div class="lazy-column-inner" style="{{ implode('; ', $innerStyles) }}">
        @if(!empty($column['elements']))
            @foreach($column['elements'] as $el)
                @if($el['type'] === 'heading')
                    @include('cms-dashboard::frontend.builder.elements.heading', ['el' => $el])
                @elseif($el['type'] === 'title')
                    @include('cms-dashboard::frontend.builder.elements.title', ['el' => $el])
                @elseif($el['type'] === 'text')
                    @include('cms-dashboard::frontend.builder.elements.text', ['el' => $el])
                @elseif($el['type'] === 'button')
                    @include('cms-dashboard::frontend.builder.elements.button', ['el' => $el])
                @elseif($el['type'] === 'image')
                    @include('cms-dashboard::frontend.builder.elements.image', ['el' => $el])
                @elseif($el['type'] === 'spacer')
                    @include('cms-dashboard::frontend.builder.elements.spacer', ['el' => $el])
                @elseif($el['type'] === 'video')
                    @include('cms-dashboard::frontend.builder.elements.video', ['el' => $el])
                @elseif($el['type'] === 'row')
                    @if($contentLayout === 'row')
                        <div style="flex-basis: 100%; width: 100%; height: 0; overflow: hidden;"></div>
                    @endif
                    @include('cms-dashboard::frontend.builder.container', ['container' => $el])
                    @if($contentLayout === 'row')
                        <div style="flex-basis: 100%; width: 100%; height: 0; overflow: hidden;"></div>
                    @endif
                @else
                    @php
                        $customBuilderDefs = apply_lazy_filters('lazy_builder_elements', []);
                        $customBuilderDef  = $customBuilderDefs[$el['type']] ?? null;
                    @endphp
                    @if($customBuilderDef)
                        @if(!empty($customBuilderDef['template']))
                            @include($customBuilderDef['template'], ['el' => $el, 's' => $el['settings'] ?? []])
                        @else
                            @include('cms-dashboard::frontend.builder.elements.custom', ['el' => $el, 'customDef' => $customBuilderDef])
                        @endif
                    @endif
                @endif
            @endforeach
        @endif
    </div>

    @if($link)
        </a>
    @endif
</{{ $htmlTag }}>
