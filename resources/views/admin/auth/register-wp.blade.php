<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form &lsaquo; CMS &#8212; WordPress</title>
    <script src="{{ asset('vendor/cms-dashboard/js/tailwind.min.js') }}"></script>
    <style>
        body { background-color: #f0f0f1; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif; }
        .login-box { width: 320px; padding: 26px 24px 34px; font-weight: 400; background: #fff; border: 1px solid #c3c4c7; box-shadow: 0 1px 3px rgba(0,0,0,.04); }
        .wp-label { color: #3c434a; font-size: 14px; margin-bottom: 3px; display: block; }
        .wp-input { border: 1px solid #8c8f94; border-radius: 3px; padding: 0 10px; min-height: 40px; font-size: 20px; width: 100%; margin-bottom: 16px; background: #fff; color: #2c3338; }
        .wp-input:focus { border-color: #2271b1; box-shadow: 0 0 0 1px #2271b1; outline: none; }
        .wp-submit { background: #2271b1; border: 1px solid #2271b1; color: #fff; padding: 0 15px; min-height: 32px; line-height: 2.30769231; font-size: 13px; border-radius: 3px; cursor: pointer; font-weight: 600; width: 100%; display: flex; align-items: center; justify-content: center; }
        .wp-submit:hover { background: #135e96; border-color: #135e96; }
        .login-links { margin-top: 20px; width: 320px; font-size: 13px; color: #72777c; }
        .login-links a { color: #2271b1; text-decoration: none; }
        .login-links a:hover { color: #135e96; }
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
            <span class="mt-2 text-[32px] font-light text-[#1d2327]">Lazy CMS Registration</span>
        </a>
    </div>

    @if($errors->any())
        <div class="alert">
            <ul class="list-disc ml-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="login-box">
        <form action="{{ route('admin.register') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="user_name" class="wp-label">Full Name</label>
                <input type="text" name="name" id="user_name" class="wp-input" value="{{ old('name') }}" required autofocus>
            </div>
            <div class="mb-4">
                <label for="user_email" class="wp-label">Email Address</label>
                <input type="email" name="email" id="user_email" class="wp-input" value="{{ old('email') }}" required>
            </div>
            <div class="mb-4">
                <div class="flex justify-between items-center mb-1">
                    <label for="password" class="wp-label">Password</label>
                    <span id="strength-text" class="text-[10px] font-bold uppercase"></span>
                </div>
                <div style="position: relative;">
                    <input type="password" name="password" id="password" class="wp-input" required>
                    <button type="button" class="toggle-password" data-target="password" style="position: absolute; right: 10px; top: 8px; background: none; border: none; cursor: pointer; color: #646970;">
                        <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                <div style="height: 3px; background: #eee; margin-top: 5px; border-radius: 2px; overflow: hidden;"><div id="strength-bar" style="height: 100%; width: 0; transition: width 0.3s;"></div></div>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="wp-label">Confirm Password</label>
                <div style="position: relative;">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="wp-input" required>
                    <button type="button" class="toggle-password" data-target="password_confirmation" style="position: absolute; right: 10px; top: 8px; background: none; border: none; cursor: pointer; color: #646970;">
                        <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                <div id="match-feedback" style="font-size: 11px; font-weight: 600; margin-top: 2px; min-height: 15px;"></div>
            </div>

            <button type="submit" class="wp-submit w-full">Register</button>
        </form>
    </div>

    <div class="login-links flex justify-center"><a href="{{ route('admin.login') }}">Already have an account? Log in</a></div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const target = document.getElementById(this.getAttribute('data-target'));
                const type = target.type === 'password' ? 'text' : 'password';
                target.type = type;
                this.style.color = type === 'text' ? '#2271b1' : '#646970';
            });
        });

        const pwd = document.getElementById('password');
        const confirmPwd = document.getElementById('password_confirmation');
        const bar = document.getElementById('strength-bar');
        const text = document.getElementById('strength-text');
        const matchFeedback = document.getElementById('match-feedback');

        function validateMatch() {
            if (confirmPwd.value.length > 0) {
                if (pwd.value !== confirmPwd.value) { matchFeedback.innerText = "Password didn't match"; matchFeedback.style.color = "#d63638"; }
                else { matchFeedback.innerText = "Password matched"; matchFeedback.style.color = "#00a32a"; }
            } else { matchFeedback.innerText = ""; }
        }

        pwd.addEventListener('input', function() {
            validateMatch();
            let score = 0;
            const val = this.value;
            if (val.length > 6) score++;
            if (val.length > 10) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (val.length === 0) { bar.style.width = '0%'; text.innerText = ''; }
            else if (score <= 2) { bar.style.width = '33%'; bar.style.backgroundColor = '#ef4444'; text.innerText = 'LOW (WEAK)'; text.style.color = '#ef4444'; }
            else if (score <= 4) { bar.style.width = '66%'; bar.style.backgroundColor = '#f59e0b'; text.innerText = 'MEDIUM'; text.style.color = '#f59e0b'; }
            else { bar.style.width = '100%'; bar.style.backgroundColor = '#10b981'; text.innerText = 'STRONG'; text.style.color = '#10b981'; }
        });
        confirmPwd.addEventListener('input', validateMatch);
    </script>
</body>
</html>
