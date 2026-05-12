<div class="widget mb-12">
    @if($widget->title)
        <h4 class="widget-title">{{ $widget->title }}</h4>
    @endif
    <ul class="space-y-3">
        @php 
            $taxonomy = $widget->settings['taxonomy'] ?? 'category';
            $categories = get_lazy_categories($taxonomy);
        @endphp
        @foreach($categories as $cat)
            <li>
                <a href="{{ route('frontend.category', $cat->slug) }}" class="flex items-center justify-between group">
                    <span class="text-sm text-slate-600 group-hover:text-primary transition-colors">{{ $cat->name }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>
