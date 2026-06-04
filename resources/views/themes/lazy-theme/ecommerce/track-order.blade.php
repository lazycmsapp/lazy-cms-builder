@extends('cms-dashboard::themes.lazy-theme.layouts.app')

@section('title', 'Track Your Order')

@section('content')
<div class="bg-gray-50 py-16 min-h-screen">
    <div class="container-custom max-w-3xl">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-heading">Track Your Order</h1>
            <p class="text-body mt-2">Enter your order number and email to see its latest status.</p>
        </div>

        {{-- Lookup form --}}
        <div class="bg-white rounded-sm shadow-sm border border-gray-100 p-6 mb-8">
            <form action="{{ route('shop.track') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                @csrf
                <div class="sm:col-span-1">
                    <label class="block text-[12px] font-bold text-heading mb-1">Order Number</label>
                    <input type="text" name="order_number" value="{{ old('order_number', request('order_number')) }}" placeholder="ORD-XXXXXXXX"
                           class="w-full border border-slate-200 rounded px-3 py-2.5 text-sm outline-none focus:border-primary transition" required>
                </div>
                <div class="sm:col-span-1">
                    <label class="block text-[12px] font-bold text-heading mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', request('email')) }}" placeholder="you@example.com"
                           class="w-full border border-slate-200 rounded px-3 py-2.5 text-sm outline-none focus:border-primary transition" required>
                </div>
                <div class="sm:col-span-1">
                    <button type="submit" class="w-full bg-primary text-white px-4 py-2.5 rounded font-bold text-sm hover:bg-primary-hover transition uppercase">Track Order</button>
                </div>
            </form>
        </div>

        @if(!empty($notFound))
            <div class="bg-rose-50 border border-rose-100 text-rose-700 rounded-sm p-4 text-sm text-center">
                No order found with that order number and email. Please check and try again.
            </div>
        @endif

        @if(!empty($order))
            @php
                $statusMap = [
                    'pending'            => ['Order Placed', '#dba617'],
                    'processing'         => ['Processing', '#2271b1'],
                    'on-hold'            => ['On Hold', '#646970'],
                    'completed'          => ['Completed', '#46b450'],
                    'cancelled'          => ['Cancelled', '#d63638'],
                    'failed'             => ['Failed', '#d63638'],
                    'partially-refunded' => ['Partially Refunded', '#8c44db'],
                    'refunded'           => ['Refunded', '#646970'],
                ];
                [$stLabel, $stColor] = $statusMap[$order->status] ?? [ucfirst($order->status), '#646970'];
                $flow = ['pending' => 0, 'processing' => 1, 'completed' => 2];
                $isTerminalBad = in_array($order->status, ['cancelled', 'failed']);
                $currentStep = $flow[$order->status] ?? ($order->status === 'completed' ? 2 : 1);
            @endphp

            <div class="bg-white rounded-sm shadow-sm border border-gray-100 overflow-hidden">
                {{-- Header --}}
                <div class="p-6 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <span class="text-[12px] text-gray-400 uppercase tracking-wider">Order</span>
                        <div class="text-lg font-bold text-heading">#{{ $order->order_number }}</div>
                        <div class="text-[12px] text-body">Placed on {{ $order->created_at->format('M d, Y') }}</div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-[12px] font-bold uppercase" style="background:{{ $stColor }}1a;color:{{ $stColor }}">{{ $stLabel }}</span>
                </div>

                {{-- Progress timeline --}}
                <div class="p-6">
                    @if($isTerminalBad)
                        <div class="text-center py-4">
                            <span class="material-symbols-outlined text-[40px]" style="color:{{ $stColor }}">cancel</span>
                            <p class="text-body mt-2">This order was <strong style="color:{{ $stColor }}">{{ strtolower($stLabel) }}</strong>.</p>
                        </div>
                    @else
                        <div class="flex items-center justify-between relative">
                            @foreach(['Order Placed', 'Processing', 'Completed'] as $i => $label)
                                @php $done = $i <= $currentStep; @endphp
                                <div class="flex-1 flex flex-col items-center relative z-10">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-[14px] font-bold"
                                         style="background:{{ $done ? '#46b450' : '#d1d5db' }}">
                                        @if($done)<i data-lucide="check" class="w-4 h-4"></i>@else{{ $i + 1 }}@endif
                                    </div>
                                    <span class="text-[11px] font-semibold mt-2 text-center {{ $done ? 'text-heading' : 'text-gray-400' }}">{{ $label }}</span>
                                </div>
                                @if($i < 2)
                                    <div class="flex-1 h-1 -mx-2" style="background:{{ $i < $currentStep ? '#46b450' : '#e5e7eb' }}"></div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Tracking info --}}
                @if($order->tracking_number)
                    <div class="px-6 pb-6">
                        <div class="bg-blue-50 border border-blue-100 rounded-sm p-5">
                            <h3 class="text-sm font-bold text-heading mb-3 uppercase tracking-wide">Shipment Tracking</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                @if($order->tracking_carrier)
                                    <div><span class="text-gray-400 text-[11px] uppercase block">Carrier</span><strong class="text-heading">{{ $order->tracking_carrier }}</strong></div>
                                @endif
                                <div><span class="text-gray-400 text-[11px] uppercase block">Tracking Number</span><strong class="text-heading">{{ $order->tracking_number }}</strong></div>
                            </div>
                            @if($order->tracking_url)
                                <a href="{{ $order->tracking_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 mt-4 bg-primary text-white px-5 py-2 rounded-sm text-sm font-bold hover:bg-primary-hover transition">
                                    <i data-lucide="truck" class="w-4 h-4"></i> Track Package
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Summary --}}
                <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap justify-between gap-4 text-sm">
                    <span class="text-body">{{ $order->items->count() }} item{{ $order->items->count() === 1 ? '' : 's' }}</span>
                    <span class="font-bold text-heading">Total: {{ lazy_price_format($order->total, $order) }}</span>
                </div>
            </div>
        @endif
    </div>
</div>
@stop
