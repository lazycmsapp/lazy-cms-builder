@php
    $s = $el['settings'] ?? [];

    $v = $s['visibility'] ?? ['mobile' => true, 'tablet' => true, 'desktop' => true];
    $visibilityClasses = '';
    if (!($v['mobile']  ?? true)) $visibilityClasses .= ' lazy-hide-mobile';
    if (!($v['tablet']  ?? true)) $visibilityClasses .= ' lazy-hide-tablet';
    if (!($v['desktop'] ?? true)) $visibilityClasses .= ' lazy-hide-desktop';

    $text   = $s['text']   ?? 'Click Me';
    $url    = $s['url']    ?? '#';
    $style  = $s['style']  ?? 'primary';
    $size   = $s['size']   ?? 'medium';
    $target = $s['linkTarget'] ?? $s['target'] ?? '_self';
    $align  = $s['textAlign'] ?? 'left';

    $sizeStyles = match($size) {
        'small'  => 'padding: 8px 18px; font-size: 13px;',
        'large'  => 'padding: 16px 40px; font-size: 17px;',
        default  => 'padding: 12px 28px; font-size: 15px;',
    };

    $btnColor     = $s['btnColor']     ?? '';
    $btnTextColor = $s['btnTextColor'] ?? '';
    $borderRadius = $s['borderRadius'] ?? '4';
    $borderRadiusUnit = $s['borderRadiusUnit'] ?? 'px';

    if ($style === 'primary') {
        $baseStyle = 'background-color:' . ($btnColor ?: 'var(--primary, #0274be)') . '; color:' . ($btnTextColor ?: '#ffffff') . '; border: 2px solid ' . ($btnColor ?: 'var(--primary, #0274be)') . ';';
    } elseif ($style === 'outline') {
        $baseStyle = 'background-color: transparent; color:' . ($btnColor ?: 'var(--primary, #0274be)') . '; border: 2px solid ' . ($btnColor ?: 'var(--primary, #0274be)') . ';';
    } else {
        $baseStyle = 'background-color:' . ($btnColor ?: '#f0f0f1') . '; color:' . ($btnTextColor ?: '#1d2327') . '; border: 2px solid ' . ($btnColor ?: '#c3c4c7') . ';';
    }

    $wrapperStyle = 'text-align: ' . $align . ';';
    if (isset($s['marginTop'])    && $s['marginTop']    !== '') $wrapperStyle .= ' margin-top: '    . $s['marginTop']    . ($s['marginTopUnit']    ?? 'px') . ';';
    if (isset($s['marginBottom']) && $s['marginBottom'] !== '') $wrapperStyle .= ' margin-bottom: ' . $s['marginBottom'] . ($s['marginBottomUnit'] ?? 'px') . ';';
@endphp

<div class="element-button {{ $visibilityClasses }}" style="{{ $wrapperStyle }}">
    <a href="{{ $url }}" target="{{ $target }}"
       style="{{ $baseStyle }} {{ $sizeStyles }} border-radius: {{ $borderRadius }}{{ $borderRadiusUnit }}; display: inline-block; font-weight: 600; text-decoration: none; line-height: 1; transition: opacity .15s, transform .15s; cursor: pointer;"
       onmouseover="this.style.opacity='0.85'; this.style.transform='translateY(-1px)';"
       onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)';">
        {{ $text }}
    </a>
</div>
