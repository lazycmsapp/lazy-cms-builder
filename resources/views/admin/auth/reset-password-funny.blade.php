<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magic Password Reset - Lazy CMS Funny</title>
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
        }

        h1 {
            font-family: 'Jua', sans-serif;
            font-size: 36px;
            background: linear-gradient(to bottom, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0 0 10px 0;
            letter-spacing: -1px;
        }

        .subtitle {
            color: #94a3b8;
            font-size: 15px;
            margin-bottom: 35px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
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

        #submit-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 14px 30px;
            font-size: 17px;
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            border-radius: 20px;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
            width: 100%;
            transition: transform 0.3s;
            margin-top: 10px;
        }

        #submit-btn:hover {
            transform: scale(1.02);
        }

        .alert {
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 25px;
            font-size: 14px;
            background: rgba(248, 58, 58, 0.1);
            border: 1px solid rgba(248, 58, 58, 0.2);
            color: #f87171;
        }
    </style>
</head>
<body>
    <div class="funny-card">
        <h1>NEW MAGIC KEY</h1>
        <p class="subtitle">Set your legendary new access key! 🗝️✨</p>

        @if($errors->any())
            <div class="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group">
                <label>Confirm Email</label>
                <input type="email" name="email" placeholder="legend@lazycms.com" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label>New Secret Key</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label>Repeat Secret Key</label>
                <input type="password" name="password_confirmation" placeholder="••••••••" required>
            </div>

            <button type="submit" id="submit-btn">Update Magic Key 🚀</button>
        </form>
    </div>
</body>
</html>
