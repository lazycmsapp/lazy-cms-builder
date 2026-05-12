@if(!empty($layout))
    @foreach($layout as $container)
        @include('cms-dashboard::frontend.builder.container', ['container' => $container])
    @endforeach
@endif
