@extends('cms-dashboard::themes.lazy-theme.layouts.app')

@section('title', $post->title)

@section('content')
    @php
        $isBuilder = $post->editor_type === 'builder' || (is_string($post->content) && (str_starts_with($post->content, '[') || str_starts_with($post->content, '{')));
    @endphp

    @if($isBuilder)
        <div class="lazy-content-wrapper">
            {!! get_lazy_content($post->content) !!}
        </div>
    @else
        <!-- Page Header -->
        <section class="relative py-24 bg-slate-50 overflow-hidden border-b border-slate-100">
            <div class="absolute top-0 right-0 w-1/3 h-full bg-white -skew-x-12 transform translate-x-10"></div>
            <div class="container-custom relative">
                <div class="max-w-3xl">
                    <div class="mb-6">
                        @include('cms-dashboard::components.frontend.breadcrumbs', ['post' => $post])
                    </div>
                    <h1 class="text-4xl lg:text-6xl font-black mb-6 text-slate-900 tracking-tight">
                        {{ $post->title }}
                    </h1>
                    <div class="h-1.5 w-20 bg-primary rounded-full"></div>
                </div>
            </div>
        </section>

        <!-- Page Content -->
        <section class="py-20 bg-white">
            <div class="container-custom">
                <div class="prose prose-lg prose-slate max-w-none">
                    <div class="lazy-content-wrapper">
                        {!! do_lazy_shortcode($post->content) !!}
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
