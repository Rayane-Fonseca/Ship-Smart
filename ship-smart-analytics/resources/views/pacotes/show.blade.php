*@extends('layouts.app')

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

    .breadcrumb a {
        color: #f472b6;
        text-decoration: none;
        transition: .15s ease;
    }

    .breadcrumb a:hover {
        color: #e11d48;
    }

    .breadcrumb strong {
        color: #e11d48;
    }

    .page-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 12px;
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

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 16px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        color: #9ca3af;
        background: #f9fafb;
        border: 1px solid #f3f4f6;
        text-decoration: none;
        transition: .15s ease;
    }

    .btn-back:hover {
        color: #6b7280;
        background: #f3f4f6;
    }

    /* Card principal */
    .detail-card {
        background: #fff;
        border: 1px solid #fce7f3;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0,0,0,.04);
        max-width: 780px;
        margin: 0 auto;
    }

    /* Topo do card com código e status */
    .detail-hero {
        background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);
        padding: 28px 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #fce7f3;
        flex-wrap: wrap;
        gap: 12px;
    }

    .detail-hero-left {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .detail-hero-eyebrow {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #f472b6;
    }

    .detail-codigo {
        font-family: 'Courier New', monospace;
        font-size: 26px;
        font-weight: 800;
        color: #e11d48;
        line-height: 1;
    }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge::before {
        content: '';
        width: 7px;
        height: 7px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .badge-pendente  { background: #fdf2f8; color: #be185d; }
    .badge-pendente::before  { background: #f472b6; }

    .badge-emrota    { background: #fdf4ff; color: #a21caf; }
    .badge-emrota::before    { background: #d946ef; }

    .badge-entregue  { background: #ecfdf5; color: #047857; }
    .badge-entregue::before  { background: #34d399; }

    /* Grid de campos */
    .detail-body {
        padding: 28px 32px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0;
    }

    .detail-field {
        padding: 16px 20px;
        border-radius: 14px;
        transition: background .15s ease;
    }

    .detail-field:hover {
        background: #fdf9fb;
    }

    .detail-field-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: #ec4899;
        margin-bottom: 5px;
    }

    .detail-field-value {
        font-size: 15px;
        font-weight: 600;
        color: #1f2937;
    }

    .detail-field-value.muted {
        color: #6b7280;
        font-weight: 400;
        font-size: 13px;
    }

    /* Divisor */
    .detail-divider {
        height: 1px;
        background: #fdf2f8;
        margin: 4px 0;
        grid-column: 1 / -1;
    }

    /* Rodapé com ações */
    .detail-footer {
        padding: 20px 32px;
        border-top: 1px solid #fdf2f8;
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-editar {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        color: #b45309;
        background: #fffbeb;
        border: 1px solid #fef3c7;
        text-decoration: none;
        transition: .15s ease;
        cursor: pointer;
    }

    .btn-editar:hover {
        background: #fef3c7;
        color: #92400e;
    }

    .btn-excluir {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        color: #b91c1c;
        background: #fef2f2;
        border: 1px solid #fee2e2;
        text-decoration: none;
        transition: .15s ease;
        cursor: pointer;
    }

    .btn-excluir:hover {
        background: #fee2e2;
        color: #991b1b;
    }

    @media (max-width: 640px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .detail-hero {
            padding: 22px 20px;
        }

        .detail-body {
            padding: 20px;
        }

        .detail-footer {
            padding: 16px 20px;
        }

        .page-title {
            font-size: 22px;
        }
    }
</style>

<div class="page-top">
    <div class="page-header" style="margin-bottom:0">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Home</a>
            <span>›</span>
            <a href="{{ route('pacotes.index') }}">Pacotes</a>
            <span>›</span>
            <strong>Detalhes</strong>
        </div>
        <h1 class="page-title">Detalhes do Pacote</h1>
        <div class="page-subtitle">Informações completas do pacote selecionado</div>
    </div>

    <a href="{{ route('pacotes.index') }}" class="btn-back">← Voltar</a>
</div>

<div class="detail-card">

    {{-- Hero: código + status --}}
    <div class="detail-hero">
        <div class="detail-hero-left">
            <div class="detail-hero-eyebrow">Código do Pacote</div>
            <div class="detail-codigo">{{ $pacote->codigo }}</div>
        </div>

        @php
            $badge = match($pacote->status) {
                'Pendente' => 'badge-pendente',
                'Em Rota'  => 'badge-emrota',
                'Entregue' => 'badge-entregue',
                default    => 'badge-pendente',
            };
        @endphp
        <span class="badge {{ $badge }}">{{ $pacote->status }}</span>
    </div>

    {{-- Campos --}}
    <div class="detail-body">
        <div class="detail-grid">

            <div class="detail-field">
                <div class="detail-field-label">Nome</div>
                <div class="detail-field-value">{{ $pacote->nome }}</div>
            </div>

            <div class="detail-field">
                <div class="detail-field-label">Remetente</div>
                <div class="detail-field-value">{{ $pacote->fabricante_fornecedor }}</div>
            </div>

            <div class="detail-divider"></div>

            <div class="detail-field">
                <div class="detail-field-label">Destinatário</div>
                <div class="detail-field-value">{{ $pacote->destinatario }}</div>
            </div>

            <div class="detail-field">
                <div class="detail-field-label">Peso</div>
                <div class="detail-field-value">{{ number_format($pacote->peso, 3) }} kg</div>
            </div>

            <div class="detail-divider"></div>

            <div class="detail-field">
                <div class="detail-field-label">Preço</div>
                <div class="detail-field-value">R$ {{ number_format($pacote->preco, 2, ',', '.') }}</div>
            </div>

            <div class="detail-field">
                <div class="detail-field-label">Quantidade</div>
                <div class="detail-field-value">{{ $pacote->quantidade }}</div>
            </div>

            <div class="detail-divider"></div>

            <div class="detail-field">
                <div class="detail-field-label">Criado em</div>
                <div class="detail-field-value muted">{{ $pacote->created_at->format('d/m/Y H:i') }}</div>
            </div>

            <div class="detail-field">
                <div class="detail-field-label">Atualizado em</div>
                <div class="detail-field-value muted">{{ $pacote->updated_at->format('d/m/Y H:i') }}</div>
            </div>

        </div>
    </div>

    {{-- Ações --}}
    <div class="detail-footer">
        <a href="{{ route('pacotes.edit', $pacote) }}" class="btn-editar">
            ✏️ Editar
        </a>
        <form method="POST" action="{{ route('pacotes.destroy', $pacote) }}"
              onsubmit="return confirm('Confirmar exclusão?')" style="margin:0">
            @csrf @method('DELETE')
            <button type="submit" class="btn-excluir">
                🗑️ Excluir
            </button>
        </form>
    </div>

</div>
@endsection