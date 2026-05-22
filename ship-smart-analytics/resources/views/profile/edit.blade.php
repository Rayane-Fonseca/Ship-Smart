@extends('layouts.app')

@php
$user = auth()->user();

$funcionariosGerenciados = $funcionariosGerenciados ?? 284;
$pacotesHora = $pacotesHora ?? 3240;
$rotasOtimizadas = $rotasOtimizadas ?? 96;
$eficienciaOperacional = $eficienciaOperacional ?? 92;

$setores = $setores ?? collect([
(object) ['nome' => 'Recebimento', 'colaboradores' => 48, 'status' => 'estável'],
(object) ['nome' => 'Separação', 'colaboradores' => 76, 'status' => 'alta demanda'],
(object) ['nome' => 'Expedição', 'colaboradores' => 61, 'status' => 'otimizado'],
(object) ['nome' => 'Roteirização', 'colaboradores' => 19, 'status' => 'estratégico'],
]);

$atividades = $atividades ?? collect([
(object) ['acao' => 'Atualizou regras de priorização de rotas', 'tempo' => 'Há 12 min'],
(object) ['acao' => 'Revisou desempenho da equipe da expedição', 'tempo' => 'Há 28 min'],
(object) ['acao' => 'Aprovou ajuste de fluxo no corredor B', 'tempo' => 'Há 1 hora'],
(object) ['acao' => 'Exportou relatório operacional', 'tempo' => 'Hoje, 08:40'],
]);
@endphp

@section('content')
<style>
    .content-wrapper,
    .main-content,
    [class*="container"],
    [class*="max-w"] {
        max-width: 100% !important;
        width: 100% !important;
    }

    .profile-page {
        width: 100%;
    }

    .page-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #f472b6;
        margin-bottom: 6px;
    }

    .breadcrumb a {
        color: #f472b6;
        text-decoration: none;
        transition: .15s;
    }

    .breadcrumb a:hover {
        color: #e11d48;
    }

    .breadcrumb strong {
        color: #e11d48;
    }

    .page-title {
        font-size: 28px;
        font-weight: 800;
        color: #1f2937;
        margin: 0 0 4px;
        line-height: 1.1;
    }

    .page-subtitle {
        color: #9ca3af;
        font-size: 14px;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
        border-radius: 14px;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        background: linear-gradient(135deg, #f43f5e 0%, #ec4899 100%);
        box-shadow: 0 8px 18px rgba(244, 63, 94, .22);
        transition: .2s ease;
        border: none;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(244, 63, 94, .30);
        color: #fff;
    }

    .profile-hero {
        display: grid;
        grid-template-columns: 1.35fr .95fr;
        gap: 20px;
        margin-bottom: 28px;
    }

    .hero-card,
    .panel-card,
    .stat-card,
    .table-card,
    .insight-card {
        background: #fff;
        border: 1px solid #fce7f3;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
    }

    .hero-card {
        padding: 26px;
        position: relative;
        overflow: hidden;
    }

    .hero-card::after,
    .stat-card::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        opacity: 0;
        transition: opacity .22s ease;
        background: linear-gradient(135deg, rgba(244, 63, 94, .03) 0%, rgba(236, 72, 153, .03) 100%);
        pointer-events: none;
    }

    .hero-card:hover::after,
    .stat-card:hover::after {
        opacity: 1;
    }

    .hero-content {
        position: relative;
        z-index: 1;
        display: flex;
        gap: 18px;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .avatar {
        width: 88px;
        height: 88px;
        border-radius: 22px;
        background: linear-gradient(135deg, #fff1f2 0%, #fce7f3 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        font-weight: 800;
        color: #be123c;
        flex-shrink: 0;
    }

    .hero-name {
        font-size: 28px;
        font-weight: 800;
        color: #1f2937;
        margin: 0 0 6px;
        line-height: 1.1;
    }

    .hero-role {
        font-size: 14px;
        color: #e11d48;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .hero-description {
        font-size: 14px;
        color: #6b7280;
        max-width: 760px;
        line-height: 1.65;
        margin-bottom: 16px;
    }

    .tag-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        background: #fff1f2;
        color: #be123c;
        white-space: nowrap;
        border: 1px solid #ffe4e6;
    }

    .panel-card {
        padding: 24px;
    }

    .panel-title {
        font-size: 16px;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 14px;
    }

    .profile-meta {
        display: grid;
        gap: 12px;
    }

    .meta-item {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        padding: 12px 14px;
        border-radius: 14px;
        background: #fdf9fb;
        border: 1px solid #fdf2f8;
    }

    .meta-label {
        color: #9ca3af;
        font-size: 13px;
    }

    .meta-value {
        color: #1f2937;
        font-size: 13px;
        font-weight: 700;
        text-align: right;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .stat-card {
        padding: 22px;
        transition: .22s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, .08);
    }

    .stat-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 18px;
        gap: 10px;
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        flex-shrink: 0;
    }

    .icon-func {
        background: #fff1f2;
    }

    .icon-pacotes {
        background: #fdf2f8;
    }

    .icon-rotas {
        background: #fdf4ff;
    }

    .icon-efi {
        background: #ecfdf5;
    }

    .stat-pct {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 999px;
        white-space: nowrap;
    }

    .pct-neutral {
        color: #6b7280;
        background: #f3f4f6;
    }

    .pct-warn {
        color: #b45309;
        background: #fffbeb;
    }

    .pct-move {
        color: #7c3aed;
        background: #f5f3ff;
    }

    .pct-ok {
        color: #047857;
        background: #ecfdf5;
    }

    .stat-value {
        font-size: 34px;
        line-height: 1;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 6px;
        font-variant-numeric: tabular-nums;
    }

    .stat-label {
        font-size: 13px;
        color: #9ca3af;
        margin-bottom: 14px;
        font-weight: 500;
    }

    .stat-bar {
        height: 5px;
        border-radius: 999px;
        background: #f3f4f6;
        overflow: hidden;
    }

    .stat-bar-fill {
        height: 100%;
        border-radius: 999px;
        transition: width .6s cubic-bezier(.4, 0, .2, 1);
        min-width: 4px;
    }

    .fill-func {
        background: linear-gradient(90deg, #fb7185, #ec4899);
        width: 78%;
    }

    .fill-pacotes {
        background: linear-gradient(90deg, #f472b6, #d946ef);
        width: 92%;
    }

    .fill-rotas {
        background: linear-gradient(90deg, #e879f9, #f472b6);
        width: 96%;
    }

    .fill-efi {
        background: linear-gradient(90deg, #34d399, #14b8a6);
        width: 92%;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1.15fr .85fr;
        gap: 20px;
    }

    .table-card {
        overflow: hidden;
    }

    .table-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 22px 28px;
        border-bottom: 1px solid #fdf2f8;
        flex-wrap: wrap;
        gap: 10px;
    }

    .table-card-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
    }

    .table-card-subtitle {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 3px;
    }

    .table-link {
        color: #f43f5e;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 7px 14px;
        border-radius: 10px;
        background: #fff1f2;
        transition: .15s ease;
    }

    .table-link:hover {
        background: #ffe4e6;
        color: #be123c;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .dashboard-table {
        width: 100%;
        border-collapse: collapse;
    }

    .dashboard-table thead tr {
        background: linear-gradient(90deg, #fdf2f8 0%, #fce7f3 100%);
    }

    .dashboard-table th {
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .09em;
        color: #ec4899;
        padding: 13px 28px;
        font-weight: 700;
        white-space: nowrap;
    }

    .dashboard-table td {
        padding: 15px 28px;
        border-top: 1px solid #fdf2f8;
        color: #374151;
        font-size: 14px;
    }

    .dashboard-table tbody tr {
        transition: background .13s ease;
    }

    .dashboard-table tbody tr:hover {
        background: #fdf9fb;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 11px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .status-estavel {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .status-estavel::before {
        background: #60a5fa;
    }

    .status-alta-demanda {
        background: #fffbeb;
        color: #b45309;
    }

    .status-alta-demanda::before {
        background: #f59e0b;
    }

    .status-otimizado {
        background: #ecfdf5;
        color: #047857;
    }

    .status-otimizado::before {
        background: #34d399;
    }

    .status-estrategico {
        background: #fdf4ff;
        color: #a21caf;
    }

    .status-estrategico::before {
        background: #d946ef;
    }

    .sidebar-stack {
        display: grid;
        gap: 20px;
    }

    .insight-card {
        padding: 22px;
    }

    .insight-title {
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 14px;
    }

    .insight-text {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.65;
    }

    .activity-list {
        display: grid;
        gap: 12px;
        margin-top: 4px;
    }

    .activity-item {
        padding: 14px;
        border-radius: 14px;
        background: #fdf9fb;
        border: 1px solid #fdf2f8;
    }

    .activity-action {
        font-size: 14px;
        color: #374151;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .activity-time {
        font-size: 12px;
        color: #9ca3af;
    }

    .quick-actions {
        display: grid;
        gap: 10px;
    }

    .quick-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-decoration: none;
        color: #374151;
        background: #fff;
        border: 1px solid #fce7f3;
        border-radius: 14px;
        padding: 14px 16px;
        transition: .18s ease;
    }

    .quick-link:hover {
        transform: translateY(-2px);
        background: #fff8fa;
        color: #be123c;
    }

    @media (max-width: 1180px) {

        .profile-hero,
        .content-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .page-title {
            font-size: 22px;
        }

        .hero-card,
        .panel-card,
        .insight-card {
            padding: 20px;
        }

        .table-card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .dashboard-table th,
        .dashboard-table td {
            padding: 12px 16px;
        }

        .hero-name {
            font-size: 24px;
        }
    }
</style>

<div class="profile-page">
    <div class="page-top">
        <div>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Home</a>
                <span>›</span>
                <strong>Meu Perfil</strong>
            </div>

            <h1 class="page-title">Perfil da Gestora</h1>
            <div class="page-subtitle">
                Central de identidade, desempenho operacional e preferências da usuária logada.
            </div>
        </div>

        <a href="{{ route('profile.edit') }}" class="btn-primary">✏️ Editar Perfil</a>
    </div>

    <div class="profile-hero">
        <div class="hero-card">
            <div class="hero-content">
                <div class="avatar">
                    {{ strtoupper(substr($user->name ?? 'F', 0, 1)) }}
                </div>

                <div>
                    <h2 class="hero-name">{{ $user->name ?? 'Fernanda Souza' }}</h2>
                    <div class="hero-role">Gestora de Operações Logísticas</div>

                    <div class="hero-description">
                        Fernanda supervisiona centenas de funcionários e acompanha milhares de pacotes por hora.
                        Seu foco principal é transformar dados operacionais em decisões rápidas para melhorar fluxo,
                        produtividade e otimização de rotas dentro do armazém.
                    </div>

                    <div class="tag-list">
                        <span class="tag">📦 Alta volumetria</span>
                        <span class="tag">👥 Gestão de equipes</span>
                        <span class="tag">📊 Análise operacional</span>
                        <span class="tag">🚚 Otimização de rotas</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-card">
            <div class="panel-title">Informações da conta</div>

            <div class="profile-meta">
                <div class="meta-item">
                    <div class="meta-label">Nome</div>
                    <div class="meta-value">{{ $user->name ?? 'Fernanda Souza' }}</div>
                </div>

                <div class="meta-item">
                    <div class="meta-label">E-mail</div>
                    <div class="meta-value">{{ $user->email ?? '[email protected]' }}</div>
                </div>

                <div class="meta-item">
                    <div class="meta-label">Perfil de acesso</div>
                    <div class="meta-value">Gestora Master</div>
                </div>

                <div class="meta-item">
                    <div class="meta-label">Unidade</div>
                    <div class="meta-value">Armazém Central - Resende</div>
                </div>

                <div class="meta-item">
                    <div class="meta-label">Turno principal</div>
                    <div class="meta-value">Operação contínua</div>
                </div>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon icon-func">👥</div>
                <span class="stat-pct pct-neutral">Equipe ampla</span>
            </div>
            <div class="stat-value">{{ number_format($funcionariosGerenciados, 0, ',', '.') }}</div>
            <div class="stat-label">Funcionários gerenciados</div>
            <div class="stat-bar">
                <div class="stat-bar-fill fill-func"></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon icon-pacotes">📦</div>
                <span class="stat-pct pct-warn">Alta vazão</span>
            </div>
            <div class="stat-value">{{ number_format($pacotesHora, 0, ',', '.') }}</div>
            <div class="stat-label">Pacotes por hora</div>
            <div class="stat-bar">
                <div class="stat-bar-fill fill-pacotes"></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon icon-rotas">🧭</div>
                <span class="stat-pct pct-move">{{ $rotasOtimizadas }}%</span>
            </div>
            <div class="stat-value">{{ $rotasOtimizadas }}</div>
            <div class="stat-label">Índice de rotas otimizadas</div>
            <div class="stat-bar">
                <div class="stat-bar-fill fill-rotas"></div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon icon-efi">✅</div>
                <span class="stat-pct pct-ok">{{ $eficienciaOperacional }}%</span>
            </div>
            <div class="stat-value">{{ $eficienciaOperacional }}</div>
            <div class="stat-label">Eficiência operacional</div>
            <div class="stat-bar">
                <div class="stat-bar-fill fill-efi"></div>
            </div>
        </div>
    </div>

    <div class="content-grid">
        <div class="table-card">
            <div class="table-card-header">
                <div>
                    <div class="table-card-title">Setores sob gestão</div>
                    <div class="table-card-subtitle">Distribuição das equipes acompanhadas pela usuária logada</div>
                </div>

                <a href="#" class="table-link">Ver operação completa ›</a>
            </div>

            <div class="table-wrap">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Setor</th>
                            <th>Colaboradores</th>
                            <th>Status operacional</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($setores as $setor)
                        @php
                        $statusClass = match(strtolower(str_replace(' ', '-', $setor->status))) {
                        'estável', 'estavel' => 'status-estavel',
                        'alta-demanda' => 'status-alta-demanda',
                        'otimizado' => 'status-otimizado',
                        'estratégico', 'estrategico' => 'status-estrategico',
                        default => 'status-estavel',
                        };
                        @endphp

                        <tr>
                            <td>{{ $setor->nome }}</td>
                            <td>{{ number_format($setor->colaboradores, 0, ',', '.') }}</td>
                            <td>
                                <span class="status-badge {{ $statusClass }}">
                                    {{ ucfirst($setor->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="sidebar-stack">
            <div class="insight-card">
                <div class="insight-title">Resumo analítico</div>
                <div class="insight-text">
                    O perfil desta usuária foi estruturado para destacar contexto decisório, volume operacional
                    e capacidade de liderança. Em um ambiente com muitos colaboradores e alta circulação de pacotes,
                    o perfil precisa expor rapidamente o papel da gestora, seus indicadores e áreas críticas da operação.
                </div>
            </div>

            <div class="insight-card">
                <div class="insight-title">Atividades recentes</div>
                <div class="activity-list">
                    @foreach($atividades as $atividade)
                    <div class="activity-item">
                        <div class="activity-action">{{ $atividade->acao }}</div>
                        <div class="activity-time">{{ $atividade->tempo }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="insight-card">
                <div class="insight-title">Ações rápidas</div>
                <div class="quick-actions">
                    <a href="#" class="quick-link">
                        <span>Gerenciar funcionários</span>
                        <span>›</span>
                    </a>

                    <a href="#" class="quick-link">
                        <span>Analisar fluxo de pacotes</span>
                        <span>›</span>
                    </a>

                    <a href="#" class="quick-link">
                        <span>Revisar rotas internas</span>
                        <span>›</span>
                    </a>

                    <a href="#" class="quick-link">
                        <span>Exportar relatório</span>
                        <span>›</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection