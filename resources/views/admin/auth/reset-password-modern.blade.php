<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — {{ get_cms_option('site_title', 'Lazy CMS') }}</title>
    <script src="{{ asset('vendor/cms-dashboard/js/tailwind.min.js') }}"></script>
    <link href="{{ asset('vendor/cms-dashboard/css/inter.css') }}" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; margin: 0; }
        .page-wrap { display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem; box-sizing: border-box; }
        .auth-card { background: #fff; border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0,0,0,.1), 0 8px 10px -6px rgba(0,0,0,.08); width: 100%; max-width: 400px; padding: 2.25rem; box-sizing: border-box; }

        .lf-field-wrap { position: relative; }
        .lf-input {
            border: 1px solid #d1d5db; border-radius: 8px;
            padding: 1.3rem 14px .45rem; width: 100%;
            font-size: .9rem; color: #111827; background: #fff;
            transition: border-color .15s, box-shadow .15s;
            box-sizing: border-box; display: block;
        }
        .lf-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
        .lf-input.has-right-icon { padding-right: 44px; }
        .lf-float-label {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            transition: top .15s ease, transform .15s ease, color .15s ease, background-color .15s ease;
            pointer-events: none; color: #9ca3af; font-size: .875rem; font-weight: 500; line-height: 1;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: calc(100% - 28px); z-index: 1;
        }
        .lf-field-wrap.lf-focused .lf-float-label,
        .lf-field-wrap.lf-filled  .lf-float-label {
            top: 0; transform: translateY(-50%) scale(.78); transform-origin: left center;
            padding: 0 3px; background-color: #fff; color: #374151; max-width: none; overflow: visible;
        }
        .lf-field-wrap.lf-focused .lf-float-label { color: #6366f1; }

        .toggle-password {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; color: #9ca3af; padding: 0; line-height: 0; z-index: 10;
        }
        .btn-submit {
            background: #4f46e5; color: #fff; padding: 13px; border: none; border-radius: 8px;
            font-weight: 600; font-size: .9rem; width: 100%; cursor: pointer;
            transition: background .2s, transform .1s, box-shadow .2s;
        }
        .btn-submit:hover { background: #4338ca; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(79,70,229,.25); }
    </style>
</head>
<body class="page-wrap">
    <div class="auth-card">
        <div style="text-align:center;margin-bottom:1.75rem">
            @if(get_cms_option('theme_site_logo'))
                <img src="{{ get_cms_option('theme_site_logo') }}" alt="{{ get_cms_option('site_title') ?: 'Lazy CMS Builder' }}" style="height:44px;width:160px;object-fit:contain;display:inline-block">
            @else
                <span style="font-size:1.25rem;font-weight:800;color:#111827;letter-spacing:-.02em">{{ get_cms_option('site_title') ?: 'Lazy CMS Builder' }}</span>
            @endif
        </div>

        <div class="text-center mb-7">
            <h1 style="font-size:1.5rem;font-weight:700;color:#111827;margin:0 0 .4rem">Set new password</h1>
            <p style="color:#6b7280;font-size:.875rem;margin:0">Choose a strong password for your account.</p>
        </div>

        @if($errors->any())
            <div style="background:#fef2f2;border-left:3px solid #f87171;padding:.75rem 1rem;border-radius:6px;margin-bottom:1.25rem;font-size:.85rem;color:#b91c1c">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.password.update') }}" method="POST" style="display:flex;flex-direction:column;gap:1.1rem">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email --}}
            <div class="lf-field-wrap">
                <input type="email" name="email" id="a_email" placeholder=" " class="lf-input" required autofocus value="{{ old('email', $email ?? '') }}">
                <label class="lf-float-label" for="a_email">Email address</label>
            </div>

            {{-- New Password --}}
            <div class="lf-field-wrap">
                <input type="password" name="password" id="a_password" placeholder=" " class="lf-input has-right-icon" required>
                <label class="lf-float-label" for="a_password">New password</label>
                <button type="button" class="toggle-password" data-target="a_password" tabindex="-1">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
            </div>

            {{-- Confirm Password --}}
            <div>
                <div class="lf-field-wrap">
                    <input type="password" name="password_confirmation" id="a_password2" placeholder=" " class="lf-input has-right-icon" required>
                    <label class="lf-float-label" for="a_password2">Confirm new password</label>
                    <button type="button" class="toggle-password" data-target="a_password2" tabindex="-1">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                <div style="font-size:.78rem;font-weight:600;min-height:16px;margin-top:.35rem" id="match-msg"></div>
            </div>

            <button type="submit" class="btn-submit">Update Password</button>
        </form>
    </div>

    <script>
    (function () {
        // floating labels
        document.querySelectorAll('.lf-field-wrap').forEach(function (wrap) {
            var inp = wrap.querySelector('.lf-input');
            if (!inp) return;
            function update() { wrap.classList.toggle('lf-filled', inp.value.trim() !== ''); }
            inp.addEventListener('focus',  function () { wrap.classList.add('lf-focused'); });
            inp.addEventListener('blur',   function () { wrap.classList.remove('lf-focused'); update(); });
            inp.addEventListener('input',  update);
            update();
        });
        // password toggle
        document.querySelectorAll('.toggle-password').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var inp = document.getElementById(this.dataset.target);
                inp.type = inp.type === 'password' ? 'text' : 'password';
                this.style.color = inp.type === 'text' ? '#6366f1' : '#9ca3af';
            });
        });
        // match check
        var pwd  = document.getElementById('a_password');
        var pwd2 = document.getElementById('a_password2');
        var msg  = document.getElementById('match-msg');
        function checkMatch() {
            if (!pwd2.value.length) { msg.textContent = ''; return; }
            if (pwd.value === pwd2.value) { msg.textContent = 'Passwords match'; msg.style.color = '#10b981'; }
            else { msg.textContent = 'Passwords do not match'; msg.style.color = '#ef4444'; }
        }
        pwd.addEventListener('input', checkMatch);
        pwd2.addEventListener('input', checkMatch);
    })();
    </script>
</body>
</html>
