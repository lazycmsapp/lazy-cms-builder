@php
    $s = $el['settings'] ?? [];

    $v = $s['visibility'] ?? ['mobile' => true, 'tablet' => true, 'desktop' => true];
    $visibilityClasses = '';
    if (!($v['mobile']  ?? true)) $visibilityClasses .= ' lazy-hide-mobile';
    if (!($v['tablet']  ?? true)) $visibilityClasses .= ' lazy-hide-tablet';
    if (!($v['desktop'] ?? true)) $visibilityClasses .= ' lazy-hide-desktop';

    $bpSm  = (int) get_cms_option('theme_small_screen_breakpoint',  '800');
    $bpMed = (int) get_cms_option('theme_medium_screen_breakpoint', '1100');
    $bpSm1 = $bpSm + 1;

    $elemId = 'pc-' . str_replace('.', '', uniqid('', true));

    $getRespVal = function(string $prop, string $dev) use ($s): ?string {
        if ($dev === 'mobile') {
            if (isset($s[$prop . '_mobile']) && $s[$prop . '_mobile'] !== '') return (string)$s[$prop . '_mobile'];
            if (isset($s[$prop . '_tablet']) && $s[$prop . '_tablet'] !== '') return (string)$s[$prop . '_tablet'];
        } elseif ($dev === 'tablet') {
            if (isset($s[$prop . '_tablet']) && $s[$prop . '_tablet'] !== '') return (string)$s[$prop . '_tablet'];
        }
        return null;
    };

    // Responsive CSS — single braces, text-align targets both wrapper and inner text
    $respCss = '';
    foreach ([
        ['tablet', "@media(min-width:{$bpSm1}px) and (max-width:{$bpMed}px)"],
        ['mobile',  "@media(max-width:{$bpSm}px)"],
    ] as [$rDev, $rMq]) {
        $rules = [];
        $rAlign = $getRespVal('textAlign', $rDev);
        if ($rAlign !== null) $rules[] = "text-align:{$rAlign}!important";
        foreach (['marginTop' => 'margin-top', 'marginRight' => 'margin-right', 'marginBottom' => 'margin-bottom', 'marginLeft' => 'margin-left'] as $mProp => $cssProp) {
            $val = $getRespVal($mProp, $rDev);
            if ($val !== null) {
                $unit = $rDev === 'mobile'
                    ? ($s[$mProp . 'Unit_mobile'] ?? $s[$mProp . 'Unit_tablet'] ?? $s[$mProp . 'Unit'] ?? 'px')
                    : ($s[$mProp . 'Unit_tablet'] ?? $s[$mProp . 'Unit'] ?? 'px');
                $rules[] = "{$cssProp}:{$val}{$unit}!important";
            }
        }
        if (!empty($rules)) {
            $respCss .= "{$rMq}{.{$elemId}{" . implode(';', $rules) . "}}";
        }
    }

    // Desktop base styles — width:100% and align-self:stretch so it fills column width
    $wrapStyle  = "width:100%;align-self:stretch;";
    $wrapStyle .= "text-align:" . ($s['textAlign'] ?? 'left') . ";";
    $wrapStyle .= "margin-top:"    . ($s['marginTop']    ?? 0) . ($s['marginTopUnit']    ?? 'px') . ";";
    $wrapStyle .= "margin-right:"  . ($s['marginRight']  ?? 0) . ($s['marginRightUnit']  ?? 'px') . ";";
    $wrapStyle .= "margin-bottom:" . ($s['marginBottom'] ?? 8) . ($s['marginBottomUnit'] ?? 'px') . ";";
    $wrapStyle .= "margin-left:"   . ($s['marginLeft']   ?? 0) . ($s['marginLeftUnit']   ?? 'px') . ";";

    // Typography — text-align explicitly inherited so responsive overrides cascade properly
    $typoStyle  = "font-family:"    . ($s['fontFamily']    ?? 'inherit') . ";";
    $typoStyle .= "font-size:"      . ($s['fontSize']      ?? 13) . ($s['fontSizeUnit'] ?? 'px') . ";";
    $typoStyle .= "font-weight:"    . ($s['fontWeight']    ?? '400') . ";";
    $typoStyle .= "line-height:"    . ($s['lineHeight']    ?? '1.6') . ";";
    $typoStyle .= "letter-spacing:" . ($s['letterSpacing'] ?? 0) . "px;";
    $typoStyle .= "text-transform:" . ($s['textTransform'] ?? 'none') . ";";
    $typoStyle .= "color:"          . ($s['color']          ?? '#6b7280') . ";";
    $typoStyle .= "text-align:inherit;margin:0;";

    // Resolve content
    $contentDisplay = $s['content_display'] ?? 'excerpt';
    $stripHtml      = $s['stripHtml'] ?? true;
    $excerptLength  = max(10, (int)($s['excerptLength'] ?? 120));

    if ($contentDisplay === 'full') {
        $rawContent = $postContent ?? strip_tags($postExcerpt ?? '');
        $output     = $stripHtml ? strip_tags($rawContent) : ($postContent ?? '');
    } else {
        $rawContent = $postExcerpt ?? $postContent ?? '';
        if ($stripHtml) $rawContent = strip_tags($rawContent);
        $output = mb_strlen($rawContent) > $excerptLength
            ? mb_substr($rawContent, 0, $excerptLength) . '…'
            : $rawContent;
    }

    $cssId  = $s['cssId']    ?? '';
    $cssCls = $s['cssClass'] ?? '';
@endphp

@if($respCss)
<style>{!! $respCss !!}</style>
@endif

@if($output !== '')
<div class="element-post-content {{ $elemId }} {{ $cssCls }} {{ $visibilityClasses }}"
     @if($cssId) id="{{ $cssId }}" @endif
     style="{{ $wrapStyle }}">
    @if($contentDisplay === 'full' && !$stripHtml)
        <div style="{{ $typoStyle }}">{!! $output !!}</div>
    @else
        <p style="{{ $typoStyle }}">{{ $output }}</p>
    @endif
</div>
@endif
