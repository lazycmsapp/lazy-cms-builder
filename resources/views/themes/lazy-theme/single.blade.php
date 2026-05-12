@extends('cms-dashboard::themes.lazy-theme.layouts.app')

@section('title', $post->title)

@section('content')
    @php
        $isBuilder = $post->editor_type === 'builder'
            || (is_string($post->content) && (
                str_starts_with($post->content, '[')
                || str_starts_with($post->content, '{')
            ));
    @endphp

    @if($isBuilder)
        <div class="lazy-content-wrapper">
            {!! get_lazy_content($post->content) !!}
        </div>
        
        {{-- For builder pages, we might still want comments and tags at the bottom, but outside the 70/30 layout --}}
        <div class="container-custom py-16">
            @if($post->tags->isNotEmpty())
                <div class="mt-8 pt-8 border-t border-slate-100 flex items-center gap-3 flex-wrap">
                    <span class="text-xs font-black uppercase tracking-widest text-slate-400 mr-2">Tags:</span>
                    @foreach($post->tags as $tag)
                        <a href="{{ route('frontend.tag', $tag->slug) }}" class="px-4 py-2 bg-slate-50 hover:bg-primary hover:text-white text-slate-600 text-xs font-bold rounded-lg transition-all">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="mt-16">
                @include('cms-dashboard::themes.lazy-theme.partials.comments')
            </div>
        </div>
    @else
        <!-- Main Content Area -->
        <div class="py-16 bg-white">
            <div class="container-custom">
                <div class="flex flex-col lg:flex-row gap-16">
                    
                    <!-- Content Column -->
                    <article class="w-full lg:w-[70%]">
                        <header class="mb-10">
                            <div class="mb-6">
                                @include('cms-dashboard::components.frontend.breadcrumbs', ['post' => $post])
                            </div>
                            <h1 class="text-4xl lg:text-5xl font-bold mb-6 text-slate-900 leading-tight">
                                {{ $post->title }}
                            </h1>
                            <div class="flex items-center gap-6 text-sm text-slate-400 border-b border-slate-100 pb-8">
                                <span class="flex items-center gap-2">
                                    <i data-lucide="user" class="w-4 h-4 text-primary"></i>
                                    <span class="font-bold text-slate-600">{{ $post->user->name ?? 'Admin' }}</span>
                                </span>
                                <span class="flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-4 h-4 text-primary"></i>
                                    <span class="font-bold text-slate-600">{{ $post->created_at->format('M d, Y') }}</span>
                                </span>
                                <span class="flex items-center gap-2">
                                    <i data-lucide="folder" class="w-4 h-4 text-primary"></i>
                                    <span class="font-bold text-slate-600">{{ $post->categories->first()->name ?? 'Uncategorized' }}</span>
                                </span>
                            </div>
                        </header>

                        @if($post->featured_image)
                            <div class="mb-12 rounded-2xl overflow-hidden shadow-2xl shadow-slate-200/50">
                                <img src="{{ str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset('storage/'.$post->featured_image) }}" 
                                     class="w-full h-auto object-cover" alt="{{ $post->title }}">
                            </div>
                        @endif

                        <div class="lazy-content-wrapper">
                            @php 
                                $rawContent = do_lazy_shortcode($post->content);
                                $filteredContent = apply_lazy_filters('lazy_the_content', $rawContent, $post);
                            @endphp

                            {!! do_lazy_action('lazy_before_content', $post) !!}
                            <div class="entry-content">
                                {!! $filteredContent !!}
                            </div>
                            {!! do_lazy_action('lazy_after_content', $post) !!}
                        </div>

                        <!-- Tags -->
                        @if($post->tags->isNotEmpty())
                            <div class="mt-16 pt-8 border-t border-slate-100 flex items-center gap-3 flex-wrap">
                                <span class="text-xs font-black uppercase tracking-widest text-slate-400 mr-2">Tags:</span>
                                @foreach($post->tags as $tag)
                                    <a href="{{ route('frontend.tag', $tag->slug) }}" class="px-4 py-2 bg-slate-50 hover:bg-primary hover:text-white text-slate-600 text-xs font-bold rounded-lg transition-all">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <!-- Comments Section -->
                        <div class="mt-24">
                            @include('cms-dashboard::themes.lazy-theme.partials.comments')
                        </div>
                    </article>

                    <!-- Sidebar -->
                    <aside class="w-full lg:w-[30%] space-y-12">
                        @php $sidebarContent = render_lazy_widgets('primary-sidebar'); @endphp
                        @if($sidebarContent)
                            {!! $sidebarContent !!}
                        @else
                            <!-- Default Widgets if none configured -->
                            <div class="widget">
                                <h4 class="widget-title">Search</h4>
                                <form action="{{ route('frontend.search') }}" method="GET" class="relative">
                                    <input type="text" name="s" placeholder="Type and hit enter..." class="w-full border border-slate-200 rounded px-4 py-3 text-sm focus:border-primary outline-none transition-all">
                                </form>
                            </div>

                            <div class="widget">
                                <h4 class="widget-title">Recent Posts</h4>
                                <div class="space-y-6">
                                    @foreach(get_lazy_posts(['limit' => 5]) as $recent)
                                        <div class="flex gap-4 group">
                                            @if($recent->featured_image)
                                                <div class="w-16 h-16 shrink-0 bg-slate-50 rounded overflow-hidden border border-slate-100">
                                                    <img src="{{ str_starts_with($recent->featured_image, 'http') ? $recent->featured_image : asset('storage/'.$recent->featured_image) }}" 
                                                         class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="{{ $recent->title }}">
                                                </div>
                                            @endif
                                            <div>
                                                <h5 class="text-sm font-bold leading-snug group-hover:text-primary transition-colors">
                                                    <a href="{{ get_lazy_permalink($recent) }}">{{ $recent->title }}</a>
                                                </h5>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase mt-2 tracking-widest">{{ $recent->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </aside>

                </div>
            </div>
        </div>
    @endif
@stop
