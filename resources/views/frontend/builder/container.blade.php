@php
    $s = $container['settings'] ?? [];
    $heightMode = $s['height'] ?? 'auto';
    
    $containerStyles = ['width: 100%'];

    $hexToRgba = function($hex, $opacity) {
        if (empty($hex) || $hex === 'transparent') return 'transparent';
        if (strpos($hex, 'rgba') !== false) return $hex;
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) === 3) {
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

    // Solid color (shows through transparent gradient stops or behind images)
    if (!empty($s['bgColor'])) {
        $containerStyles[] = "background-color: " . $hexToRgba($s['bgColor'], $s['bgColorOpacity'] ?? 1);
    }

    // Gradient and/or image as layered background-image (gradient on top, image below)
    $bgImages = [];
    if (!empty($s['bgGradientStartColor']) && !empty($s['bgGradientEndColor'])) {
        $gType    = $s['bgGradientType'] ?? 'linear';
        $angle    = $s['bgGradientAngle'] ?? 180;
        $startPos = $s['bgGradientStartPosition'] ?? 0;
        $endPos   = $s['bgGradientEndPosition']   ?? 100;
        $start    = $hexToRgba($s['bgGradientStartColor'], $s['bgGradientStartOpacity'] ?? $s['bgColorOpacity'] ?? 1);
        $end      = $hexToRgba($s['bgGradientEndColor'],   $s['bgGradientEndOpacity']   ?? $s['bgColorOpacity'] ?? 1);
        if ($gType === 'linear') {
            $bgImages[] = "linear-gradient({$angle}deg, {$start} {$startPos}%, {$end} {$endPos}%)";
        } else {
            $bgImages[] = "radial-gradient(circle at center, {$start} {$startPos}%, {$end} {$endPos}%)";
        }
    }
    if (!empty($s['bgImage'])) {
        $bgImages[] = "url('{$s['bgImage']}')";
        $containerStyles[] = "background-size: "       . ($s['bgImageSize']     ?? 'cover');
        $containerStyles[] = "background-position: "   . ($s['bgImagePosition'] ?? 'center center');
        $containerStyles[] = "background-repeat: "     . ($s['bgImageRepeat']   ?? 'no-repeat');
        $containerStyles[] = "background-attachment: " . (($s['bgImageParallax'] ?? 'none') === 'fixed' ? 'fixed' : 'scroll');
        if (!empty($s['bgImageBlendMode']) && $s['bgImageBlendMode'] !== 'normal') {
            $containerStyles[] = "background-blend-mode: {$s['bgImageBlendMode']}";
        }
    }
    if (!empty($bgImages)) {
        $containerStyles[] = "background-image: " . implode(', ', $bgImages);
    }
    
    // Spacing
    if (isset($s['marginTop']) && $s['marginTop'] !== '') $containerStyles[] = "margin-top: {$s['marginTop']}" . ($s['marginTopUnit'] ?? 'px');
    if (isset($s['marginBottom']) && $s['marginBottom'] !== '') $containerStyles[] = "margin-bottom: {$s['marginBottom']}" . ($s['marginBottomUnit'] ?? 'px');
    if (isset($s['paddingTop']) && $s['paddingTop'] !== '') $containerStyles[] = "padding-top: {$s['paddingTop']}" . ($s['paddingTopUnit'] ?? 'px');
    if (isset($s['paddingBottom']) && $s['paddingBottom'] !== '') $containerStyles[] = "padding-bottom: {$s['paddingBottom']}" . ($s['paddingBottomUnit'] ?? 'px');
    if (isset($s['paddingLeft']) && $s['paddingLeft'] !== '') $containerStyles[] = "padding-left: {$s['paddingLeft']}" . ($s['paddingLeftUnit'] ?? 'px');
    if (isset($s['paddingRight']) && $s['paddingRight'] !== '') $containerStyles[] = "padding-right: {$s['paddingRight']}" . ($s['paddingRightUnit'] ?? 'px');

    // Borders
    if (isset($s['borderSizeTop'])) $containerStyles[] = "border-top: {$s['borderSizeTop']}px solid " . ($s['borderColor'] ?? '#000');
    if (isset($s['borderSizeRight'])) $containerStyles[] = "border-right: {$s['borderSizeRight']}px solid " . ($s['borderColor'] ?? '#000');
    if (isset($s['borderSizeBottom'])) $containerStyles[] = "border-bottom: {$s['borderSizeBottom']}px solid " . ($s['borderColor'] ?? '#000');
    if (isset($s['borderSizeLeft'])) $containerStyles[] = "border-left: {$s['borderSizeLeft']}px solid " . ($s['borderColor'] ?? '#000');
    
    // Border Radius
    if (isset($s['borderRadiusTopLeft'])) $containerStyles[] = "border-top-left-radius: {$s['borderRadiusTopLeft']}" . ($s['borderRadiusTopLeftUnit'] ?? 'px');
    if (isset($s['borderRadiusTopRight'])) $containerStyles[] = "border-top-right-radius: {$s['borderRadiusTopRight']}" . ($s['borderRadiusTopRightUnit'] ?? 'px');
    if (isset($s['borderRadiusBottomRight'])) $containerStyles[] = "border-bottom-right-radius: {$s['borderRadiusBottomRight']}" . ($s['borderRadiusBottomRightUnit'] ?? 'px');
    if (isset($s['borderRadiusBottomLeft'])) $containerStyles[] = "border-bottom-left-radius: {$s['borderRadiusBottomLeft']}" . ($s['borderRadiusBottomLeftUnit'] ?? 'px');

    // Flex/Alignment Inner
    $justifyContent = $s['justifyContent'] ?? 'flex-start';
    $isSpaceDist = in_array($justifyContent, ['space-between', 'space-around', 'space-evenly']);
    $colGap = $isSpaceDist ? '0px' : ($s['columnGap'] ?? '20px');

    $alignItems = $s['alignItems'] ?? 'stretch';
    $isNestedRow = ($container['type'] ?? 'container') === 'row';

    // Align-content logic (matched with admin builder):
    // - Auto height: flex-start
    // - Fixed height: follow rowAlignContent or sync with alignItems
    if ($isNestedRow) {
        $alignContentVal = $s['rowAlignContent'] ?? 'flex-start';
    } elseif ($heightMode === 'auto') {
        $alignContentVal = 'flex-start';
    } else {
        // For fixed height, we want rows to stretch to fill the height by default,
        // which allows align-items to move columns within that space.
        $alignContentVal = $s['rowAlignContent'] ?? 'stretch';
    }

    $innerStyles = [
        'display: flex !important',
        'flex-wrap: ' . ($s['flexWrap'] ?? 'wrap'),
        'align-items: ' . $alignItems . ' !important',
        'justify-content: ' . $justifyContent,
        'align-content: ' . $alignContentVal . ' !important',
    ];

    if ($heightMode !== 'auto') {
        $val = ($heightMode === 'full' ? '100vh' : ($s['customHeight'] ?? 'auto'));
        $innerStyles[] = "min-height: {$val}";
        $innerStyles[] = "height: {$val}";
    } else {
        $hasContent = false;
        foreach ($container['columns'] as $col) {
            if (!empty($col['elements'])) {
                $hasContent = true;
                break;
            }
        }
        // Only use 100px default if container is empty
        $innerStyles[] = "min-height: " . (!empty($s['minHeight']) ? $s['minHeight'] : ($hasContent ? '8px' : '100px'));
    }
    
    // height:100% lets inner fill the outer when outer has a fixed height (custom/full)
    $innerStyles[] = ($heightMode !== 'auto') ? "height: 100%" : "height: auto";
    $innerStyles[] = "flex-grow: 1";
    $innerStyles[] = "width: 100%";

    $contentWidth = $s['contentWidth'] ?? 'site';
    $innerClass = ($contentWidth === 'site' && !$isNestedRow) ? 'container-custom mx-auto' : 'w-full';
    
    $htmlTag = $s['htmlTag'] ?? 'div';
    $status = $s['status'] ?? 'published';

    // Device Visibility
    $v = $s['visibility'] ?? ['mobile' => true, 'tablet' => true, 'desktop' => true];
    $visibilityClasses = '';
    if (!($v['mobile']  ?? true)) $visibilityClasses .= ' lazy-hide-mobile';
    if (!($v['tablet']  ?? true)) $visibilityClasses .= ' lazy-hide-tablet';
    if (!($v['desktop'] ?? true)) $visibilityClasses .= ' lazy-hide-desktop';
    if (!($v['mobile'] ?? true) && !($v['tablet'] ?? true) && !($v['desktop'] ?? true)) {
        $visibilityClasses = ' lazy-hide-all';
    }
    // Hover Logic
    $hoverClass = (!empty($s['hoverType']) && $s['hoverType'] !== 'none') ? 'hover-effect-' . $s['hoverType'] : '';

    $link = !empty($s['linkUrl']) ? $s['linkUrl'] : null;
    $linkTarget = $s['linkTarget'] ?? '_self';
@endphp

@if($status === 'published')
    <{{ $htmlTag }} id="{{ $s['menuAnchor'] ?? '' }}" class="lazy-container {{ $hoverClass }} {{ $s['cssClass'] ?? '' }} {{ $visibilityClasses }}" style="{{ implode('; ', $containerStyles) }}">
        @if($link)
            <a href="{{ $link }}" target="{{ $linkTarget }}" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; flex-grow: 1; width: 100%;">
        @endif
        <div class="lazy-container-inner {{ $innerClass }} flex flex-wrap" style="{{ implode('; ', $innerStyles) }}">
            @if(!empty($container['columns']))
                @foreach($container['columns'] as $column)
                    @include('cms-dashboard::frontend.builder.column', ['column' => $column, 'container' => $container])
                @endforeach
            @endif
        </div>
        @if($link)
            </a>
        @endif
    </{{ $htmlTag }}>
@endif
