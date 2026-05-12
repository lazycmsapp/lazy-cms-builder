<div class="widget mb-12">
    @if($widget->title)
        <h4 class="widget-title">{{ $widget->title }}</h4>
    @endif
    <form action="{{ route('frontend.search') }}" method="GET" class="relative">
        <input type="text" name="q" placeholder="{{ $widget->settings['placeholder'] ?? 'Search...' }}" class="w-full border border-slate-200 rounded px-4 py-3 text-sm focus:border-primary outline-none transition-all">
    </form>
</div>
