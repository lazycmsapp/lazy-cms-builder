<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Lazy CMS Modern</title>
    <script src="{{ asset('vendor/cms-dashboard/js/tailwind.min.js') }}"></script>
    <link href="{{ asset('vendor/cms-dashboard/css/inter.css') }}" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; margin: 0; }
        .flex-fallback { display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem; box-sizing: border-box; }
        .modern-card { background: #ffffff; border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; padding: 2.5rem; box-sizing: border-box; }
        .wp-input { border: 1px solid #d1d5db; border-radius: 8px; padding: 12px 14px; width: 100%; transition: all 0.2s; background: #f9fafb; box-sizing: border-box; }
        .wp-input:focus { border-color: #6366f1; outline: none; background: #fff; ring: 4px rgba(99, 102, 241, 0.1); }
        .btn-modern { background: #4f46e5; color: white; padding: 14px; border: none; border-radius: 8px; font-weight: 600; transition: all 0.2s; width: 100%; cursor: pointer; }
        .btn-modern:hover { background: #4338ca; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2); }
    </style>
</head>
<body class="flex-fallback">
    <div class="modern-card">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Set New Password</h1>
            <p class="text-gray-500 text-sm">Please choose a strong password for your account.</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-3 mb-6 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.password.update') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                <input type="email" name="email" class="wp-input" placeholder="Confirm your email" value="{{ old('email') }}" required autofocus>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
                <input type="password" name="password" class="wp-input" placeholder="••••••••" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password</label>
                <input type="password" name="password_confirmation" class="wp-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-modern">Update Password</button>
        </form>
    </div>
</body>
</html>
