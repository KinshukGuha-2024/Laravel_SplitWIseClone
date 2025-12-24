<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset your password</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f172a;
            --panel: #0b1220;
            --muted: #94a3b8;
            --text: #e2e8f0;
            --accent: #22d3ee;
            --accent-strong: #0ea5e9;
            --error: #f87171;
            --success: #4ade80;
            --border: #1e293b;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Space Grotesk', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at 20% 20%, #1f2937 0, rgba(31, 41, 55, 0) 30%),
                        radial-gradient(circle at 80% 0%, #0ea5e9 0, rgba(14, 165, 233, 0) 30%),
                        radial-gradient(circle at 80% 80%, #22c55e 0, rgba(34, 197, 94, 0) 28%),
                        var(--bg);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .card {
            width: min(460px, 100%);
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.02), rgba(255, 255, 255, 0.06));
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            box-shadow: 0 20px 80px rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(6px);
            padding: 1.75rem;
            position: relative;
            overflow: hidden;
        }

        .card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.04);
            pointer-events: none;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.3rem 0.75rem;
            border-radius: 999px;
            background: rgba(34, 211, 238, 0.12);
            color: var(--accent);
            font-size: 0.8rem;
            letter-spacing: 0.04em;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        h1 {
            font-size: 1.7rem;
            margin: 0 0 0.35rem;
            letter-spacing: -0.02em;
        }

        .lead {
            margin: 0 0 1.5rem;
            color: var(--muted);
            line-height: 1.5;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.35rem;
        }

        .field {
            position: relative;
        }

        .input {
            width: 100%;
            padding: 0.9rem 1rem;
            padding-right: 3.5rem;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.02);
            color: var(--text);
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input:focus {
            outline: none;
            border-color: rgba(34, 211, 238, 0.8);
            box-shadow: 0 0 0 4px rgba(34, 211, 238, 0.15);
        }

        .toggle {
            position: absolute;
            right: 0.65rem;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            color: var(--muted);
            border: none;
            cursor: pointer;
            font-weight: 600;
            padding: 0.35rem 0.55rem;
            border-radius: 8px;
            transition: color 0.2s, background-color 0.2s;
        }

        .toggle:hover {
            background: rgba(255, 255, 255, 0.06);
            color: var(--text);
        }

        .helper {
            margin-top: 0.25rem;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .helper.error {
            color: var(--error);
        }

        .notice {
            border-radius: 12px;
            padding: 0.9rem 1rem;
            font-weight: 600;
            display: flex;
            gap: 0.5rem;
            align-items: center;
            border: 1px solid transparent;
            line-height: 1.4;
        }

        .notice.error {
            background: rgba(248, 113, 113, 0.14);
            color: #fecdd3;
            border-color: rgba(248, 113, 113, 0.3);
        }

        .notice.success {
            background: rgba(74, 222, 128, 0.12);
            color: #bbf7d0;
            border-color: rgba(74, 222, 128, 0.3);
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 0.5rem;
        }

        .btn {
            appearance: none;
            border: none;
            cursor: pointer;
            padding: 0.95rem 1.1rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            transition: transform 0.15s ease, box-shadow 0.2s ease, background 0.2s ease;
            width: 100%;
            color: #0b1220;
            background: linear-gradient(120deg, var(--accent), var(--accent-strong));
            box-shadow: 0 15px 40px rgba(34, 211, 238, 0.25);
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 45px rgba(14, 165, 233, 0.28);
        }

        .btn:disabled {
            cursor: not-allowed;
            opacity: 0.6;
            transform: none;
            box-shadow: none;
        }

        .meta {
            margin-top: 1rem;
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.7rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.9rem;
        }

        ul {
            margin: 0.25rem 0 0;
            padding-left: 1.2rem;
        }

        li {
            margin-bottom: 0.2rem;
        }

        @media (max-width: 520px) {
            body { padding: 1.25rem 0.9rem; }
            .card { padding: 1.35rem; border-radius: 16px; }
        }
    </style>
</head>
<body>
<main class="card">
    <div class="eyebrow">
        <span>Account Security</span>
    </div>
    <h1>Set a new password</h1>
    <p class="lead">Choose a strong password to secure your account. Make sure it is unique and not used anywhere else.</p>

    @if (session('status'))
        <div class="notice success" role="status">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="notice error" role="alert">
            <div>We found a few issues:</div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="reset-form" method="POST" action="/auth/reset-password" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        <div>
            <label for="password">New password</label>
            <div class="field">
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="input"
                    minlength="8"
                    autocomplete="new-password"
                    required
                    placeholder="Enter a strong password">
                <button class="toggle" type="button" data-toggle="password">Show</button>
            </div>
            <div class="helper">At least 7 characters, with a mix of numbers and letters.</div>
        </div>

        <div>
            <label for="password_confirmation">Confirm password</label>
            <div class="field">
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="input"
                    minlength="8"
                    autocomplete="new-password"
                    required
                    placeholder="Re-enter your password">
                <button class="toggle" type="button" data-toggle="password_confirmation">Show</button>
            </div>
            <div id="match-helper" class="helper">Must match the password above.</div>
        </div>

        <div class="actions">
            <button id="submit-btn" class="btn" type="submit">Update password</button>
        </div>
    </form>
</main>

<script>
    const toggles = document.querySelectorAll('[data-toggle]');
    toggles.forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-toggle');
            const input = document.getElementById(targetId);
            if (!input) return;
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.textContent = isHidden ? 'Hide' : 'Show';
            input.focus();
        });
    });

    const form = document.getElementById('reset-form');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const helper = document.getElementById('match-helper');
    const submitBtn = document.getElementById('submit-btn');

    const syncState = () => {
        const mismatch = confirmInput.value.length > 0 && passwordInput.value !== confirmInput.value;
        helper.classList.toggle('error', mismatch);
        helper.textContent = mismatch ? 'Passwords do not match yet.' : 'Must match the password above.';
        submitBtn.disabled = mismatch || passwordInput.value.length < 7;
    };

    passwordInput.addEventListener('input', syncState);
    confirmInput.addEventListener('input', syncState);
    syncState();

    form.addEventListener('submit', () => submitBtn.disabled = true);
</script>
</body>
</html>
