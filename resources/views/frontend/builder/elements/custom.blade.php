@php
    $s      = $el['settings'] ?? [];
    $fields = $customDef['fields'] ?? [];

    $v = $s['visibility'] ?? ['mobile' => true, 'tablet' => true, 'desktop' => true];
    $visibilityClasses = '';
    if (!($v['mobile']  ?? true)) $visibilityClasses .= ' lazy-hide-mobile';
    if (!($v['tablet']  ?? true)) $visibilityClasses .= ' lazy-hide-tablet';
    if (!($v['desktop'] ?? true)) $visibilityClasses .= ' lazy-hide-desktop';
@endphp

<div class="lazy-custom-element {{ $s['cssClass'] ?? '' }} {{ $visibilityClasses }}"
     @if(!empty($s['cssId'])) id="{{ $s['cssId'] }}" @endif>
    @foreach($fields as $fieldKey => $field)
        @php $val = $s[$fieldKey] ?? null; @endphp
        @if($val !== null && $val !== '' && $val !== false)
            @if(($field['type'] ?? 'text') === 'image')
                <img src="{{ $val }}" alt="{{ $s['alt'] ?? '' }}" class="max-w-full h-auto">
            @elseif(in_array($field['type'] ?? 'text', ['text', 'textarea']))
                <p>{{ $val }}</p>
            @endif
        @endif
    @endforeach
</div>
