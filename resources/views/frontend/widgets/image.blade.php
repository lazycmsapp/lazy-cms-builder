@php
    $imageUrl   = $widget->settings['image_url']   ?? '';
    $linkUrl    = $widget->settings['link_url']    ?? '';
    $linkTarget = $widget->settings['link_target'] ?? '_self';
    $altText    = $widget->settings['alt_text']    ?? ($widget->title ?: '');
    $caption    = $widget->settings['caption']     ?? '';
@endphp

@if($imageUrl)
<div class="widget mb-12">
    @if($widget->title)
        <h4 class="widget-title">{{ $widget->title }}</h4>
    @endif

    <figure class="m-0">
        @if($linkUrl)
            <a href="{{ $linkUrl }}" target="{{ $linkTarget }}" rel="{{ $linkTarget === '_blank' ? 'noopener noreferrer' : '' }}" class="block rounded-xl">
                <img src="{{ $imageUrl }}"
                     alt="{{ $altText }}"
                     class="w-full h-auto rounded-xl"
                     loading="lazy">
            </a>
        @else
            <img src="{{ $imageUrl }}"
                 alt="{{ $altText }}"
                 class="w-full h-auto rounded-xl"
                 loading="lazy">
        @endif

        @if($caption)
            <figcaption class="text-xs text-slate-400 text-center mt-2 leading-snug">{{ $caption }}</figcaption>
        @endif
    </figure>
</div>
@endif
