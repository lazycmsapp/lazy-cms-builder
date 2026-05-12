@php
    $s = $el['settings'] ?? [];

    $v = $s['visibility'] ?? ['mobile' => true, 'tablet' => true, 'desktop' => true];
    $visibilityClasses = '';
    if (!($v['mobile']  ?? true)) $visibilityClasses .= ' lazy-hide-mobile';
    if (!($v['tablet']  ?? true)) $visibilityClasses .= ' lazy-hide-tablet';
    if (!($v['desktop'] ?? true)) $visibilityClasses .= ' lazy-hide-desktop';

    $url      = $s['url']      ?? $s['videoUrl'] ?? '';
    $autoplay = !empty($s['autoplay'])  ? 1 : 0;
    $muted    = !empty($s['muted'])     ? 1 : 0;
    $loop     = !empty($s['loop'])      ? 1 : 0;
    $controls = isset($s['controls'])   ? (int)(bool)$s['controls'] : 1;
    $aspect   = $s['aspectRatio']       ?? '16-9';

    // Normalise URL to embed URL
    $embedUrl = '';
    if ($url) {
        // YouTube
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $m)) {
            $params = http_build_query(array_filter([
                'autoplay' => $autoplay,
                'mute'     => $autoplay ? 1 : $muted,
                'loop'     => $loop,
                'playlist' => $loop ? $m[1] : null,
                'controls' => $controls,
            ], fn($v) => $v !== null));
            $embedUrl = 'https://www.youtube.com/embed/' . $m[1] . ($params ? '?' . $params : '');
        }
        // Vimeo
        elseif (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
            $params = http_build_query(array_filter([
                'autoplay' => $autoplay,
                'muted'    => $autoplay ? 1 : $muted,
                'loop'     => $loop,
                'controls' => $controls ? null : 0,
            ], fn($v) => $v !== null));
            $embedUrl = 'https://player.vimeo.com/video/' . $m[1] . ($params ? '?' . $params : '');
        }
        // Direct video file
        else {
            $embedUrl = null;
        }
    }

    $paddingMap = ['16-9' => '56.25%', '4-3' => '75%', '1-1' => '100%', '9-16' => '177.78%'];
    $paddingBottom = $paddingMap[$aspect] ?? '56.25%';

    $wrapperStyle = '';
    if (isset($s['marginTop'])    && $s['marginTop']    !== '') $wrapperStyle .= 'margin-top: '    . $s['marginTop']    . ($s['marginTopUnit']    ?? 'px') . '; ';
    if (isset($s['marginBottom']) && $s['marginBottom'] !== '') $wrapperStyle .= 'margin-bottom: ' . $s['marginBottom'] . ($s['marginBottomUnit'] ?? 'px') . '; ';
@endphp

<div class="element-video {{ $visibilityClasses }}" style="{{ $wrapperStyle }}">
    @if($url)
        @if($embedUrl)
            <div style="position: relative; width: 100%; padding-bottom: {{ $paddingBottom }}; height: 0; overflow: hidden;">
                <iframe src="{{ $embedUrl }}"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                        allowfullscreen
                        allow="autoplay; encrypted-media; picture-in-picture">
                </iframe>
            </div>
        @else
            <video style="width: 100%; display: block;"
                   {{ $controls ? 'controls' : '' }}
                   {{ $autoplay ? 'autoplay' : '' }}
                   {{ $muted || $autoplay ? 'muted' : '' }}
                   {{ $loop ? 'loop' : '' }}
                   playsinline>
                <source src="{{ $url }}">
            </video>
        @endif
    @else
        <div style="background: #1d2327; padding: 60px 20px; text-align: center; color: #8c8f94; font-size: 13px; border-radius: 4px;">
            No video URL set
        </div>
    @endif
</div>
