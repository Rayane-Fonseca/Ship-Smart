<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Packt') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --pink: #f43f5e;
            --pink2: #ec4899;
            --pink-lt: #fce7f3;
            --pink-bg: #fdf2f8;
            --text: #1f2937;
            --muted: #9ca3af;
        }

        html,
        body {
            height: 100%;
            font-family: 'Instrument Sans', sans-serif;
            background: #fdfcfe;
            color: var(--text);
            overflow-x: hidden;
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
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, #f43f5e, #ec4899);
            top: -200px;
            right: -100px;
            animation: float1 12s ease-in-out infinite;
        }

        .blob-2 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, #ec4899, #d946ef);
            bottom: -100px;
            left: -80px;
            animation: float2 15s ease-in-out infinite;
        }

        .blob-3 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, #fb7185, #f472b6);
            top: 40%;
            left: 30%;
            animation: float3 18s ease-in-out infinite;
        }

        @keyframes float1 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(-40px, 30px) scale(1.05);
            }
        }

        @keyframes float2 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(30px, -40px) scale(1.08);
            }
        }

        @keyframes float3 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(20px, -20px) scale(1.04);
            }

            66% {
                transform: translate(-20px, 20px) scale(.96);
            }
        }

        .page {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 48px;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .nav-logo-icon {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: linear-gradient(135deg, var(--pink) 0%, var(--pink2) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 6px 16px rgba(244, 63, 94, .25);
        }

        .nav-logo-text {
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -.02em;
        }

        .nav-logo-text span {
            color: var(--pink);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 18px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: .15s ease;
        }

        .nav-btn-ghost {
            color: #6b7280;
            border: 1px solid var(--pink-lt);
            background: #fff;
        }

        .nav-btn-ghost:hover {
            background: var(--pink-bg);
            color: var(--pink);
            border-color: #fbcfe8;
        }

        .nav-btn-solid {
            color: #fff;
            background: linear-gradient(135deg, var(--pink) 0%, var(--pink2) 100%);
            box-shadow: 0 6px 16px rgba(244, 63, 94, .22);
        }

        .nav-btn-solid:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(244, 63, 94, .30);
        }

        .hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 60px 24px 80px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            background: var(--pink-bg);
            border: 1px solid var(--pink-lt);
            font-size: 12px;
            font-weight: 700;
            color: var(--pink);
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 28px;
            animation: fadeUp .6s ease both;
        }

        .hero-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--pink);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .5;
                transform: scale(1.4);
            }
        }

        .hero-title {
            font-size: clamp(48px, 7vw, 88px);
            font-weight: 800;
            line-height: 1.0;
            letter-spacing: -.04em;
            color: var(--text);
            margin-bottom: 20px;
            animation: fadeUp .6s .1s ease both;
        }

        .hero-title-grad {
            background: linear-gradient(135deg, var(--pink) 0%, var(--pink2) 50%, #d946ef 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-sub {
            font-size: clamp(16px, 2vw, 20px);
            color: var(--muted);
            max-width: 520px;
            line-height: 1.6;
            margin-bottom: 44px;
            font-weight: 400;
            animation: fadeUp .6s .2s ease both;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: center;
            animation: fadeUp .6s .3s ease both;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            background: linear-gradient(135deg, var(--pink) 0%, var(--pink2) 100%);
            box-shadow: 0 10px 28px rgba(244, 63, 94, .28);
            transition: .2s ease;
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 36px rgba(244, 63, 94, .36);
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 600;
            color: #6b7280;
            text-decoration: none;
            background: #fff;
            border: 1px solid var(--pink-lt);
            transition: .2s ease;
        }

        .btn-hero-secondary:hover {
            background: var(--pink-bg);
            color: var(--pink);
            border-color: #fbcfe8;
            transform: translateY(-1px);
        }

        .features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            max-width: 860px;
            margin: 60px auto 0;
            padding: 0 24px;
            animation: fadeUp .6s .4s ease both;
        }

        .feat-card {
            background: rgba(255, 255, 255, .8);
            backdrop-filter: blur(12px);
            border: 1px solid var(--pink-lt);
            border-radius: 20px;
            padding: 24px;
            transition: .2s ease;
        }

        .feat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(244, 63, 94, .10);
            border-color: #fbcfe8;
        }

        .feat-icon {
            width: 44px;
            height: 44px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 14px;
        }

        .feat-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 6px;
        }

        .feat-text {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.6;
        }

        .footer {
            text-align: center;
            padding: 24px;
            font-size: 12px;
            color: var(--muted);
        }

        .footer a {
            color: var(--pink);
            text-decoration: none;
            font-weight: 600;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .nav {
                padding: 16px 20px;
            }

            .features {
                grid-template-columns: 1fr;
                max-width: 400px;
            }

            .hero {
                padding: 40px 20px 60px;
            }
        }

        @media (max-width: 480px) {
            .nav-logo-text {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <div class="page">

        {{-- Nav --}}
        <nav class="nav">
            <a href="{{ url('/') }}" class="nav-logo">
                <div class="nav-logo-icon">📦</div>
                <span class="nav-logo-text">Ship<span>-</span>Smart</span>
            </a>

            @if (Route::has('login'))
            <div class="nav-links">
                @auth
                <a href="{{ route('pacotes.index') }}" class="nav-btn nav-btn-ghost">Ver Pacotes</a>
                @else
                <a href="{{ route('login') }}" class="nav-btn nav-btn-ghost">Entrar</a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="nav-btn nav-btn-solid">Criar conta</a>
                @endif
                @endauth
            </div>
            @endif
        </nav>

        {{-- Hero --}}
        <main class="hero">
            <div class="hero-badge">
                Sistema de Rastreio de Pacotes
            </div>

            <h1 class="hero-title">
                Gerencie suas<br>
                <span class="hero-title-grad">entregas</span> com<br>
                facilidade
            </h1>

            <p class="hero-sub">
                Rastreie pacotes, acompanhe status em tempo real
                e mantenha suas entregas organizadas em um só lugar.
            </p>

            <div class="hero-actions">
                @auth
                <a href="{{ route('pacotes.index') }}" class="btn-hero-primary">
                    Ir para Meus Pacotes →
                </a>
                @else
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-hero-primary">
                    Começar agora 
                </a>
                @endif
                <a href="{{ route('login') }}" class="btn-hero-secondary">
                    Já tenho conta
                </a>
                @endauth
            </div>

            {{-- Feature cards --}}
            <div class="features">
                <div class="feat-card">
                    <div class="feat-icon" style="background:#fff1f2;">📦</div>
                    <div class="feat-title">Cadastro de Pacotes</div>
                    <div class="feat-text">Registre pacotes com código de rastreio, remetente, destinatário e peso.</div>
                </div>
                <div class="feat-card">
                    <div class="feat-icon" style="background:#fdf4ff;">🚚</div>
                    <div class="feat-title">Status em Tempo Real</div>
                    <div class="feat-text">Acompanhe cada pacote: Pendente, Em Rota ou Entregue com um clique.</div>
                </div>
                <div class="feat-card">
                    <div class="feat-icon" style="background:#ecfdf5;">📊</div>
                    <div class="feat-title">Dashboard Completo</div>
                    <div class="feat-text">Visualize estatísticas e os pacotes mais recentes em um painel centralizado.</div>
                </div>
            </div>
        </main>

        <footer class="footer">
            Feito com ♥ usando <a href="https://laravel.com" target="_blank">Laravel</a>
        </footer>

    </div>

</body>

</html>