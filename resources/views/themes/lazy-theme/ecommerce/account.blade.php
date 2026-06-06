@extends('cms-dashboard::themes.lazy-theme.layouts.app')

@section('title', 'My Account')

@section('content')
@php $primaryColor = get_cms_option('theme_primary_color', '#4f46e5'); @endphp
<div class="bg-gray-50 py-16 min-h-screen">
    <div class="container-custom">

@auth
{{-- ─── Logged-in view ─────────────────────────────────────────────── --}}
@php $user = auth()->user(); @endphp

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
                <form action="{{ route('shop.account.logout') }}" method="POST">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
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
                            <p class="text-body mb-5">No orders match "<strong>{{ request('s') }}</strong>".</p>
                            <a href="{{ url()->current() }}" class="inline-block bg-primary text-white px-6 py-2.5 rounded-sm font-bold hover:bg-primary-hover transition uppercase text-sm">View all orders</a>
                        @else
                            <p class="text-body mb-5">You haven't placed any orders yet.</p>
                            <a href="{{ get_lazy_shop_url() }}" class="inline-block bg-primary text-white px-6 py-2.5 rounded-sm font-bold hover:bg-primary-hover transition uppercase text-sm">Start shopping</a>
                        @endif
                    </div>
                @else
                    @php
                        $statusColors = [
                            'pending'            => 'bg-amber-100 text-amber-700',
                            'processing'         => 'bg-blue-100 text-blue-700',
                            'completed'          => 'bg-emerald-100 text-emerald-700',
                            'cancelled'          => 'bg-rose-100 text-rose-700',
                            'partially-refunded' => 'bg-purple-100 text-purple-700',
                            'refunded'           => 'bg-gray-200 text-gray-600',
                            'on-hold'            => 'bg-amber-100 text-amber-700',
                            'failed'             => 'bg-rose-100 text-rose-700',
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
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ str_replace('-', ' ', $order->status) }}
                                        </span>
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

@else
{{-- ─── Guest / Login form ──────────────────────────────────────────── --}}
<style>
.acc-lf-wrap { position: relative; }
.acc-lf-input {
    border: 1px solid #d1d5db; border-radius: 8px;
    padding: 1.3rem 14px .45rem; width: 100%;
    font-size: .9rem; color: #111827; background: #fff;
    transition: border-color .15s, box-shadow .15s;
    box-sizing: border-box; display: block;
}
.acc-lf-input:focus { outline: none; border-color: {{ $primaryColor }}; box-shadow: 0 0 0 3px {{ $primaryColor }}26; }
.acc-lf-input.has-icon { padding-right: 44px; }
.acc-lf-label {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    transition: top .15s ease, transform .15s ease, color .15s ease, background-color .15s ease;
    pointer-events: none; color: #9ca3af; font-size: .875rem; font-weight: 500; line-height: 1;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: calc(100% - 28px); z-index: 1;
}
.acc-lf-wrap.lf-focused .acc-lf-label,
.acc-lf-wrap.lf-filled  .acc-lf-label {
    top: 0; transform: translateY(-50%) scale(.78); transform-origin: left center;
    padding: 0 3px; background-color: #fff; color: #374151; max-width: none; overflow: visible;
}
.acc-lf-wrap.lf-focused .acc-lf-label { color: {{ $primaryColor }}; }
.acc-toggle-pwd {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer; color: #9ca3af; padding: 0; line-height: 0; z-index: 10;
}
</style>

        <div class="max-w-md mx-auto">

            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background:{{ $primaryColor }}1a">
                    <i data-lucide="user" class="w-8 h-8" style="color:{{ $primaryColor }}"></i>
                </div>
                <h1 class="text-2xl font-bold text-heading">Sign in to your account</h1>
                <p class="text-body mt-2 text-sm">Enter your credentials below to view your orders and account details.</p>
            </div>

            @if($errors->has('account_email'))
                <div class="bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3 rounded-lg mb-5 flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
                    {{ $errors->first('account_email') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <form action="{{ route('shop.account.login') }}" method="POST">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ url()->current() }}">

                    <div style="display:flex;flex-direction:column;gap:1.1rem">

                        {{-- Email --}}
                        <div class="acc-lf-wrap">
                            <input type="email" id="acc_email" name="account_email"
                                   placeholder=" " autocomplete="email"
                                   value="{{ old('account_email') }}"
                                   class="acc-lf-input {{ $errors->has('account_email') ? 'border-rose-400' : '' }}" required>
                            <label class="acc-lf-label" for="acc_email">Email address</label>
                        </div>

                        {{-- Password --}}
                        <div>
                            <div class="acc-lf-wrap">
                                <input type="password" id="acc_password" name="account_password"
                                       placeholder=" " autocomplete="current-password"
                                       class="acc-lf-input has-icon" required>
                                <label class="acc-lf-label" for="acc_password">Password</label>
                                <button type="button" class="acc-toggle-pwd" data-target="acc_password" tabindex="-1">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                            <div style="text-align:right;margin-top:.35rem">
                                <a href="{{ route('admin.password.request') }}" style="font-size:.78rem;font-weight:600;text-decoration:none;color:{{ $primaryColor }}">Forgot password?</a>
                            </div>
                        </div>

                        {{-- Remember me --}}
                        <label style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;color:#374151;cursor:pointer">
                            <input type="checkbox" name="remember" value="1" style="width:15px;height:15px;accent-color:{{ $primaryColor }};border-radius:3px">
                            Keep me signed in
                        </label>

                        <button type="submit"
                                style="background:{{ $primaryColor }};color:#fff;padding:13px;border:none;border-radius:8px;font-weight:600;font-size:.9rem;width:100%;cursor:pointer;transition:opacity .2s,transform .1s"
                                onmouseover="this.style.opacity='.88';this.style.transform='translateY(-1px)'"
                                onmouseout="this.style.opacity='1';this.style.transform='none'">
                            Sign In
                        </button>

                    </div>
                </form>
            </div>

            <p class="text-center text-sm text-body mt-6">
                Don't have an account?
                <a href="{{ route('shop.checkout') }}" style="color:{{ $primaryColor }};font-weight:600;text-decoration:none">Create one at checkout</a>
            </p>

        </div>

@endauth

    </div>
</div>

<script>
(function () {
    // Floating labels
    document.querySelectorAll('.acc-lf-wrap').forEach(function (wrap) {
        var inp = wrap.querySelector('.acc-lf-input');
        if (!inp) return;
        function update() { wrap.classList.toggle('lf-filled', inp.value.trim() !== ''); }
        inp.addEventListener('focus', function () { wrap.classList.add('lf-focused'); });
        inp.addEventListener('blur',  function () { wrap.classList.remove('lf-focused'); update(); });
        inp.addEventListener('input', update);
        update();
    });
    // Password toggle
    document.querySelectorAll('.acc-toggle-pwd').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var inp = document.getElementById(this.dataset.target);
            inp.type = inp.type === 'password' ? 'text' : 'password';
            this.style.color = inp.type === 'text' ? '{{ $primaryColor }}' : '#9ca3af';
        });
    });
})();
</script>
@stop
