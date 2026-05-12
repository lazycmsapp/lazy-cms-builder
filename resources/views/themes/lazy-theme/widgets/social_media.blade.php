@php
    $facebook = !empty($widget->settings['facebook']) ? $widget->settings['facebook'] : get_cms_option('social_facebook');
    $twitter = !empty($widget->settings['twitter']) ? $widget->settings['twitter'] : get_cms_option('social_twitter');
    $instagram = !empty($widget->settings['instagram']) ? $widget->settings['instagram'] : get_cms_option('social_instagram');
    $linkedin = !empty($widget->settings['linkedin']) ? $widget->settings['linkedin'] : get_cms_option('social_linkedin');

    $svgs = [
        'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
        'twitter' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path></svg>',
        'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>',
        'linkedin' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>'
    ];
@endphp

<div class="widget mb-10">
    @if(!empty($widget->title))
        <h4 class="widget-title">{{ $widget->title }}</h4>
    @endif
    
    <div class="flex flex-wrap gap-3">
        @if(!empty($facebook))
        <a href="{{ $facebook }}" target="_blank" class="w-10 h-10 rounded-lg flex items-center justify-center text-white transition-all hover:scale-110 shadow-sm" style="background-color: #1877F2;" title="Facebook">
            <span class="w-6 h-6">{!! $svgs['facebook'] !!}</span>
        </a>
        @endif

        @if(!empty($twitter))
        <a href="{{ $twitter }}" target="_blank" class="w-10 h-10 rounded-lg flex items-center justify-center text-white transition-all hover:scale-110 shadow-sm" style="background-color: #000000;" title="X (Twitter)">
            <span class="w-5 h-5">{!! $svgs['twitter'] !!}</span>
        </a>
        @endif

        @if(!empty($instagram))
        <a href="{{ $instagram }}" target="_blank" class="w-10 h-10 rounded-lg flex items-center justify-center text-white transition-all hover:scale-110 shadow-sm" style="background-color: #E4405F;" title="Instagram">
            <span class="w-6 h-6">{!! $svgs['instagram'] !!}</span>
        </a>
        @endif

        @if(!empty($linkedin))
        <a href="{{ $linkedin }}" target="_blank" class="w-10 h-10 rounded-lg flex items-center justify-center text-white transition-all hover:scale-110 shadow-sm" style="background-color: #0077B5;" title="LinkedIn">
            <span class="w-6 h-6">{!! $svgs['linkedin'] !!}</span>
        </a>
        @endif
    </div>
</div>
