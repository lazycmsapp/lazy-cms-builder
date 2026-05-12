<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>The Legend Returns - Lazy CMS Login</title>
    <link href="{{ asset('vendor/cms-dashboard/css/funny-fonts.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary: #f83a3a;
            --secondary: #00d2ff;
            --accent: #ff007a;
            --bg: #0f172a;
            --card-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .funny-card {
            background: var(--card-bg);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            padding: 50px;
            border-radius: 40px;
            border: 1px solid var(--glass-border);
            width: 100%;
            max-width: 420px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            position: relative;
        }

        h1 {
            font-family: 'Jua', sans-serif;
            font-size: 40px;
            background: linear-gradient(to bottom, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0 0 5px 0;
            letter-spacing: -1px;
        }

        .subtitle {
            color: #94a3b8;
            font-size: 15px;
            margin-bottom: 35px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 25px;
            position: relative;
        }

        label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            color: #cbd5e1;
            margin-bottom: 8px;
            letter-spacing: 1.5px;
        }

        input {
            width: 100%;
            padding: 14px 18px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            color: #fff;
            font-size: 15px;
            font-family: inherit;
            box-sizing: border-box;
            transition: all 0.3s;
            outline: none;
        }

        input:focus {
            background: rgba(0, 0, 0, 0.5);
            border-color: var(--secondary);
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 20px;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.5);
            padding: 0px;
            line-height: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            width: 50px;
        }

        .btn-funny-area {
            height: 100px;
            position: relative;
            margin-top: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #login-btn {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white;
            border: none;
            padding: 14px 45px;
            font-size: 19px;
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            border-radius: 20px;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(248, 58, 58, 0.4);
            white-space: nowrap;
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), background 0.3s;
            position: relative;
            z-index: 100;
            will-change: transform;
        }

        .footer-text {
            margin-top: 35px;
            font-size: 14px;
            color: #94a3b8;
        }

        .footer-text a { color: var(--secondary); text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
    <div class="funny-card">
        <h1>WELCOME BACK</h1>
        <p class="subtitle">Securely enter your legendary portal 🌌</p>

        <form action="{{ route('admin.login') }}" method="POST" id="funny-form">
            @csrf
            
            <div class="form-group">
                <label>Admin Email</label>
                <input type="email" id="email" name="email" placeholder="legend@lazycms.com" required autocomplete="off">
            </div>

            <div class="form-group">
                <label>Vault Access Key</label>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" placeholder="••••••••" required style="padding-right: 55px;">
                    <button type="button" class="toggle-password" data-target="password">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
            </div>

            <div class="btn-funny-area">
                <button type="submit" id="login-btn">Unlock Vault ✨</button>
            </div>
        </form>

        @if(get_cms_option('users_can_register', '0') == '1')
        <div class="footer-text">
            Not a legend yet? <a href="{{ route('admin.register') }}">Join Us</a>
        </div>
        @endif

        <div class="footer-text" style="margin-top: 15px;">
            Forgot your access key? <a href="{{ route('admin.password.request') }}">Recover Here</a>
        </div>
    </div>

    <script>
        const btn = document.getElementById('login-btn');
        const emailInput = document.getElementById('email');
        const pwdInput = document.getElementById('password');
        
        let isValidCreds = false;

        async function verify() {
            const email = emailInput.value;
            const password = pwdInput.value;
            if (email.length < 5 || password.length < 3) { isValidCreds = false; return; }
            try {
                const response = await fetch("{{ route('admin.login.check') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ email, password })
                });
                const data = await response.json();
                isValidCreds = data.valid;
                if (isValidCreds) resetBtn();
            } catch (err) { }
        }

        function resetBtn() {
            btn.style.transform = 'translate(0, 0)';
            btn.innerText = "Unlock Vault ✨";
            btn.style.background = "linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%)";
        }

        emailInput.addEventListener('input', () => { isValidCreds = false; verify(); });
        pwdInput.addEventListener('input', () => { isValidCreds = false; verify(); });

        document.addEventListener('mousemove', (e) => {
            if (isValidCreds) return;
            const rect = btn.getBoundingClientRect();
            const btnX = rect.left + rect.width / 2;
            const btnY = rect.top + rect.height / 2;
            const dist = Math.sqrt(Math.pow(e.clientX - btnX, 2) + Math.pow(e.clientY - btnY, 2));

            if (dist < 110) {
                const angle = Math.atan2(e.clientY - btnY, e.clientX - btnX) + Math.PI;
                const moveDist = 150;
                let targetX = Math.cos(angle) * moveDist;
                let targetY = Math.sin(angle) * moveDist;
                btn.style.transform = `translate(${targetX}px, ${targetY}px)`;
                btn.innerText = "Don't Cheat! 😜";
                btn.style.background = "#fbbf24";
            }
        });

        document.querySelectorAll('.toggle-password').forEach(b => {
             b.addEventListener('click', function() {
                const target = document.getElementById(this.getAttribute('data-target'));
                const type = target.type === 'password' ? 'text' : 'password';
                target.type = type;
                this.style.color = type === 'text' ? 'var(--secondary)' : 'rgba(255, 255, 255, 0.5)';
            });
        });
    </script>
</body>
</html>
