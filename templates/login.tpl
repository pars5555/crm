<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#111a2b">
    <title>CRM &middot; Sign in</title>
    <link rel="icon" type="image/svg+xml" href="{$SITE_PATH}/img/favicon.svg">
    {literal}
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --ink: #1b2430; --muted: #6a7686; --line: #d9e0ea; --brand: #2f6fed; --brand-600: #2459cc;
            --font: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Noto Sans Armenian", sans-serif;
        }
        body {
            font-family: var(--font);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background:
                radial-gradient(900px 500px at 15% -10%, rgba(47, 111, 237, .22), transparent 60%),
                radial-gradient(700px 480px at 100% 110%, rgba(122, 167, 255, .25), transparent 60%),
                #eef2f8;
            -webkit-font-smoothing: antialiased;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            background: #fff;
            border-radius: 16px;
            padding: 36px 34px 30px;
            box-shadow: 0 1px 2px rgba(16, 24, 40, .06), 0 20px 50px rgba(16, 24, 40, .12);
        }
        .brand { display: flex; align-items: center; gap: 12px; margin-bottom: 6px; }
        .brand-mark { width: 40px; height: 40px; border-radius: 12px; flex: none; }
        .brand-name { font-size: 22px; font-weight: 700; letter-spacing: .2px; }
        .subtitle { color: var(--muted); font-size: 14px; margin-bottom: 24px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 6px; font-size: 13px; font-weight: 600; }
        .field { position: relative; }
        .field input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--line);
            border-radius: 10px;
            font-size: 15px;
            font-family: var(--font);
            color: var(--ink);
            background: #fff;
            outline: none;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .field input:focus { border-color: var(--brand); box-shadow: 0 0 0 4px rgba(47, 111, 237, .14); }
        .field input.has-toggle { padding-right: 64px; }
        .toggle {
            position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
            border: 0; background: transparent; color: var(--brand); font-size: 12px; font-weight: 700;
            padding: 6px 8px; border-radius: 6px; cursor: pointer; font-family: var(--font);
        }
        .toggle:hover { background: #eaf1ff; }
        .login-btn {
            width: 100%;
            margin-top: 6px;
            padding: 13px;
            border: 0;
            border-radius: 10px;
            background: var(--brand);
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            font-family: var(--font);
            cursor: pointer;
            transition: background .15s ease, box-shadow .15s ease;
        }
        .login-btn:hover { background: var(--brand-600); box-shadow: 0 6px 18px rgba(47, 111, 237, .35); }
        .login-btn:disabled { opacity: .7; cursor: wait; }
        .error {
            margin-bottom: 18px;
            padding: 11px 14px;
            border-radius: 10px;
            background: #fef2f2;
            border: 1px solid #f5aaaa;
            color: #7f1d1d;
            font-size: 14px;
        }
        .foot { margin-top: 22px; text-align: center; color: #9aa5b5; font-size: 12px; }
        @media (max-width: 440px) { .login-card { padding: 28px 22px 24px; } }
    </style>
    {/literal}
</head>
<body>
    <div class="login-card">
        <div style="display: flex; justify-content: flex-end; gap: 8px; font-size: 13px; margin-bottom: 8px;">
            <a href="{$SITE_PATH}/language/en" style="color: {if ($ns.currentLang|default:'en') == 'en'}var(--brand){else}var(--muted){/if}; font-weight: {if ($ns.currentLang|default:'en') == 'en'}700{else}400{/if}; text-decoration: none;">en</a>
            <span style="color: var(--line)">|</span>
            <a href="{$SITE_PATH}/language/am" style="color: {if ($ns.currentLang|default:'en') == 'am'}var(--brand){else}var(--muted){/if}; font-weight: {if ($ns.currentLang|default:'en') == 'am'}700{else}400{/if}; text-decoration: none;">am</a>
            <span style="color: var(--line)">|</span>
            <a href="{$SITE_PATH}/language/ru" style="color: {if ($ns.currentLang|default:'en') == 'ru'}var(--brand){else}var(--muted){/if}; font-weight: {if ($ns.currentLang|default:'en') == 'ru'}700{else}400{/if}; text-decoration: none;">ru</a>
        </div>
        <div class="brand">
            <img class="brand-mark" src="{$SITE_PATH}/img/favicon.svg" alt="">
            <span class="brand-name">CRM</span>
        </div>
        <p class="subtitle">{$ns.lm->getPhrase('Sign in to continue')|default:'Sign in to continue'}</p>
        {if !empty($ns.loginError)}
            <div class="error" role="alert">{$ns.lm->getPhrase('Wrong username or password. Please try again.')|default:'Wrong username or password. Please try again.'}</div>
        {/if}
        <form action="{$SITE_PATH}/dyn/login/do_login" method="POST" id="loginForm">
            <div class="form-group">
                <label for="username">{$ns.lm->getPhrase('Username')|default:'Username'}</label>
                <div class="field">
                    <input type="text" id="username" name="username" placeholder="{$ns.lm->getPhrase('Enter username')|default:'Enter username'}" autocomplete="username" autocapitalize="none" spellcheck="false" autofocus required />
                </div>
            </div>
            <div class="form-group">
                <label for="password">{$ns.lm->getPhrase('Password')|default:'Password'}</label>
                <div class="field">
                    <input type="password" id="password" name="password" class="has-toggle" placeholder="{$ns.lm->getPhrase('Enter password')|default:'Enter password'}" autocomplete="current-password" required />
                    <button type="button" class="toggle" id="togglePw" aria-label="Show password">{$ns.lm->getPhrase('Show')|default:'Show'}</button>
                </div>
            </div>
            <button type="submit" class="login-btn" id="loginBtn">{$ns.lm->getPhrase('Sign in')|default:'Sign in'}</button>
        </form>
        <div class="foot">{$ns.lm->getPhrase('Authorised use only')|default:'Authorised use only'}</div>
    </div>
    {literal}
    <script>
        (function () {
            var pw = document.getElementById('password'), t = document.getElementById('togglePw');
            t.addEventListener('click', function () {
                var show = pw.type === 'password';
                pw.type = show ? 'text' : 'password';
                t.textContent = show ? 'Hide' : 'Show';
                t.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
                pw.focus();
            });
            document.getElementById('loginForm').addEventListener('submit', function () {
                var b = document.getElementById('loginBtn'); b.disabled = true; b.textContent = 'Signing in…';
            });
        })();
    </script>
    {/literal}
</body>
</html>
