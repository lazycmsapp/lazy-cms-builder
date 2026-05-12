<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password &lsaquo; CMS &#8212; WordPress</title>
    <script src="{{ asset('vendor/cms-dashboard/js/tailwind.min.js') }}"></script>
    <style>
        body { background-color: #f0f0f1; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif; }
        .login-box { width: 320px; padding: 26px 24px 34px; font-weight: 400; background: #fff; border: 1px solid #c3c4c7; box-shadow: 0 1px 3px rgba(0,0,0,.04); }
        .wp-label { color: #3c434a; font-size: 14px; margin-bottom: 3px; display: block; }
        .wp-input { border: 1px solid #8c8f94; border-radius: 3px; padding: 0 10px; min-height: 40px; font-size: 20px; width: 100%; margin-bottom: 16px; background: #fff; color: #2c3338; }
        .wp-input:focus { border-color: #2271b1; box-shadow: 0 0 0 1px #2271b1; outline: none; }
        .wp-submit { background: #2271b1; border: 1px solid #2271b1; color: #fff; padding: 0 15px; min-height: 32px; line-height: 2.30769231; font-size: 13px; border-radius: 3px; cursor: pointer; font-weight: 600; width: 100%; display: flex; align-items: center; justify-content: center; }
        .wp-submit:hover { background: #135e96; border-color: #135e96; }
        .wp-logo { margin-bottom: 25px; }
        .alert { background: #fff; border-left: 4px solid #d63638; box-shadow: 0 1px 1px 0 rgba(0,0,0,.1); margin-bottom: 20px; padding: 12px; width: 320px; font-size: 14px; color: #3c434a; }
    </style>
</head>
<body>

    <div class="wp-logo">
        <a href="/" class="flex flex-col items-center text-center">
            <svg class="w-20 h-20 text-[#1d2327]" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12.14,2.09c8.36,0,10.59,4.9,10.59,4.9h0c0.14,0.3,0.02,0.66-0.28,0.8L12.33,12.33c-0.23,0.11-0.5,0.06-0.67-0.12L8,8.5V19 c0,0.55-0.45,1-1,1s-1-0.45-1-1v-11c0-0.55,0.45-1,1-1c0.27,0,0.52,0.11,0.71,0.29l2.79,2.79l8.61-4.08c1.39-0.66,0.3-2.61-0.97-1.92 L9,8.5V5.5c0-0.55,0.45-1,1-1H12.14z M2,12C2,6.48,6.48,2,12,2s10,4.48,10,10s-4.48,10-10,10S2,17.52,2,12z"/>
            </svg>
            <span class="mt-2 text-[32px] font-light text-[#1d2327]">Lazy CMS Admin</span>
        </a>
    </div>

    @if($errors->any())
        <div class="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="login-box">
        <form action="{{ route('admin.password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="mb-4">
                <label for="user_email" class="wp-label">Email Address</label>
                <input type="email" name="email" id="user_email" class="wp-input" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-4">
                <label for="user_pass" class="wp-label">New Password</label>
                <input type="password" name="password" id="user_pass" class="wp-input" required>
            </div>

            <div class="mb-4">
                <label for="user_pass_confirm" class="wp-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="user_pass_confirm" class="wp-input" required>
            </div>

            <button type="submit" class="wp-submit">Reset Password</button>
        </form>
    </div>

</body>
</html>
