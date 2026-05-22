@php $p = $pacote ?? null; @endphp

<style>
    /* ── Erro global ── */
    .form-error-box {
        margin-bottom: 20px;
        background: #fff1f2;
        border: 1px solid #fecdd3;
        color: #be123c;
        border-radius: 16px;
        padding: 14px 18px;
        font-size: 14px;
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .form-error-box-icon {
        font-size: 18px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .form-error-box ul {
        margin: 6px 0 0 16px;
        line-height: 1.7;
    }

    /* ── Card ── */
    .form-card {
        background: #fff;
        border: 1px solid #fce7f3;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
    }

    .form-card-header {
        padding: 22px 28px;
        border-bottom: 1px solid #fdf2f8;
        background: linear-gradient(90deg, #fdf2f8 0%, #fce7f3 100%);
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .form-card-header-icon {
        width: 42px;
        height: 42px;
        border-radius: 13px;
        background: linear-gradient(135deg, #f43f5e 0%, #ec4899 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(244, 63, 94, .20);
    }

    .form-card-title {
        font-size: 17px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 2px;
    }

    .form-card-subtitle {
        font-size: 12px;
        color: #9ca3af;
    }

    .form-card-body {
        padding: 28px;
    }

    /* ── Seções ── */
    .form-section {
        margin-bottom: 28px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .form-section-label {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: #ec4899;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #fce7f3;
    }

    /* ── Grid ── */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .form-grid.cols-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    /* ── Labels ── */
    .form-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: #ec4899;
        margin-bottom: 7px;
    }

    /* ── Inputs ── */
    .form-input,
    .form-select {
        width: 100%;
        border: 1px solid #fce7f3;
        border-radius: 12px;
        padding: 11px 14px;
        font-size: 14px;
        color: #1f2937;
        background: #fff;
        outline: none;
        transition: border-color .18s ease, box-shadow .18s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .03);
        -webkit-appearance: none;
        appearance: none;
    }

    .form-input::placeholder {
        color: #d1d5db;
    }

    .form-input:hover,
    .form-select:hover {
        border-color: #fbcfe8;
    }

    .form-input:focus,
    .form-select:focus {
        border-color: #f472b6;
        box-shadow: 0 0 0 4px rgba(244, 114, 182, .13);
    }

    /* Select com seta customizada */
    .form-select-wrap {
        position: relative;
    }

    .form-select-wrap::after {
        content: '▾';
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #f472b6;
        font-size: 13px;
        pointer-events: none;
    }

    .form-select-wrap .form-select {
        padding-right: 36px;
        cursor: pointer;
    }

    /* Mono (código de rastreio) */
    .form-input-mono {
        font-family: 'Courier New', monospace;
        letter-spacing: .04em;
        font-size: 13px;
        color: #e11d48;
        font-weight: 600;
    }

    /* Prefixo (R$, kg) */
    .form-input-wrap {
        position: relative;
    }

    .form-input-prefix {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 13px;
        font-weight: 600;
        color: #9ca3af;
        pointer-events: none;
        user-select: none;
    }

    .form-input-wrap .form-input {
        padding-left: 42px;
    }

    .form-input-suffix {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 12px;
        font-weight: 600;
        color: #9ca3af;
        pointer-events: none;
        user-select: none;
    }

    .form-input-wrap.has-suffix .form-input {
        padding-right: 44px;
    }

    /* Help text */
    .form-help {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 5px;
    }

    /* Erro por campo */
    .form-error-text {
        font-size: 11px;
        color: #e11d48;
        margin-top: 5px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .form-error-text::before {
        content: '⚠';
        font-size: 10px;
    }

    .has-error {
        border-color: #fb7185 !important;
        box-shadow: 0 0 0 4px rgba(251, 113, 133, .12) !important;
    }

    /* Status com bolinha colorida */
    .status-option-pendente {
        color: #be185d;
    }

    .status-option-emrota {
        color: #a21caf;
    }

    .status-option-entregue {
        color: #047857;
    }

    @media (max-width: 900px) {
        .form-grid.cols-3 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {

        .form-grid,
        .form-grid.cols-3 {
            grid-template-columns: 1fr;
        }

        .form-card-body,
        .form-card-header {
            padding: 18px 16px;
        }
    }
</style>

{{-- Erros globais --}}
@if($errors->any())
<div class="form-error-box">
    <div class="form-error-box-icon">🚫</div>
    <div>
        <strong>Não foi possível salvar o pacote:</strong>
        <ul>
            @foreach($errors->all() as $erro)
            <li>{{ $erro }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div class="form-card">
    <div class="form-card-header">
        <div class="form-card-header-icon">📦</div>
        <div>
            <div class="form-card-title">Informações do Pacote</div>
            <div class="form-card-subtitle">Preencha os dados para cadastrar ou editar um pacote</div>
        </div>
    </div>

    <div class="form-card-body">

        {{-- Seção: Identificação --}}
        <div class="form-section">
            <div class="form-section-label">Identificação</div>
            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label">Nome do Pacote *</label>
                    <input
                        type="text"
                        name="nome"
                        value="{{ old('nome', $p?->nome) }}"
                        class="form-input @error('nome') has-error @enderror"
                        placeholder="Ex: Caixa eletrônicos">
                    @error('nome')
                    <div class="form-error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Código de Rastreio *</label>
                    <input
                        type="text"
                        name="codigo"
                        value="{{ old('codigo', $p?->codigo) }}"
                        class="form-input form-input-mono @error('codigo') has-error @enderror"
                        placeholder="Ex: BR123456789">
                    @error('codigo')
                    <div class="form-error-text">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Seção: Remetente & Destinatário --}}
        <div class="form-section">
            <div class="form-section-label">Remetente & Destinatário</div>
            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label">Remetente *</label>
                    <input
                        type="text"
                        name="fabricante_fornecedor"
                        value="{{ old('fabricante_fornecedor', $p?->fabricante_fornecedor) }}"
                        class="form-input @error('fabricante_fornecedor') has-error @enderror"
                        placeholder="Ex: Mercado Livre">
                    @error('fabricante_fornecedor')
                    <div class="form-error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Destinatário *</label>
                    <input
                        type="text"
                        name="destinatario"
                        value="{{ old('destinatario', $p?->destinatario) }}"
                        class="form-input @error('destinatario') has-error @enderror"
                        placeholder="Ex: João Silva">
                    @error('destinatario')
                    <div class="form-error-text">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Seção: Detalhes --}}
        <div class="form-section">
            <div class="form-section-label">Detalhes</div>
            <div class="form-grid cols-3">

                <div class="form-group">
                    <label class="form-label">Preço *</label>
                    <div class="form-input-wrap">
                        <span class="form-input-prefix">R$</span>
                        <input
                            type="number"
                            name="preco"
                            value="{{ old('preco', $p?->preco) }}"
                            step="0.01"
                            min="0"
                            class="form-input @error('preco') has-error @enderror"
                            placeholder="0,00">
                    </div>
                    @error('preco')
                    <div class="form-error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Peso *</label>
                    <div class="form-input-wrap has-suffix">
                        <input
                            type="number"
                            name="peso"
                            value="{{ old('peso', $p?->peso) }}"
                            step="0.001"
                            min="0.01"
                            class="form-input @error('peso') has-error @enderror"
                            placeholder="0,010">
                        <span class="form-input-suffix">kg</span>
                    </div>
                    <div class="form-help">Mínimo: 0,010 kg</div>
                    @error('peso')
                    <div class="form-error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Quantidade *</label>
                    <input
                        type="number"
                        name="quantidade"
                        value="{{ old('quantidade', $p?->quantidade ?? 1) }}"
                        min="1"
                        class="form-input @error('quantidade') has-error @enderror">
                    @error('quantidade')
                    <div class="form-error-text">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Seção: Status --}}
        <div class="form-section">
            <div class="form-section-label">Status</div>
            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label">Status do Pacote *</label>
                    <div class="form-select-wrap">
                        <select
                            name="status"
                            class="form-select @error('status') has-error @enderror">
                            @foreach(['Pendente', 'Em Rota', 'Entregue'] as $s)
                            <option value="{{ $s }}" {{ old('status', $p?->status ?? 'Pendente') == $s ? 'selected' : '' }}>
                                {{ $s }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @error('status')
                    <div class="form-error-text">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

    </div>
</div>