<style>
    .element-image {
        overflow: hidden;
    }
    .element-image img {
        transition: all 0.3s ease-in-out;
        display: block;
    }
    .hover-zoom-in:hover img { transform: scale(1.1); }
    .hover-zoom-out:hover img { transform: scale(0.9); }
    .hover-lift:hover img { transform: translateY(-8px); }
    .hover-shadow:hover img { box-shadow: 0 15px 35px rgba(0,0,0,0.2); }
    .hover-opacity:hover img { opacity: 0.7; }
</style>

@if(!empty($layout))
    @foreach($layout as $container)
        @include('cms-dashboard::frontend.builder.container', ['container' => $container])
    @endforeach
@endif
