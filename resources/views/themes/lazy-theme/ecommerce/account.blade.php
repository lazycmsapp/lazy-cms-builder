@extends('cms-dashboard::themes.lazy-theme.layouts.app')

@section('title', 'My Account')

@section('content')
@php $user = auth()->user(); @endphp
<div class="bg-gray-50 py-16 min-h-screen">
    <div class="container-custom">

        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-heading">My Account</h1>
                <p class="text-body mt-1">Welcome back, <strong>{{ $user->name ?? 'Customer' }}</strong>.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('shop.wishlist') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-sm border border-slate-200 bg-white text-slate-600 text-sm font-bold hover:border-primary hover:text-primary transition">
                    <i data-lucide="heart" class="w-4 h-4"></i> Wishlist
                    <span class="lazy-wishlist-count bg-primary text-white text-[11px] font-bold rounded-full px-1.5 min-w-[18px] text-center">{{ lazy_wishlist_count() }}</span>
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-sm border border-slate-200 bg-white text-slate-600 text-sm font-bold hover:border-primary hover:text-primary transition">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Log out
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Account details --}}
            <div class="bg-white rounded-sm shadow-sm border border-gray-100 p-6 h-fit">
                <h2 class="text-sm font-black uppercase tracking-widest text-heading mb-4">Account Details</h2>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-[11px] uppercase tracking-wider text-gray-400">Name</dt>
                        <dd class="font-semibold text-heading">{{ $user->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-wider text-gray-400">Email</dt>
                        <dd class="font-semibold text-heading break-all">{{ $user->email ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Orders --}}
            <div class="lg:col-span-2 bg-white rounded-sm shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-sm font-black uppercase tracking-widest text-heading">My Orders</h2>
                    <form action="" method="GET" class="flex items-center gap-2">
                        <input type="text" name="s" value="{{ request('s') }}" placeholder="Search orders..."
                               class="border border-slate-200 rounded px-3 py-1.5 text-sm outline-none focus:border-primary transition w-44">
                        <button type="submit" class="bg-primary text-white px-3 py-1.5 rounded text-sm font-semibold hover:bg-primary-hover transition">Search</button>
                        @if(request('s'))
                            <a href="{{ url()->current() }}" class="text-xs text-slate-400 hover:text-primary">Clear</a>
                        @endif
                    </form>
                </div>

                @if($orders->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="package" class="w-8 h-8 text-slate-300"></i>
                        </div>
                        @if(request('s'))
                            <p class="text-body mb-5">No orders match “<strong>{{ request('s') }}</strong>”.</p>
                            <a href="{{ url()->current() }}" class="inline-block bg-primary text-white px-6 py-2.5 rounded-sm font-bold hover:bg-primary-hover transition uppercase text-sm">View all orders</a>
                        @else
                            <p class="text-body mb-5">You haven’t placed any orders yet.</p>
                            <a href="{{ get_lazy_shop_url() }}" class="inline-block bg-primary text-white px-6 py-2.5 rounded-sm font-bold hover:bg-primary-hover transition uppercase text-sm">Start shopping</a>
                        @endif
                    </div>
                @else
                    @php
                        $statusColors = [
                            'pending' => 'bg-amber-100 text-amber-700', 'processing' => 'bg-blue-100 text-blue-700',
                            'completed' => 'bg-emerald-100 text-emerald-700', 'cancelled' => 'bg-rose-100 text-rose-700',
                            'partially-refunded' => 'bg-purple-100 text-purple-700', 'refunded' => 'bg-gray-200 text-gray-600',
                            'on-hold' => 'bg-amber-100 text-amber-700', 'failed' => 'bg-rose-100 text-rose-700',
                        ];
                    @endphp
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-[12px] uppercase tracking-wider">
                                <th class="px-6 py-3 font-bold">Order</th>
                                <th class="px-6 py-3 font-bold">Date</th>
                                <th class="px-6 py-3 font-bold">Status</th>
                                <th class="px-6 py-3 font-bold text-right">Total</th>
                                <th class="px-6 py-3 font-bold text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr class="border-t border-gray-100 hover:bg-gray-50/60">
                                    <td class="px-6 py-4 font-bold text-heading">#{{ $order->order_number ?: $order->id }}</td>
                                    <td class="px-6 py-4 text-body">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">{{ str_replace('-', ' ', $order->status) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-heading">{{ lazy_price_format($order->total, $order) }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('shop.confirmation', $order->id) }}" class="text-primary font-bold hover:underline">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($orders->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 flex justify-end">
                            {{ $orders->links('cms-dashboard::components.admin.pagination') }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@stop
