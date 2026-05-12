<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Lazy CMS Modern</title>
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
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-50 text-indigo-600 rounded-full mb-4">
                <svg style="width:32px;height:32px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Forgot Password?</h1>
            <p class="text-gray-500 text-sm">No worries, we'll send you reset instructions.</p>
        </div>

        @if(session('status'))
            <div class="bg-green-50 border-l-4 border-green-400 p-3 mb-6 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-3 mb-6 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.password.email') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                <input type="email" name="email" class="wp-input" placeholder="Enter your email" required autofocus>
            </div>

            <button type="submit" class="btn-modern">Reset Password</button>
        </form>

        <p class="text-center mt-8 text-sm text-gray-500">
            <a href="{{ route('admin.login') }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-500 font-semibold transition-colors">
                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to log in
            </a>
        </p>
    </div>
</body>
</html>
