<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost Password - Lazy CMS</title>
    <script src="{{ asset('vendor/cms-dashboard/js/tailwind.min.js') }}"></script>
    <style>
        body { background: #f0f0f1; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif; margin: 0; }
        .flex-fallback { display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .login-box { width: 320px; margin: 0 auto; }
        .input-fallback { width: 100%; box-sizing: border-box; }
        .btn-fallback { background: #2271b1; color: white; border: none; cursor: pointer; padding: 6px 15px; border-radius: 3px; }
        .alert { background: #fff; border-left: 4px solid #72aee6; box-shadow: 0 1px 1px 0 rgba(0,0,0,.1); margin-bottom: 20px; padding: 12px; font-size: 14px; color: #3c434a; }
    </style>
</head>
<body class="flex-fallback">
    <div class="login-box">
        <div class="text-center mb-8">
            <h1 class="text-[32px] font-bold text-[#3c434a]">Lazy CMS</h1>
        </div>
        
        @if(session('status'))
            <div class="alert">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert border-red-500" style="border-left-color: #d63638;">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white p-6 shadow-sm border border-[#c3c4c7]">
            <p class="text-[14px] text-[#3c434a] mb-6 leading-relaxed">
                Please enter your email address. You will receive a link to create a new password via email.
            </p>

            <form action="{{ route('admin.password.email') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-[14px] text-[#3c434a] mb-1">Email Address</label>
                    <input type="email" name="email" class="input-fallback w-full border border-[#8c8f94] p-2 focus:border-[#2271b1] focus:ring-1 focus:ring-[#2271b1] outline-none" required autofocus>
                </div>
                
                <div class="flex items-center justify-between mt-6">
                    <button type="submit" class="btn-fallback bg-[#2271b1] text-white px-4 py-2 rounded-[3px] text-[13px] font-semibold hover:bg-[#135e96] w-full">Get New Password</button>
                </div>
            </form>
        </div>

        <p class="mt-4 text-[13px] text-[#3c434a]">
            <a href="{{ route('admin.login') }}" class="hover:text-[#2271b1]">Log In</a>
        </p>
    </div>
</body>
</html>
