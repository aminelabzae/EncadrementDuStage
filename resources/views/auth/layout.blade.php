<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name')) — {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-deep:    #f0f6ff;
            --bg-card:    #ffffff;
            --bg-input:   #f8faff;
            --border:     #dde8f8;
            --border-focus: #2563eb;
            --accent:     #2563eb;
            --accent-hover:#1d4ed8;
            --accent-glow: rgba(37,99,235,0.2);
            --text-main:  #1e3a5f;
            --text-muted: #64748b;
            --text-link:  #2563eb;
            --error-bg:   #fef2f2;
            --error-text: #dc2626;
            --success-bg: #f0fdf4;
            --success-text:#16a34a;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg-deep);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background-image:
                radial-gradient(ellipse 80% 60% at 20% 10%, rgba(37,99,235,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 90%, rgba(99,102,241,0.06) 0%, transparent 60%);
        }

        .auth-wrapper {
            width: 100%;
            max-width: 420px;
        }

        /* Brand header */
        .auth-brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-brand .brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px; height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--accent), #6366f1);
            box-shadow: 0 8px 24px var(--accent-glow);
            margin-bottom: 1rem;
        }
        .auth-brand .brand-icon svg { width: 28px; height: 28px; color: #fff; }
        .auth-brand h1 {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--text-main);
            letter-spacing: -0.01em;
        }
        .auth-brand p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        /* Card */
        .auth-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(37,99,235,0.08), 0 1px 3px rgba(37,99,235,0.05);
        }

        .auth-card h2 {
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
            letter-spacing: -0.02em;
            color: var(--text-main);
        }
        .auth-card .subtitle {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 1.75rem;
        }

        /* Form elements */
        .form-group {
            margin-bottom: 1.1rem;
        }
        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 0.45rem;
            letter-spacing: 0.01em;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.7rem 1rem;
            color: var(--text-main);
            font-size: 0.9rem;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .form-group input:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }
        .form-group input.is-invalid {
            border-color: #ef4444;
        }
        .error-msg {
            font-size: 0.775rem;
            color: var(--error-text);
            margin-top: 0.35rem;
        }

        /* Remember row */
        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.4rem;
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.82rem;
            color: var(--text-muted);
            cursor: pointer;
            user-select: none;
        }
        .checkbox-label input[type="checkbox"] {
            accent-color: var(--accent);
            width: 15px; height: 15px;
        }

        /* Button */
        .btn-primary {
            display: block;
            width: 100%;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, var(--accent), #4f46e5);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 16px var(--accent-glow);
            letter-spacing: 0.01em;
        }
        .btn-primary:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px var(--accent-glow);
        }
        .btn-primary:active {
            transform: translateY(0);
        }

        /* Links */
        a.text-link {
            color: var(--text-link);
            text-decoration: none;
            font-size: 0.82rem;
            transition: color 0.15s;
        }
        a.text-link:hover { color: var(--accent-hover); text-decoration: underline; }

        /* Divider */
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.83rem;
            color: var(--text-muted);
        }
        .auth-footer a.text-link { font-size: 0.83rem; }

        /* Alert */
        .alert {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.83rem;
            margin-bottom: 1.25rem;
        }
        .alert-success {
            background: var(--success-bg);
            border: 1px solid #bbf7d0;
            color: var(--success-text);
        }
        .alert-error {
            background: var(--error-bg);
            border: 1px solid #fecaca;
            color: var(--error-text);
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">

        <!-- Brand -->
        <div class="auth-brand">
            <div class="brand-icon">
                <!-- Briefcase icon -->
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                    <line x1="12" y1="12" x2="12" y2="16" stroke-linecap="round"/>
                    <line x1="10" y1="14" x2="14" y2="14" stroke-linecap="round"/>
                </svg>
            </div>
            <h1>Stages &amp; Encadrement</h1>
            <p>Plateforme de gestion des stages</p>
        </div>

        <!-- Card content -->
        @yield('content')

    </div>
</body>
</html>
