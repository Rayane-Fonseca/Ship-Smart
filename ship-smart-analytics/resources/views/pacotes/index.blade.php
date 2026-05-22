@extends('layouts.app')

@section('content')
<style>
    .page-header {
        margin-bottom: 28px;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #f472b6;
        margin-bottom: 6px;
    }

    .breadcrumb strong {
        color: #e11d48;
    }

    .page-title {
        font-size: 28px;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .page-subtitle {
        color: #6b7280;
        font-size: 14px;
    }

    /* Botão primário */
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
        box-shadow: 0 8px 18px rgba(244, 63, 94, 0.20);
        transition: .2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(244, 63, 94, 0.28);
    }

    /* Cabeçalho da página */
    .page-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 12px;
    }

    /* Card de filtros */
    .filter-card {
        background: #fff;
        border: 1px solid #fce7f3;
        border-radius: 18px;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
    }

    .filter-form {
        display: flex;
        gap: 12px;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .filter-group.flex-1 {
        flex: 1;
        min-width: 180px;
    }

    .filter-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #ec4899;
    }

    .filter-input,
    .filter-select {
        border: 1px solid #fce7f3;
        border-radius: 12px;
        padding: 9px 14px;
        font-size: 14px;
        color: #374151;
        background: #fff;
        transition: .2s ease;
        outline: none;
    }

    .filter-input:focus,
    .filter-select:focus {
        border-color: #f472b6;
        box-shadow: 0 0 0 3px rgba(244, 114, 182, 0.15);
    }

    .filter-input::placeholder {
        color: #d1d5db;
    }

    .btn-filter {
        padding: 9px 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, #f43f5e 0%, #ec4899 100%);
        border: none;
        cursor: pointer;
        transition: .2s ease;
        box-shadow: 0 4px 12px rgba(244, 63, 94, 0.20);
    }

    .btn-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(244, 63, 94, 0.28);
    }

    .btn-clear {
        font-size: 13px;
        color: #9ca3af;
        text-decoration: none;
        padding: 9px 4px;
        transition: .15s ease;
    }

    .btn-clear:hover {
        color: #6b7280;
    }

    /* Card da tabela */
    .table-card {
        background: #fff;
        border: 1px solid #fce7f3;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
    }

    .table-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 22px 24px;
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
        margin-top: 4px;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .main-table {
        width: 100%;
        border-collapse: collapse;
    }

    .main-table thead tr {
        background: linear-gradient(90deg, #fdf2f8 0%, #fce7f3 100%);
    }

    .main-table th {
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #ec4899;
        padding: 14px 24px;
        font-weight: 700;
        white-space: nowrap;
    }

    .main-table td {
        padding: 16px 24px;
        border-top: 1px solid #fdf2f8;
        color: #374151;
        font-size: 14px;
    }

    .main-table tbody tr {
        transition: background .15s ease;
    }

    .main-table tbody tr:hover {
        background: #fdf9fb;
    }

    .codigo-cell {
        font-family: 'Courier New', monospace;
        font-weight: 700;
        color: #e11d48;
        font-size: 13px;
    }

    .weight-cell {
        color: #6b7280;
    }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
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

    .badge-emrota {
        background: #fdf4ff;
        color: #a21caf;
    }

    .badge-emrota::before {
        background: #d946ef;
    }

    .badge-entregue {
        background: #ecfdf5;
        color: #047857;
    }

    .badge-entregue::before {
        background: #34d399;
    }

    /* Ações */
    .actions-cell {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: .15s ease;
        border: none;
        cursor: pointer;
        background: transparent;
    }

    .action-ver {
        color: #3b82f6;
        background: #eff6ff;
    }

    .action-ver:hover {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .action-editar {
        color: #d97706;
        background: #fffbeb;
    }

    .action-editar:hover {
        background: #fef3c7;
        color: #b45309;
    }

    .action-excluir {
        color: #ef4444;
        background: #fef2f2;
    }

    .action-excluir:hover {
        background: #fee2e2;
        color: #b91c1c;
    }

    /* Empty state */
    .empty-state {
        padding: 64px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 14px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);
        color: #f472b6;
        font-size: 28px;
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
        margin-bottom: 16px;
    }

    /* Paginação */
    .pagination-wrap {
        padding: 16px 24px;
        border-top: 1px solid #fdf2f8;
    }

    /* Responsivo */
    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
        }

        .filter-group.flex-1 {
            width: 100%;
        }

        .page-title {
            font-size: 22px;
        }

        .table-card-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

{{-- Cabeçalho --}}
<div class="page-top">
    <div class="page-header" style="margin-bottom:0">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}" style="color:#f472b6;text-decoration:none;">Home</a>
            <span>›</span>
            <span><strong>Pacotes</strong></span>
        </div>
        <h1 class="page-title">Pacotes</h1>
        <div class="page-subtitle">Gerencie todos os seus pacotes e entregas</div>
    </div>

    <a href="{{ route('pacotes.create') }}" class="btn-primary">
        + Novo Pacote
    </a>
</div>

{{-- Filtros --}}
<div class="filter-card">
    <form method="GET" action="{{ route('pacotes.index') }}" class="filter-form">
        <div class="filter-group flex-1">
            <label class="filter-label">Buscar</label>
            <input type="text" name="busca" value="{{ request('busca') }}"
                placeholder="Código, destinatário ou nome..."
                class="filter-input">
        </div>
        <div class="filter-group">
            <label class="filter-label">Status</label>
            <select name="status" class="filter-select">
                <option value="">Todos</option>
                <option value="Pendente" {{ request('status') == 'Pendente'  ? 'selected' : '' }}>Pendente</option>
                <option value="Em Rota" {{ request('status') == 'Em Rota'   ? 'selected' : '' }}>Em Rota</option>
                <option value="Entregue" {{ request('status') == 'Entregue'  ? 'selected' : '' }}>Entregue</option>
            </select>
        </div>
        <button type="submit" class="btn-filter">Filtrar</button>
        <a href="{{ route('pacotes.index') }}" class="btn-clear">Limpar</a>
    </form>
</div>

{{-- Tabela --}}
<div class="table-card">
    <div class="table-card-header">
        <div>
            <div class="table-card-title">Lista de Pacotes</div>
            <div class="table-card-subtitle">{{ $pacotes->total() }} pacote(s) encontrado(s)</div>
        </div>
    </div>

    <div class="table-wrap">
        <table class="main-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Remetente</th>
                    <th>Destinatário</th>
                    <th>Peso</th>
                    <th>Qtd</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pacotes as $pacote)
                <tr>
                    <td class="codigo-cell">{{ $pacote->codigo }}</td>
                    <td>{{ $pacote->nome }}</td>
                    <td>{{ $pacote->fabricante_fornecedor }}</td>
                    <td>{{ $pacote->destinatario }}</td>
                    <td class="weight-cell">{{ number_format($pacote->peso, 3) }} kg</td>
                    <td>{{ $pacote->quantidade }}</td>
                    <td>
                        @php
                        $badge = match($pacote->status) {
                        'Pendente' => 'badge-pendente',
                        'Em Rota' => 'badge-emrota',
                        'Entregue' => 'badge-entregue',
                        default => 'badge-pendente',
                        };
                        @endphp
                        <span class="badge {{ $badge }}">{{ $pacote->status }}</span>
                    </td>
                    <td>
                        <div class="actions-cell">
                            <a href="{{ route('pacotes.show', $pacote) }}" class="action-btn action-ver">Ver</a>
                            <a href="{{ route('pacotes.edit', $pacote) }}" class="action-btn action-editar">Editar</a>
                            <form method="POST" action="{{ route('pacotes.destroy', $pacote) }}"
                                onsubmit="return confirm('Confirmar exclusão?')" style="margin:0">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn action-excluir">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="empty-icon">📦</div>
                            <div class="empty-title">Nenhum pacote encontrado</div>
                            <div class="empty-text">Adicione um novo pacote para começar</div>
                            <a href="{{ route('pacotes.create') }}" class="btn-primary">+ Novo Pacote</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $pacotes->withQueryString()->links() }}
    </div>
</div>
@endsection