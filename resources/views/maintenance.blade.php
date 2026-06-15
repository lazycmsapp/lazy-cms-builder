<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 32px rgba(0,0,0,.08);
            padding: 56px 48px;
            max-width: 520px;
            width: 100%;
            text-align: center;
        }
        .icon {
            width: 72px;
            height: 72px;
            background: #eff6ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
        }
        .icon svg { width: 36px; height: 36px; color: #3b82f6; }
        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }
        p {
            font-size: 16px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 36px;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fef9c3;
            color: #854d0e;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 99px;
            margin-bottom: 36px;
        }
        .badge svg { width: 14px; height: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/>
                <path d="M12 8v4l3 3"/>
            </svg>
        </div>

        <div class="badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            Scheduled Maintenance
        </div>

        <h1>We'll be back soon</h1>
        <p>{{ $message }}</p>

    </div>
</body>
</html>
