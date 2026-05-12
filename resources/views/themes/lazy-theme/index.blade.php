@extends('cms-dashboard::themes.lazy-theme.layouts.app')

@section('title', get_cms_option('site_title', 'Lazy Panda'))

@section('content')
    <!-- Hero Section -->
    <section class="py-24 bg-slate-50 border-b border-slate-100">
        <div class="container-custom text-center">
            <h1 class="text-5xl lg:text-7xl font-bold mb-8 tracking-tight text-slate-900">
                Welcome to {{ get_cms_option('site_title', 'Lazy Panda') }}
            </h1>
            <p class="text-xl text-slate-500 mb-12 max-w-3xl mx-auto leading-relaxed">
                {{ get_cms_option('site_description', 'Building beautiful digital experiences with simplicity and power.') }}
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ url('/posts') }}" class="btn-premium">Explore Stories</a>
                <a href="#" class="px-8 py-3 rounded font-bold border border-slate-200 text-slate-700 hover:bg-white transition shadow-sm">Documentation</a>
            </div>
        </div>
    </section>

    <!-- Recent Stories -->
    <section class="py-20 bg-white">
        <div class="container-custom">
            <div class="flex items-center justify-between mb-12">
                <h2 class="text-3xl font-bold tracking-tight">Recent Insights</h2>
                <a href="{{ url('/posts') }}" class="text-sm font-bold text-primary hover:underline">View All &rarr;</a>
            </div>

            @include('cms-dashboard::themes.lazy-theme.loop', ['posts' => get_lazy_posts(['limit' => 3])])
        </div>
    </section>

    <!-- Simple Newsletter -->
    <section class="py-20 bg-slate-50 border-t border-slate-100">
        <div class="container-custom">
            <div class="bg-white p-12 rounded shadow-sm border border-slate-100 text-center">
                <h2 class="text-3xl font-bold mb-6">Stay Connected</h2>
                <p class="text-slate-500 mb-8">Join our newsletter to receive the latest updates and exclusive content directly in your inbox.</p>
                <form class="flex flex-col md:flex-row gap-4 max-w-xl mx-auto">
                    <input type="email" placeholder="Enter your email address" class="flex-grow px-6 py-3 bg-slate-50 border border-slate-200 rounded outline-none focus:border-primary transition-all">
                    <button type="submit" class="btn-premium px-10">Subscribe</button>
                </form>
            </div>
        </div>
    </section>
@stop
