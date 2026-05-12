<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>The Legend's Gate - Lazy CMS Premium</title>
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
            padding: 45px;
            border-radius: 40px;
            border: 1px solid var(--glass-border);
            width: 100%;
            max-width: 480px;
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
            margin: 0;
            letter-spacing: -1px;
        }

        .subtitle { color: #94a3b8; font-size: 15px; margin-bottom: 25px; }
        .form-group { text-align: left; margin-bottom: 20px; position: relative; }
        label { display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #cbd5e1; margin-bottom: 6px; letter-spacing: 1px; }
        
        input {
            width: 100%;
            padding: 14px 18px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            color: #fff;
            font-size: 14px;
            font-family: inherit;
            box-sizing: border-box;
            transition: all 0.3s;
            outline: none;
        }

        input:focus { background: rgba(0, 0, 0, 0.5); border-color: var(--secondary); }
        .toggle-password { position: absolute; right: 12px; top: 20px; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: rgba(255, 255, 255, 0.4); padding: 0px; line-height: 0; z-index: 10; display: flex; align-items: center; justify-content: flex-end; width: 50px; }
        .feedback-text { font-size: 10px; font-weight: 800; margin-top: 5px; min-height: 12px; }
        .btn-funny-area { height: 90px; position: relative; margin-top: 20px; display: flex; align-items: center; justify-content: center; }

        #register-btn {
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
            position: relative;
            white-space: nowrap;
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), background 0.3s;
            will-change: transform;
            z-index: 100;
        }

        #email-feedback { position: absolute; right: 15px; top: 38px; font-size: 10px; font-weight: 900; }
        #strength-text { font-size: 10px; font-weight: 800; color: var(--secondary); }
        .strength-bar-container { height: 4px; background: rgba(255,255,255,0.05); border-radius: 10px; margin-top: 8px; overflow: hidden; }
        #strength-bar { height: 100%; width: 0; transition: all 0.4s ease-out; }
    </style>
</head>
<body>
    <div class="funny-card" id="main-card">
        <h1>JOIN THE ELITE</h1>
        <p class="subtitle">Secure your legendary portal today 🌌</p>

        <form action="{{ route('admin.register') }}" method="POST" id="funny-form">
            @csrf
            <div class="form-group">
                <label>Nickname</label>
                <input type="text" name="name" placeholder="E.g. Tony Stark" required autocomplete="off">
            </div>

            <div class="form-group">
                <label>Email ID</label>
                <input type="email" id="email" name="email" placeholder="legend@lazycms.com" autocomplete="off">
                <div id="email-feedback"></div>
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between;">
                    <label>Password</label>
                    <span id="strength-text"></span>
                </div>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" placeholder="••••••••" required style="padding-right: 55px;">
                    <button type="button" class="toggle-password" data-target="password">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                <div class="strength-bar-container"><div id="strength-bar"></div></div>
            </div>

            <div class="form-group">
                <label>Verify Password</label>
                <div style="position: relative;">
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required style="padding-right: 55px;">
                    <button type="button" class="toggle-password" data-target="password_confirmation">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                <div id="match-feedback" class="feedback-text"></div>
            </div>

            <div class="btn-funny-area">
                <button type="submit" id="register-btn">Create Account ✨</button>
            </div>
        </form>

        <div class="subtitle" style="margin-top: 25px; font-size: 14px;">
            Legend already? <a href="{{ route('admin.login') }}" style="color: var(--secondary); text-decoration: none; font-weight: 700;">Sign In</a>
        </div>
    </div>

    <script>
        const btn = document.getElementById('register-btn');
        const emailInput = document.getElementById('email');
        const pwd = document.getElementById('password');
        const confirmPwd = document.getElementById('password_confirmation');
        const emailFeedback = document.getElementById('email-feedback');
        const matchFeedback = document.getElementById('match-feedback');
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');

        let emailStatus = 'invalid'; 
        let passwordsMatch = false;

        async function checkEmail() {
            const email = emailInput.value;
            if (email.length < 5) return;
            try {
                const response = await fetch("{{ route('admin.email.check') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, body: JSON.stringify({ email }) });
                const data = await response.json();
                if (data.exists) { emailStatus = 'exists'; emailFeedback.innerText = "ALREADY TAKEN! 🕵️‍♂️"; emailFeedback.style.color = "#f87171"; }
                else { emailStatus = 'available'; emailFeedback.innerText = "AVAILABLE! ✅"; emailFeedback.style.color = "#34d399"; }
            } catch (err) { }
        }

        function checkMatch() {
            const val = pwd.value;
            let score = 0;
            if (val.length > 6) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[A-Z]/.test(val)) score++;
            if (val.length === 0) { strengthBar.style.width = '0%'; strengthText.innerText = ''; }
            else if (score <= 1) { strengthBar.style.width = '33%'; strengthBar.style.background = '#f87171'; strengthText.innerText = 'WEAK 🍼'; }
            else if (score === 2) { strengthBar.style.width = '66%'; strengthBar.style.background = '#fbbf24'; strengthText.innerText = 'GOOD 🎖️'; }
            else { strengthBar.style.width = '100%'; strengthBar.style.background = '#34d399'; strengthText.innerText = 'ELITE! 🔥'; }
            if (confirmPwd.value.length > 0) {
                 if (pwd.value !== confirmPwd.value) { passwordsMatch = false; matchFeedback.innerText = "NOT MATCHING! ❌"; matchFeedback.style.color = "#f87171"; }
                 else { passwordsMatch = true; matchFeedback.innerText = "MATCH FOUND! 🚀"; matchFeedback.style.color = "#34d399"; }
            }
        }

        emailInput.addEventListener('input', () => { emailStatus = 'invalid'; checkEmail(); });
        pwd.addEventListener('input', () => { passwordsMatch = false; checkMatch(); });
        confirmPwd.addEventListener('input', () => { passwordsMatch = false; checkMatch(); });

        document.addEventListener('mousemove', (e) => {
            if (emailStatus === 'available' && passwordsMatch && pwd.value.length >= 4) {
                 btn.style.transform = 'translate(0, 0)';
                 btn.innerText = "Create Account ✨";
                 btn.style.background = "linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%)";
                 return;
            }
            const rect = btn.getBoundingClientRect();
            const btnX = rect.left + rect.width / 2;
            const btnY = rect.top + rect.height / 2;
            const dist = Math.sqrt(Math.pow(e.clientX - btnX, 2) + Math.pow(e.clientY - btnY, 2));

            if (dist < 110) {
                const angle = Math.atan2(e.clientY - btnY, e.clientX - btnX) + Math.PI;
                const moveDist = 160;
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
                this.style.color = type === 'text' ? 'var(--secondary)' : 'rgba(255, 255, 255, 0.4)';
            });
        });
    </script>
</body>
</html>
