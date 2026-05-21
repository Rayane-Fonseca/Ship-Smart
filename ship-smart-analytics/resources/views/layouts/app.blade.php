<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ship-Smart Analytics</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root{
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
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
            background: rgba(255,255,255,0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 13px;
            font-weight: 700;
        }

        .brand {
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-box {
            width: 220px;
            height: 34px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.28);
            background: rgba(255,255,255,0.12);
            color: #fff;
            padding: 0 14px;
            outline: none;
        }

        .search-box::placeholder {
            color: #ffe3ef;
        }

        .icon-btn,
        .avatar-btn {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.24);
            background: rgba(255,255,255,0.1);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .logout-btn {
            border: 0;
            background: transparent;
            color: #fff;
            cursor: pointer;
            font-size: .95rem;
            font-weight: 600;
        }

        .sidebar {
            position: fixed;
            top: var(--topbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--topbar-height));
            background: linear-gradient(rgb(255, 229, 236));
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

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: .9rem;
            color: #bb6a8f;
            margin-bottom: 6px;
        }

        .page-header {
            margin-bottom: 24px;
        }

        .page-title {
            margin: 0;
            font-size: 2rem;
            line-height: 1.1;
            color: #1f2937;
        }

        .page-subtitle {
            margin-top: 6px;
            color: #7c7480;
            font-size: .98rem;
        }

        .alert-success {
            background: var(--success-bg);
            color: var(--success-text);
            border: 1px solid var(--success-border);
            padding: 14px 16px;
            border-radius: 14px;
            margin-bottom: 20px;
        }

        .page-card {
            background: #fff;
            border: 1px solid #f0dbe5;
            border-radius: 24px;
            padding: 22px;
            box-shadow: 0 10px 30px rgba(145, 88, 116, 0.06);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #f0dbe5;
            border-radius: 18px;
            padding: 18px;
            box-shadow: 0 6px 16px rgba(145, 88, 116, 0.05);
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fce8f0;
            color: #d84e89;
            font-size: 1rem;
            font-weight: 700;
        }

        .stat-growth {
            font-size: .8rem;
            color: #4f9d78;
            background: #eefaf3;
            padding: 4px 8px;
            border-radius: 999px;
            font-weight: 700;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 2px;
        }

        .stat-label {
            color: #7c7480;
            font-size: .95rem;
        }

        .stat-line {
            margin-top: 10px;
            height: 4px;
            border-radius: 999px;
            background: #f3f0f2;
        }

        .table-card {
            background: #fff;
            border: 1px solid #f0dbe5;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(145, 88, 116, 0.06);
        }

        .table-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 20px;
            background: #fff;
        }

        .table-card-title {
            font-size: 1.55rem;
            font-weight: 700;
            color: #1f2937;
        }

        .table-card-subtitle {
            margin-top: 4px;
            color: #8b7e87;
            font-size: .9rem;
        }

        .table-link {
            color: #d8518d;
            font-weight: 700;
            text-decoration: none;
        }

        .table-head {
            display: grid;
            grid-template-columns: 1.2fr 1.4fr .8fr .9fr;
            gap: 12px;
            padding: 14px 20px;
            background: #f8edf3;
            color: #c0618c;
            font-size: .84rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .empty-state {
            min-height: 270px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            padding: 32px 20px;
        }

        .empty-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: #f9dbe9;
            color: #de4f8b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 16px;
        }

        .empty-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #6f6671;
            margin-bottom: 8px;
        }

        .empty-text {
            color: #9a9098;
            margin-bottom: 18px;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(90deg, #ea4f8f 0%, #de4b86 100%);
            color: #fff;
            padding: 11px 18px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            border: 0;
            cursor: pointer;
            box-shadow: 0 10px 18px rgba(228, 77, 141, 0.18);
        }

        .badge-pendente { background:#fef3c7; color:#92400e; }
        .badge-emrota { background:#dbeafe; color:#1e40af; }
        .badge-entregue { background:#dcfce7; color:#166534; }

        .badge-pendente,
        .badge-emrota,
        .badge-entregue {
            padding: 6px 10px;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
        }

        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
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

            .search-box {
                display: none;
            }
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .topbar-right {
                gap: 8px;
            }

            .page-title {
                font-size: 1.7rem;
            }

            .table-head {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="topbar">
        <div class="brand-wrap">
            <div class="brand-icon">⚙️</div>
            <div class="brand"><a href="#">Ship-Smart Analytics</a></div>
        </div>

        <div class="topbar-right">
            <input type="text" class="search-box" placeholder="Buscar pacote...">
            <button type="button" class="icon-btn">🔔</button>
            <div class="avatar-btn">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Sair</button>
            </form>
        </div>
    </nav>

    <aside class="sidebar">
        <div>
            <div class="sidebar-title">Navegação</div>

            <nav class="nav-menu">
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="nav-link-left">
                        <span class="nav-icon">📊</span>
                        <span>Dashboard</span>
                    </span>
                    <span>›</span>
                </a>

                <a href="{{ route('pacotes.index') }}"
                   class="nav-link {{ request()->routeIs('pacotes.*') ? 'active' : '' }}">
                    <span class="nav-link-left">
                        <span class="nav-icon">📦</span>
                        <span>Pacotes</span>
                    </span>
                </a>

                <a href="{{ route('pacotes.create') }}"
                   class="nav-link {{ request()->routeIs('pacotes.create') ? 'active' : '' }}">
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