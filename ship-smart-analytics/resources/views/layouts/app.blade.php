<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ship-Smart Analytics</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --pink-strong: #e5488b;
            --pink-top: #df4b8d;
            --pink-soft: #f9e8f0;
            --pink-soft-2: #f6dbe7;
            --pink-sidebar: linear-gradient(180deg, #f8e6ee 0%, #f3d2e2 100%);
            --pink-hover: #ea4c89;
            --pink-btn: #e64d8d;
            --text-dark: #1f2937;
            --text-muted: #7b7280;
            --white: #ffffff;
            --border-soft: #f0dbe5;
            --success-bg: #dcfce7;
            --success-text: #166534;
            --success-border: #bbf7d0;
            --sidebar-width: 210px;
            --topbar-height: 56px;
            --radius-lg: 20px;
            --radius-md: 16px;
            --shadow-soft: 0 8px 24px rgba(229, 72, 139, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Inter, system-ui, sans-serif;
            background: #f9edf3;
            color: var(--text-dark);
        }

        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--topbar-height);
            background: linear-gradient(90deg, rgb(244, 63, 110) 0%, rgb(236, 72, 153) 50%, rgb(219, 39, 119) 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 18px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }

        .brand-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 24px;
            height: 24px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 13px;
            font-weight: 700;
        }

        .brand a {
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .icon-btn {
            width: 42px;
            height: 42px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.24);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: linear-gradient(135deg, #f43f5e 0%, #ec4899 100%);
        }

        .profile-menu {
            position: relative;
        }

        .avatar-btn {
            width: 42px;
            height: 42px;
            border-radius: 999px;
            font-weight: 700;
            border: 1px solid rgba(255, 255, 255, 0.24);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: linear-gradient(135deg, #f43f5e 0%, #ec4899 100%);
        }

        .avatar-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(244, 63, 94, 0.30);
        }

        .profile-dropdown {
            position: absolute;
            top: 52px;
            right: 0;
            width: 220px;
            background: #fff;
            border: 1px solid #fce7f3;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
            padding: 10px;
            display: none;
            z-index: 100;
        }

        .profile-dropdown.show {
            display: block;
        }

        .profile-header {
            padding: 10px 12px;
            border-bottom: 1px solid #fce7f3;
            margin-bottom: 8px;
        }

        .profile-header strong {
            display: block;
            font-size: 14px;
            color: #1f2937;
        }

        .profile-header span {
            display: block;
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
            word-break: break-word;
        }

        .profile-dropdown a,
        .dropdown-logout {
            display: block;
            width: 100%;
            text-align: left;
            padding: 10px 12px;
            border: none;
            background: transparent;
            text-decoration: none;
            color: #374151;
            font-size: 14px;
            border-radius: 10px;
            cursor: pointer;
            transition: background .2s ease, color .2s ease;
        }

        .profile-dropdown a:hover,
        .dropdown-logout:hover {
            background: #fdf2f8;
            color: #f43f5e;
        }

        .sidebar {
            position: fixed;
            top: var(--topbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--topbar-height));
            background: var(--pink-sidebar);
            border-right: 1px solid #efd5e1;
            padding: 22px 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar-title {
            color: #c06a8d;
            font-size: .74rem;
            text-transform: uppercase;
            letter-spacing: .14em;
            margin-bottom: 14px;
            padding: 0 8px;
            font-weight: 700;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            text-decoration: none;
            color: #b04877;
            padding: 12px 14px;
            border-radius: 14px;
            font-weight: 600;
            transition: .2s ease;
        }

        .nav-link-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link:hover {
            background: rgba(228, 77, 141, 0.12);
        }

        .nav-link.active {
            background: linear-gradient(90deg, rgb(244, 63, 110) 0%, rgb(236, 72, 153) 50%, rgb(219, 39, 119) 100%);
            color: #fff;
            box-shadow: 0 10px 24px rgba(228, 77, 141, 0.24);
        }

        .nav-icon {
            width: 18px;
            text-align: center;
            font-size: 14px;
        }

        .content {
            margin-left: var(--sidebar-width);
            padding: 84px 26px 24px;
            min-height: 100vh;
            background: #f7edf0;
        }

        .page-shell {
            max-width: 940px;
        }

        .alert-success {
            background: var(--success-bg);
            color: var(--success-text);
            border: 1px solid var(--success-border);
            padding: 14px 16px;
            border-radius: 14px;
            margin-bottom: 20px;
        }

        @media (max-width: 900px) {
            .sidebar {
                display: none;
            }

            .content {
                margin-left: 0;
                padding: 78px 16px 20px;
            }

            .page-shell {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <nav class="topbar">
        <div class="brand-wrap">
            <div class="brand-icon">⚙️</div>
            <div class="brand"><a href="{{ route('dashboard') }}">Ship-Smart Analytics</a></div>
        </div>

        <div class="topbar-right">
            <button type="button" class="icon-btn">🔔</button>

            <div class="profile-menu">
                <button type="button" class="avatar-btn" id="avatarToggle" aria-expanded="false" aria-haspopup="true">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </button>

                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-header">
                        <strong>{{ Auth::user()->name }}</strong>
                        <span>{{ Auth::user()->email }}</span>
                    </div>

                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('profile.edit') }}">Meu perfil</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-logout">Sair</button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            const avatarToggle = document.getElementById('avatarToggle');
            const profileDropdown = document.getElementById('profileDropdown');

            avatarToggle.addEventListener('click', function() {
                profileDropdown.classList.toggle('show');
                const expanded = avatarToggle.getAttribute('aria-expanded') === 'true';
                avatarToggle.setAttribute('aria-expanded', !expanded);
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.profile-menu')) {
                    profileDropdown.classList.remove('show');
                    avatarToggle.setAttribute('aria-expanded', 'false');
                }
            });
        </script>
    </nav>

    <aside class="sidebar">
        <div>
            <div class="sidebar-title">Navegação</div>

            <nav class="nav-menu">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="nav-link-left">
                        <span class="nav-icon">📊</span>
                        <span>Dashboard</span>
                    </span>
                    <span>›</span>
                </a>

                <a href="{{ route('pacotes.index') }}" class="nav-link {{ request()->routeIs('pacotes.index') ? 'active' : '' }}">
                    <span class="nav-link-left">
                        <span class="nav-icon">📦</span>
                        <span>Pacotes</span>
                    </span>
                </a>

                <a href="{{ route('pacotes.create') }}" class="nav-link {{ request()->routeIs('pacotes.create') ? 'active' : '' }}">
                    <span class="nav-link-left">
                        <span class="nav-icon">➕</span>
                        <span>Novo pacote</span>
                    </span>
                </a>
            </nav>
        </div>
    </aside>

    <main class="content">
        <div class="page-shell">
            @if(session('sucesso'))
            <div class="alert-success">
                {{ session('sucesso') }}
            </div>
            @endif

            @yield('content')
        </div>
    </main>
</body>

</html>