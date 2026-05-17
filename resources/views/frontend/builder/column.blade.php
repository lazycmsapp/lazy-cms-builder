<style>
    @media (max-width: {{ get_cms_option('theme_small_screen_breakpoint', '800') }}px) { 
        .lazy-hide-mobile { display: none !important; } 
        .lazy-column {
            flex-basis: 100% !important;
            max-width: 100% !important;
            width: 100% !important;
        }
    }
    @media (min-width: {{ (int)get_cms_option('theme_small_screen_breakpoint', '800') + 1 }}px) and (max-width: {{ get_cms_option('theme_medium_screen_breakpoint', '1100') }}px) { .lazy-hide-tablet { display: none !important; } }
    @media (min-width: {{ (int)get_cms_option('theme_medium_screen_breakpoint', '1100') + 1 }}px) { .lazy-hide-desktop { display: none !important; } }
    .lazy-hide-all { display: none !important; }
</style>
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
    // Use CSS custom property --lc-col-gap (set responsively by container) for default column spacing
    $pLeft  = (isset($s['columnSpacingLeft'])  && $s['columnSpacingLeft']  !== '') ? $s['columnSpacingLeft']  . '%' : 'calc(var(--lc-col-gap, ' . $globalGap . ') * 1%)';
    $pRight = (isset($s['columnSpacingRight']) && $s['columnSpacingRight'] !== '') ? $s['columnSpacingRight'] . '%' : 'calc(var(--lc-col-gap, ' . $globalGap . ') * 1%)';

    $finalAlignSelf = ($colAlignment && $colAlignment !== 'default') ? $colAlignment : $containerAlign;
    $isDefaultAlign = in_array($colAlignment, ['', 'default', null], true);
    $colCid = 'lcc-' . ($column['id'] ?? str_replace('.', '', uniqid('', true)));

    $outerStyles = [
        "flex-basis: {$flexBasis}",
        "flex-shrink: " . ($s['flexShrink'] ?? '0'),
        "max-width: {$maxWidth}",
        "width: " . ($flexBasis === '100%' || $flexBasis === 'auto' ? '100%' : $flexBasis),
        "padding-left: {$pLeft}",
        "padding-right: {$pRight}",
        'display: flex !important',
        'flex-direction: column !important',
    ];
    if (!$isDefaultAlign) {
        $outerStyles[] = "flex-grow: " . ($shouldStretch ? '1' : ($s['flexGrow'] ?? '0'));
        $outerStyles[] = "min-height: " . ($shouldStretch ? ($isEmpty ? '100px' : 'auto') : 'auto');
        $outerStyles[] = "align-self: {$finalAlignSelf}";
    }

    $innerStyles = [
        'width: 100%',
        'flex-grow: 1',
        'margin: 0',
        'padding-top: '    . ($s['paddingTop']    ?? 10) . ($s['paddingTopUnit']    ?? 'px'),
        'padding-right: '  . ($s['paddingRight']  ?? 10) . ($s['paddingRightUnit']  ?? 'px'),
        'padding-bottom: ' . ($s['paddingBottom'] ?? 10) . ($s['paddingBottomUnit'] ?? 'px'),
        'padding-left: '   . ($s['paddingLeft']   ?? 10) . ($s['paddingLeftUnit']   ?? 'px'),
        'align-items: stretch',
    ];

    if (isset($s['marginTop']) && $s['marginTop'] !== '') $outerStyles[] = 'margin-top: ' . $s['marginTop'] . ($s['marginTopUnit'] ?? 'px');
    if (isset($s['marginBottom']) && $s['marginBottom'] !== '') $outerStyles[] = 'margin-bottom: ' . $s['marginBottom'] . ($s['marginBottomUnit'] ?? 'px');

    $innerStyles = [
        'padding-top: '    . ($s['paddingTop']    ?? 10) . ($s['paddingTopUnit'] ?? 'px'),
        'padding-bottom: ' . ($s['paddingBottom'] ?? 10) . ($s['paddingBottomUnit'] ?? 'px'),
        'padding-left: '   . ($s['paddingLeft']   ?? 10) . ($s['paddingLeftUnit'] ?? 'px'),
        'padding-right: '  . ($s['paddingRight']  ?? 10) . ($s['paddingRightUnit'] ?? 'px'),
        'box-sizing: border-box',
    ];
    if (!$isDefaultAlign) {
        array_unshift($innerStyles,
            'min-height: ' . ($shouldStretch ? 'auto' : '8px'),
            'flex: ' . ($shouldStretch ? '1 1 auto' : '0 1 auto'),
            'flex-grow: ' . ($shouldStretch ? '1' : '0')
        );
    }
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
    if (!empty($s['zIndex']))   $outerStyles[] = 'z-index: ' . $s['zIndex'];
    if (!empty($s['overflow']) && $s['overflow'] !== 'default') $innerStyles[] = 'overflow: ' . $s['overflow'];

    // Per-column responsive CSS for default-alignment columns
    $colCss = '';
    if ($isDefaultAlign) {
        $cs       = $container['settings'] ?? [];
        $colBp    = (int) get_cms_option('theme_small_screen_breakpoint', '800');
        $colBpMed = (int) get_cms_option('theme_medium_screen_breakpoint', '1100');
        $colBpSm1 = $colBp + 1;

        $getColAlign = function(string $device) use ($cs): string {
            if ($device === 'mobile') {
                if (isset($cs['alignItems_mobile']) && $cs['alignItems_mobile'] !== '') return $cs['alignItems_mobile'];
                if (isset($cs['alignItems_tablet']) && $cs['alignItems_tablet'] !== '') return $cs['alignItems_tablet'];
            } elseif ($device === 'tablet') {
                if (isset($cs['alignItems_tablet']) && $cs['alignItems_tablet'] !== '') return $cs['alignItems_tablet'];
            }
            return $cs['alignItems'] ?? 'stretch';
        };

        $hasColAlignOvr = function(string $device) use ($cs): bool {
            if ($device === 'tablet') return isset($cs['alignItems_tablet']) && $cs['alignItems_tablet'] !== '';
            return (isset($cs['alignItems_mobile']) && $cs['alignItems_mobile'] !== '')
                || (isset($cs['alignItems_tablet']) && $cs['alignItems_tablet'] !== '');
        };

        $buildColRules = function(string $align) use ($s, $isEmpty): array {
            $stretch = ($align === 'stretch');
            return [
                'outer' => [
                    "align-self:{$align}!important",
                    "flex-grow:" . ($stretch ? '1' : ($s['flexGrow'] ?? '0')) . "!important",
                    "min-height:" . ($stretch ? ($isEmpty ? '100px' : 'auto') : 'auto') . "!important",
                ],
                'inner' => [
                    "min-height:" . ($stretch ? 'auto' : '8px') . "!important",
                    "flex:" . ($stretch ? '1 1 auto' : '0 1 auto') . "!important",
                    "flex-grow:" . ($stretch ? '1' : '0') . "!important",
                ],
            ];
        };

        $innerSel = ".{$colCid}>.lazy-column-inner,.{$colCid}>a>.lazy-column-inner";
        $dRules = $buildColRules($containerAlign);
        $colCss .= ".{$colCid}{" . implode(';', $dRules['outer']) . "}";
        $colCss .= "{$innerSel}{" . implode(';', $dRules['inner']) . "}";

        if ($hasColAlignOvr('tablet')) {
            $tRules = $buildColRules($getColAlign('tablet'));
            $colCss .= "@media(min-width:{$colBpSm1}px) and (max-width:{$colBpMed}px){";
            $colCss .= ".{$colCid}{" . implode(';', $tRules['outer']) . "}";
            $colCss .= "{$innerSel}{" . implode(';', $tRules['inner']) . "}";
            $colCss .= "}";
        }

        if ($hasColAlignOvr('mobile')) {
            $mRules = $buildColRules($getColAlign('mobile'));
            $colCss .= "@media(max-width:{$colBp}px){";
            $colCss .= ".{$colCid}{" . implode(';', $mRules['outer']) . "}";
            $colCss .= "{$innerSel}{" . implode(';', $mRules['inner']) . "}";
            $colCss .= "}";
        }
    }

    // Per-column responsive overrides: alignment, contentAlignH, contentAlignV
    $rBp    = (int) get_cms_option('theme_small_screen_breakpoint', '800');
    $rBpMed = (int) get_cms_option('theme_medium_screen_breakpoint', '1100');
    $rBpSm1 = $rBp + 1;
    $rInnerSel = ".{$colCid}>.lazy-column-inner,.{$colCid}>a>.lazy-column-inner";
    $getColRespOvr = function(string $prop, string $dev) use ($s): ?string {
        if ($dev === 'mobile') {
            if (isset($s[$prop . '_mobile']) && $s[$prop . '_mobile'] !== '') return (string)$s[$prop . '_mobile'];
            if (isset($s[$prop . '_tablet']) && $s[$prop . '_tablet'] !== '') return (string)$s[$prop . '_tablet'];
        } elseif ($dev === 'tablet') {
            if (isset($s[$prop . '_tablet']) && $s[$prop . '_tablet'] !== '') return (string)$s[$prop . '_tablet'];
        }
        return null;
    };
    foreach ([['tablet', "@media(min-width:{$rBpSm1}px) and (max-width:{$rBpMed}px)"], ['mobile', "@media(max-width:{$rBp}px)"]] as [$rDev, $rMq]) {
        $rOuter = []; $rInner = [];
        $rAlign = $getColRespOvr('alignment', $rDev);
        if ($rAlign && $rAlign !== 'default') {
            $rOuter[] = "align-self:{$rAlign}!important";
            $rStretch = ($rAlign === 'stretch');
            $rOuter[] = 'flex-grow:' . ($rStretch ? '1' : '0') . '!important';
            $rInner[] = 'flex:' . ($rStretch ? '1 1 auto' : '0 1 auto') . '!important';
            $rInner[] = 'flex-grow:' . ($rStretch ? '1' : '0') . '!important';
        }
        $rAlignH = $getColRespOvr('contentAlignH', $rDev);
        $rAlignV = $getColRespOvr('contentAlignV', $rDev);
        if ($rAlignH || $rAlignV) {
            $rCl = $contentLayout ?: 'column';
            if ($rCl === 'row') {
                if ($rAlignH) $rInner[] = "justify-content:{$rAlignH}!important";
                if ($rAlignV) $rInner[] = "align-items:{$rAlignV}!important";
            } else {
                if ($rAlignV) $rInner[] = "justify-content:{$rAlignV}!important";
                if ($rAlignH) $rInner[] = "align-items:{$rAlignH}!important";
            }
        }
        if ($rOuter || $rInner) {
            $colCss .= "{$rMq}{";
            if ($rOuter) $colCss .= ".{$colCid}{" . implode(';', $rOuter) . "}";
            if ($rInner) $colCss .= "{$rInnerSel}{" . implode(';', $rInner) . "}";
            $colCss .= "}";
        }
    }

    // Sticky column CSS
    if (!empty($s['sticky'])) {
        $stickyOffset  = isset($s['stickyOffset'])  ? (int) $s['stickyOffset']  : 0;
        $stickyZIndex  = isset($s['stickyZIndex'])  ? (int) $s['stickyZIndex']  : 99;
        $stickyDesktop = $s['stickyDesktop'] ?? true;
        $stickyTablet  = $s['stickyTablet']  ?? true;
        $stickyMobile  = $s['stickyMobile']  ?? true;
        $sOn  = "position:sticky;top:{$stickyOffset}px;z-index:{$stickyZIndex};";
        $sOff = "position:static!important;top:auto!important;z-index:auto!important;";
        $colBp    = (int) get_cms_option('theme_small_screen_breakpoint', '800');
        $colBpMed = (int) get_cms_option('theme_medium_screen_breakpoint', '1100');
        $colBpSm1 = $colBp + 1;
        if ($stickyDesktop) $colCss .= ".{$colCid}{{$sOn}}";
        if ($stickyTablet !== $stickyDesktop) {
            $rule = $stickyTablet ? str_replace(';', '!important;', $sOn) : $sOff;
            $colCss .= "@media(min-width:{$colBpSm1}px) and (max-width:{$colBpMed}px){.{$colCid}{{$rule}}}";
        }
        if ($stickyMobile !== $stickyTablet) {
            $rule = $stickyMobile ? str_replace(';', '!important;', $sOn) : $sOff;
            $colCss .= "@media(max-width:{$colBp}px){.{$colCid}{{$rule}}}";
        }
    }

    $htmlTag = $s['htmlTag'] ?? 'div';
    $link = !empty($s['linkUrl']) ? $s['linkUrl'] : null;
@endphp

@php
    $hoverClass = (!empty($s['hoverType']) && $s['hoverType'] !== 'none') ? 'hover-effect-' . $s['hoverType'] : '';
@endphp

{!! $colCss ? '<style>' . $colCss . '</style>' : '' !!}

<{{ $htmlTag }} class="lazy-column {{ $colCid }} {{ $hoverClass }} {{ $visibilityClasses }} {{ $s['cssClass'] ?? '' }}"
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
                @elseif($el['type'] === 'image')
                    @include('cms-dashboard::frontend.builder.elements.image', ['el' => $el])
                @elseif($el['type'] === 'text_block')
                    @include('cms-dashboard::frontend.builder.elements.text-block', ['el' => $el])
                @elseif($el['type'] === 'button')
                    @include('cms-dashboard::frontend.builder.elements.button', ['el' => $el])
                @elseif($el['type'] === 'menu')
                    @include('cms-dashboard::frontend.builder.elements.menu', ['el' => $el])
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
