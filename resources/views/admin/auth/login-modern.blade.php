<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lazy CMS Modern</title>
    <script src="{{ asset('vendor/cms-dashboard/js/tailwind.min.js') }}"></script>
    <link href="{{ asset('vendor/cms-dashboard/css/inter.css') }}" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; margin: 0; }
        .flex-fallback { display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem; box-sizing: border-box; }
        .modern-card { background: #ffffff; border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; padding: 2rem; box-sizing: border-box; }
        .wp-input { border: 1px solid #d1d5db; border-radius: 8px; padding: 10px 14px; width: 100%; transition: all 0.2s; background: #f9fafb; box-sizing: border-box; }
        .wp-input:focus { border-color: #6366f1; outline: none; background: #fff; }
        .btn-modern { background: #4f46e5; color: white; padding: 12px; border: none; border-radius: 8px; font-weight: 600; transition: background 0.2s; width: 100%; cursor: pointer; }
        .btn-modern:hover { background: #4338ca; }
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            padding: 0px;
            line-height: 0;
            z-index: 10;
        }
    </style>
</head>
<body class="flex-fallback">
    <div class="modern-card">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h1>
            <p class="text-gray-500 text-sm">Please enter your details to sign in</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-3 mb-6 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" class="wp-input" placeholder="name@company.com" required autofocus>
            </div>

            <div class="mb-6">
                <div class="flex justify-between mb-1">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <a href="{{ route('admin.password.request') }}" class="text-xs text-indigo-600 hover:text-indigo-500 font-semibold">Forgot password?</a>
                </div>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" class="wp-input" placeholder="••••••••" required>
                    <button type="button" class="toggle-password" data-target="password">
                        <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center mb-6">
                <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
            </div>

            <button type="submit" class="btn-modern">Sign In</button>
        </form>

        @if(get_cms_option('users_can_register', '0') == '1')
        <p class="text-center mt-8 text-sm text-gray-500">
            Don't have an account? <a href="{{ route('admin.register') }}" class="text-indigo-600 hover:text-indigo-500 font-semibold">Sign up</a>
        </p>
        @endif
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const target = document.getElementById(this.getAttribute('data-target'));
                const type = target.type === 'password' ? 'text' : 'password';
                target.type = type;
                this.style.color = type === 'text' ? '#4f46e5' : '#9ca3af';
            });
        });
    </script>
</body>
</html>
