<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Ship-Smart') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    <style>
        :root {
            --pink: #f43f5e;
            --pink2: #ec4899;
            --pink-lt: #fce7f3;
            --pink-bg: #fdf2f8;
            --text: #1f2937;
            --muted: #6b7280;
        }

        body {
            background: #fdfcfe;
            font-family: 'Instrument Sans', sans-serif;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        .bg-blobs {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .18;
        }

        .blob-1 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, #f43f5e, #ec4899);
            top: -150px;
            right: -100px;
        }

        .blob-2 {
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, #ec4899, #d946ef);
            bottom: -100px;
            left: -80px;
        }

        .login-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 430px;
            background: rgba(255, 255, 255, .9);
            backdrop-filter: blur(12px);
            border: 1px solid var(--pink-lt);
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 20px 50px rgba(244, 63, 94, 0.12);
        }

        .login-brand {
            text-align: center;
            margin-bottom: 24px;
        }

        .login-brand h1 {
            font-size: 32px;
            font-weight: 800;
            color: var(--text);
            line-height: 1.1;
        }

        .login-brand h1 span {
            color: var(--pink);
        }

        .login-brand p {
            margin-top: 10px;
            font-size: 14px;
            color: var(--muted);
        }

        .field {
            margin-top: 18px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
        }

        .field input {
            width: 100%;
            border: 1px solid #fbcfe8;
            border-radius: 14px;
            padding: 12px 14px;
            font-size: 14px;
            outline: none;
            transition: .2s ease;
        }

        .field input:focus {
            border-color: var(--pink);
            box-shadow: 0 0 0 4px rgba(244, 63, 94, .12);
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 18px;
            flex-wrap: wrap;
        }

        .remember-row label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--muted);
        }

        .remember-row a {
            font-size: 14px;
            color: var(--pink);
            text-decoration: none;
            font-weight: 600;
        }

        .login-btn {
            width: 100%;
            margin-top: 24px;
            border: none;
            border-radius: 16px;
            padding: 14px 20px;
            font-size: 15px;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, var(--pink) 0%, var(--pink2) 100%);
            box-shadow: 0 10px 28px rgba(244, 63, 94, .28);
            cursor: pointer;
            transition: .2s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 36px rgba(244, 63, 94, .36);
        }

        .status-box {
            margin-bottom: 16px;
        }
    </style>
</head>

<body>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-brand">
                <h1>Ship<span>-</span>Analytics</h1>
                <p>Entre para acompanhar seus pacotes com facilidade.</p>
            </div>

            <div class="status-box">
                <x-auth-session-status class="mb-4" :status="session('status')" />
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input
                        id="email"
                        class="block mt-1 w-full"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="field">
                    <x-input-label for="password" :value="__('Senha')" />
                    <x-text-input
                        id="password"
                        class="block mt-1 w-full"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="remember-row">
                    <label for="remember_me">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span>{{ __('Lembrar de mim') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        {{ __('Esqueceu sua senha?') }}
                    </a>
                    @endif
                </div>

                <button type="submit" class="login-btn">
                    {{ __('Entrar') }}
                </button>
            </form>
        </div>
    </div>
</body>

</html>