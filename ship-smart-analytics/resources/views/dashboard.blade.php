@extends('layouts.app')

@section('content')
<style>
    /* ── Reset de qualquer max-width do layout pai ── */
    .content-wrapper,
    .main-content,
    [class*="container"],
    [class*="max-w"] {
        max-width: 100% !important;
        width: 100% !important;
    }

    /* ── Base ── */
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
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(244, 63, 94, .30);
    }

    /* ── Stats ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: #fff;
        border: 1px solid #fce7f3;
        border-radius: 20px;
        padding: 22px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
        transition: .22s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 20px;
        opacity: 0;
        transition: opacity .22s ease;
        background: linear-gradient(135deg, rgba(244, 63, 94, .03) 0%, rgba(236, 72, 153, .03) 100%);
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, .08);
    }

    .stat-card:hover::after {
        opacity: 1;
    }

    .stat-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 18px;
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

    .icon-total {
        background: #fff1f2;
    }

    .icon-pendente {
        background: #fdf2f8;
    }

    .icon-rota {
        background: #fdf4ff;
    }

    .icon-entregue {
        background: #ecfdf5;
    }

    .stat-pct {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 999px;
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

    /* Barra de progresso dinâmica */
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

    .fill-total {
        background: linear-gradient(90deg, #fb7185, #ec4899);
    }

    .fill-pendente {
        background: linear-gradient(90deg, #f472b6, #d946ef);
    }

    .fill-rota {
        background: linear-gradient(90deg, #e879f9, #f472b6);
    }

    .fill-entregue {
        background: linear-gradient(90deg, #34d399, #14b8a6);
    }

    /* ── Tabela card ── */
    .table-card {
        background: #fff;
        border: 1px solid #fce7f3;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
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

    .codigo-link {
        font-family: 'Courier New', monospace;
        font-weight: 700;
        font-size: 13px;
        color: #e11d48;
        text-decoration: none;
        transition: .13s ease;
    }

    .codigo-link:hover {
        color: #be123c;
        text-decoration: underline;
    }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 11px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .badge-pendente {
        background: #fdf2f8;
        color: #be185d;
    }

    .badge-pendente::before {
        background: #f472b6;
    }

    .badge-em-rota {
        background: #fdf4ff;
        color: #a21caf;
    }

    .badge-em-rota::before {
        background: #d946ef;
    }

    .badge-entregue {
        background: #ecfdf5;
        color: #047857;
    }

    .badge-entregue::before {
        background: #34d399;
    }

    /* Empty state */
    .empty-state {
        padding: 72px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 68px;
        height: 68px;
        margin: 0 auto 16px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);
        font-size: 30px;
    }

    .empty-title {
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 4px;
        font-size: 15px;
    }

    .empty-text {
        color: #9ca3af;
        font-size: 13px;
        margin-bottom: 18px;
    }

    /* ── Responsivo ── */
    @media (max-width: 1100px) {
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

        .table-card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .dashboard-table th,
        .dashboard-table td {
            padding: 12px 16px;
        }
    }
</style>

@php
$total = $stats['total'] ?? 0;
$pendente = $stats['pendente'] ?? 0;
$em_rota = $stats['em_rota'] ?? 0;
$entregue = $stats['entregue'] ?? 0;

$pct = fn($v) => $total > 0 ? round($v / $total * 100) : 0;
@endphp

{{-- Cabeçalho --}}
<div class="page-top">
    <div>
        <div class="breadcrumb">
            <a href="#">Home</a>
            <span>›</span>
            <strong>Dashboard</strong>
        </div>
        <h1 class="page-title">Dashboard</h1>
        <div class="page-subtitle">Visão geral das suas entregas e pacotes</div>
    </div>

    <a href="{{ route('pacotes.create') }}" class="btn-primary">+ Novo Pacote</a>
</div>

{{-- Stats --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-icon icon-total">📈</div>
            <span class="stat-pct pct-neutral">100%</span>
        </div>
        <div class="stat-value">{{ $total }}</div>
        <div class="stat-label">Total de Pacotes</div>
        <div class="stat-bar">
            <div class="stat-bar-fill fill-total" style="width:100%"></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-icon icon-pendente">🕝</div>
            <span class="stat-pct pct-warn">{{ $pct($pendente) }}%</span>
        </div>
        <div class="stat-value">{{ $pendente }}</div>
        <div class="stat-label">Pendentes</div>
        <div class="stat-bar">
            <div class="stat-bar-fill fill-pendente" style="width:{{ $pct($pendente) }}%"></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-icon icon-rota">🚚</div>
            <span class="stat-pct pct-move">{{ $pct($em_rota) }}%</span>
        </div>
        <div class="stat-value">{{ $em_rota }}</div>
        <div class="stat-label">Em Rota</div>
        <div class="stat-bar">
            <div class="stat-bar-fill fill-rota" style="width:{{ $pct($em_rota) }}%"></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-icon icon-entregue">✅</div>
            <span class="stat-pct pct-ok">{{ $pct($entregue) }}%</span>
        </div>
        <div class="stat-value">{{ $entregue }}</div>
        <div class="stat-label">Entregues</div>
        <div class="stat-bar">
            <div class="stat-bar-fill fill-entregue" style="width:{{ $pct($entregue) }}%"></div>
        </div>
    </div>

</div>

{{-- Tabela --}}
<div class="table-card">
    <div class="table-card-header">
        <div>
            <div class="table-card-title">Pacotes Recentes</div>
            <div class="table-card-subtitle">
                {{ isset($recentes) ? $recentes->count() : 0 }} pacote(s) exibido(s)
            </div>
        </div>
        <a href="{{ route('pacotes.index') }}" class="table-link">Ver todos ›</a>
    </div>

    <div class="table-wrap">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Destinatário</th>
                    <th>Peso</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($recentes) && $recentes->count())
                @foreach($recentes as $pacote)
                @php $s = strtolower($pacote->status); @endphp
                <tr>
                    <td>
                        <a href="{{ route('pacotes.show', $pacote) }}" class="codigo-link">
                            {{ $pacote->codigo }}
                        </a>
                    </td>
                    <td>{{ $pacote->destinatario }}</td>
                    <td>{{ number_format($pacote->peso, 3) }} kg</td>
                    <td>
                        @if($s === 'pendente')
                        <span class="badge badge-pendente">Pendente</span>
                        @elseif(in_array($s, ['em_rota', 'em rota']))
                        <span class="badge badge-em-rota">Em Rota</span>
                        @elseif($s === 'entregue')
                        <span class="badge badge-entregue">Entregue</span>
                        @else
                        <span class="badge badge-pendente">{{ $pacote->status }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <div class="empty-icon">📦</div>
                            <div class="empty-title">Nenhum pacote cadastrado</div>
                            <div class="empty-text">Adicione um novo pacote para começar</div>
                            <a href="{{ route('pacotes.create') }}" class="btn-primary">+ Novo pacote</a>
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection