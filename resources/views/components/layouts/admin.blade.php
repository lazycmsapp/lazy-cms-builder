<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} &lsaquo; {{ get_cms_option('site_title', 'CMS') }}</title>
    <script src="{{ asset('vendor/cms-dashboard/js/tailwind.min.js') }}"></script>
    <style>
        /* CRITICAL FALLBACKS & CORE STYLES */
        #wpadminbar { position: fixed !important; top: 0; left: 0; right: 0; height: 32px !important; background: #1d2327 !important; z-index: 9999 !important; display: flex !important; align-items: center !important; }
        #adminmenuwrap { position: fixed !important; top: 32px !important; left: 0 !important; bottom: 0 !important; width: 160px !important; background: #1d2327 !important; z-index: 999 !important; overflow-y: auto !important; }
        body { padding-top: 32px !important; padding-left: 160px !important; margin: 0 !important; background: #f0f0f1 !important; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif; transition: padding-left 0.2s; }
        
        svg, .material-symbols-outlined { max-width: 24px; max-height: 24px; }
        #wpadminbar svg { width: 18px; height: 18px; fill: #c3c4c7; }
        .material-symbols-outlined { font-size: 20px !important; }

        .wp-btn-primary { background: #2271b1; color: #fff; border: 1px solid #2271b1; border-radius: 3px; padding: 0 10px; min-height: 30px; font-size: 13px; line-height: 2.15384615; cursor: pointer; transition: all 0.1s; display: inline-flex; align-items: center; }
        .wp-btn-primary:hover { background: #135e96; border-color: #135e96; }
        .wp-btn-secondary { background: #f6f7f7; color: #2271b1; border: 1px solid #2271b1; border-radius: 3px; padding: 0 10px; min-height: 30px; font-size: 13px; line-height: 2.15384615; cursor: pointer; transition: all 0.1s; display: inline-flex; align-items: center;}
        .wp-btn-secondary:hover { background: #f0f0f1; border-color: #0a4b78; color: #0a4b78; }
        .wp-btn-outline { background: #f6f7f7; color: #2271b1; border: 1px solid #2271b1; border-radius: 3px; padding: 2px 8px; font-size: 13px; font-weight: 500; text-decoration: none; transition: .1s; display: inline-block; }
        .wp-btn-outline:hover { background: #f0f0f1; color: #0a4b78; }
        .wp-input { border: 1px solid #8c8f94; border-radius: 3px; box-shadow: 0 0 0 transparent; padding: 0 8px; min-height: 30px; font-size: 14px; background: #fff; }
        .wp-input:focus { border-color: #2271b1; box-shadow: 0 0 0 1px #2271b1; outline: none; }
        .wp-table-header { font-weight: 400; color: #2c3338; font-size: 14px; border-bottom: 1px solid #c3c4c7; padding: 8px 10px; background: #fff;}
        .wp-table-cell { padding: 8px 10px; font-size: 13px; color: #1d2327; border-bottom: 1px solid #c3c4c7; }
        .wp-metabox { background: #fff; border: 1px solid #c3c4c7; border-top: 1px solid #c3c4c7; margin-bottom: 20px; box-shadow: 0 1px 1px rgba(0,0,0,.04); }
        .wp-metabox-header { border-bottom: 1px solid #c3c4c7; padding: 8px 12px; margin: 0; font-size: 14px; font-weight: 600; color: #1d2327; background: #fff; }
        .wp-metabox-content { padding: 12px; }
        
        /* Sidebar Collapsed States */
        body.sidebar-collapsed { padding-left: 36px; }
        body.sidebar-collapsed #adminmenuwrap { width: 36px; overflow: visible; }
        body.sidebar-collapsed .collapse-text, 
        body.sidebar-collapsed .sidebar-item span,
        body.sidebar-collapsed li[class*="uppercase"] { display: none !important; }
        body.sidebar-collapsed .sidebar-item a,
        body.sidebar-collapsed .sidebar-item-link { justify-content: center !important; padding-left: 0 !important; padding-right: 0 !important; }
        body.sidebar-collapsed .sidebar-item div[class*="mr-3"],
        body.sidebar-collapsed .sidebar-item div[class*="mr-2"] { margin-right: 0 !important; }
        body.sidebar-collapsed .sidebar-item div[class*="bg-[#2c3338]"] { display: none !important; }
        body.sidebar-collapsed .sidebar-flyout { display: none !important; }
        
        #adminmenuwrap { transition: width 0.2s; }
        .collapse-icon svg { transition: transform 0.2s; }
        .rotate-180 { transform: rotate(180deg); }
        [x-cloak] { display: none !important; }
        
        /* Toast Notifications */
        .toast-container { position: fixed; top: 40px; right: 20px; z-index: 10000; display: flex; flex-col-reverse: column; gap: 10px; pointer-events: none; }
        .toast-item { pointer-events: auto; min-width: 280px; max-width: 400px; background: #fff; border-left: 4px solid #2271b1; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); padding: 12px 16px; border-radius: 4px; display: flex; align-items: flex-start; gap: 12px; transform: translateX(120%); transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55); opacity: 0; }
        .toast-item.show { transform: translateX(0); opacity: 1; }
        .toast-success { border-left-color: #46b450; }
        .toast-error { border-left-color: #d63638; }
        .toast-info { border-left-color: #2271b1; }
        .toast-warning { border-left-color: #ffb900; }
    </style>
    <script defer src="{{ asset('vendor/cms-dashboard/js/alpine.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('vendor/cms-dashboard/css/material-symbols.css') }}" />
    {!! do_lazy_action('lazy_admin_head') !!}
</head>
<body class="text-[#1d2327] text-[13px] antialiased overflow-x-hidden pt-8">
    
    <!-- WP Admin Bar (Top) -->
    <div id="wpadminbar" class="fixed top-0 left-0 right-0 h-8 bg-[#1d2327] z-50 flex items-center justify-between text-[#c3c4c7] px-2 text-[13px]">
        <div class="flex items-center space-x-4">
            @if(auth()->user()->hasPermission('access_dashboard'))
                <a href="#" class="hover:text-[#72aee6] transition px-2 flex items-center hidden sm:flex">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1.12 16.51C6.96 17.51 4.5 14.33 4.5 12c0-.52.06-1.03.17-1.52l4.81 12.33c-.56-.1-1.1-.21-1.6-.3zm2.24 0l-3.3-8.8 1.48-4.22c3.15-.31 5.92 1.34 7.21 3.96-1.1-2.02-3.1-3.62-5.46-3.8l1.35 3.86 3.1 8.25c-.9 1.15-2.06 2.06-3.38 2.75zm1.5-8.5c-.32 1.25-.97 2.36-1.8 3.25L10 6.64c2.8.52 5 2.68 5.6 5.48zL12 22v-6H8.5l6-16C19.83 4.96 23 8.31 23 12c0 2.87-1.22 5.45-3.17 7.28L14.62 10.01z"/></svg>
                </a>
                <a href="/" target="_blank" class="hover:text-[#72aee6] transition px-2 flex items-center space-x-1">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>CMS Site</span>
                </a>
                @if(auth()->user()->hasPermission('manage_posts') || auth()->user()->hasPermission('manage_pages'))
                    <div class="hover:text-[#72aee6] transition font-semibold px-2 cursor-pointer relative group flex items-center space-x-1">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>New</span>
                    </div>
                @endif
            @endif
        </div>
        <div class="flex items-center space-x-4 pr-1 text-sm relative group" x-data="{ open: false }">
            {!! do_lazy_action('lazy_admin_bar_right_before') !!}
            <button @click="open = !open" class="flex items-center space-x-2 text-[#c3c4c7] group-hover:text-[#72aee6] transition py-1 px-2 focus:outline-none">
                <span>Howdy, <span class="font-semibold">{{ auth()->user()->username ?? auth()->user()->name ?? 'Admin' }}</span></span>
                <img src="https://secure.gravatar.com/avatar/{{ md5(strtolower(trim(optional(auth()->user())->email ?? 'admin@example.com'))) }}?s=26&d=mm&r=g" class="w-6 h-6 rounded-sm ml-1">
            </button>
            <div x-show="open" @click.away="open = false" 
                 class="absolute right-0 top-8 w-48 bg-[#2c3338] border border-[#3c434a] shadow-lg py-1 z-[60] text-[#c3c4c7] hidden group-hover:block">
                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 hover:bg-[#2271b1] hover:text-white transition">Edit Profile</a>
                <div class="border-t border-[#3c434a] my-1"></div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left block px-4 py-2 hover:bg-[#2271b1] hover:text-white transition">Log Out</button>
                </form>
            </div>
        </div>
    </div>

    <!-- WP Admin Menu (Sidebar) -->
    <x-cms-dashboard::admin.sidebar :activeMenu="$activeMenu ?? null" />

    <!-- Main Content -->
    <div class="p-4 sm:p-5">
        {{ $slot }}
    </div>

    <!-- Media Modal Global Inclusion -->
    <x-cms-dashboard::admin.media-modal />

    <!-- Toast Container -->
    <div id="lazy-toast-container" class="toast-container"></div>

    <script>
        window.showToast = function(message, type = 'success', duration = 4000) {
            const container = document.getElementById('lazy-toast-container');
            const toast = document.createElement('div');
            toast.className = `toast-item toast-${type}`;
            
            let icon = 'check_circle';
            if(type === 'error') icon = 'error';
            if(type === 'info') icon = 'info';
            if(type === 'warning') icon = 'warning';

            toast.innerHTML = `
                <span class="material-symbols-outlined text-${type === 'success' ? '[#46b450]' : (type === 'error' ? '[#d63638]' : (type === 'warning' ? '[#ffb900]' : '[#2271b1]'))}">${icon}</span>
                <div class="flex-grow">
                    <p class="text-[13px] font-semibold text-[#1d2327]">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-[#8c8f94] hover:text-[#1d2327]">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                </button>
            `;

            container.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => toast.classList.add('show'), 10);

            // Auto remove
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        };
    </script>

    @stack('scripts')
    {!! do_lazy_action('lazy_admin_footer') !!}
</body>
</html>
