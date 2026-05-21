# Ship-Smart Analytics — Guia do Projeto Laravel

**Cliente:** Fernanda Oliveira — Gerente de Fulfillment  
**Contexto:** Conferente de centro de triagem (Cajamar, SP)  
**Necessidade:** Rastreamento de Pacotes  
**Cor primária:** #DB2777 (Rosa escuro)  
**Layout:** Completo (menu superior + lateral)

---

## Índice

1. [Instalação e Configuração](#1-instalação-e-configuração)
2. [Estrutura de Arquivos](#2-estrutura-de-arquivos)
3. [Banco de Dados (Migrations)](#3-banco-de-dados)
4. [Models](#4-models)
5. [Controllers](#5-controllers)
6. [Views (Blade)](#6-views)
7. [Rotas](#7-rotas)
8. [Como Executar os Testes](#8-testes)

---

## 1. Instalação e Configuração

```bash
# 1. Criar o projeto Laravel
composer create-project laravel/laravel ship-smart-analytics
cd ship-smart-analytics

# 2. Instalar Laravel Breeze (autenticação simples)
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run dev

# 3. Configurar .env
cp .env.example .env
php artisan key:generate
```

Edite o arquivo `.env`:

```env
APP_NAME="Ship-Smart Analytics"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ship_smart
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

```bash
# 4. Criar banco e executar migrations
php artisan migrate

# 5. Executar o servidor
php artisan serve
```

---

## 2. Estrutura de Arquivos

```
ship-smart-analytics/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/                  (gerado pelo Breeze)
│   │   ├── DashboardController.php
│   │   ├── PacoteController.php
│   │   └── UsuarioController.php
│   └── Models/
│       ├── User.php
│       └── Pacote.php
├── database/
│   ├── migrations/
│   │   ├── ..._create_users_table.php    (Breeze)
│   │   └── ..._create_pacotes_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php          (layout completo)
│   ├── dashboard.blade.php
│   ├── pacotes/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   └── auth/                      (gerado pelo Breeze)
└── routes/
    └── web.php
```

---

## 3. Banco de Dados

### Migration: Pacotes

**Arquivo:** `database/migrations/YYYY_MM_DD_create_pacotes_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacotes', function (Blueprint $table) {
            $table->id();
            // Campos obrigatórios (conforme briefing)
            $table->string('nome');
            $table->string('codigo')->unique();          // Código de rastreio
            $table->string('fabricante_fornecedor');     // Remetente
            $table->decimal('preco', 10, 2);
            $table->integer('quantidade');

            // Campos específicos Ship-Smart
            $table->string('destinatario');
            $table->decimal('peso', 8, 3);               // em kg
            $table->enum('status', ['Pendente', 'Em Rota', 'Entregue'])
                  ->default('Pendente');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacotes');
    }
};
```

**Como criar a migration:**
```bash
php artisan make:migration create_pacotes_table
# Cole o código acima no arquivo gerado
php artisan migrate
```

---

## 4. Models

### Model: Pacote

**Arquivo:** `app/Models/Pacote.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pacote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'codigo',
        'fabricante_fornecedor',
        'preco',
        'quantidade',
        'destinatario',
        'peso',
        'status',
    ];

    protected $casts = [
        'preco'      => 'decimal:2',
        'peso'       => 'decimal:3',
        'quantidade' => 'integer',
    ];

    // Regra de Negócio: Peso mínimo (0.01 kg)
    public function isPesoValido(): bool
    {
        return $this->peso >= 0.01;
    }

    // Scopes de filtro por status
    public function scopePendente($query)
    {
        return $query->where('status', 'Pendente');
    }

    public function scopeEmRota($query)
    {
        return $query->where('status', 'Em Rota');
    }

    public function scopeEntregue($query)
    {
        return $query->where('status', 'Entregue');
    }
}
```

---

## 5. Controllers

### DashboardController

**Arquivo:** `app/Http/Controllers/DashboardController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Pacote;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total'    => Pacote::count(),
            'pendente' => Pacote::pendente()->count(),
            'em_rota'  => Pacote::emRota()->count(),
            'entregue' => Pacote::entregue()->count(),
        ];

        $recentes = Pacote::latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentes'));
    }
}
```

### PacoteController (CRUD Completo)

**Arquivo:** `app/Http/Controllers/PacoteController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Pacote;
use Illuminate\Http\Request;

class PacoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Pacote::query();

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Busca por código ou destinatário
        if ($request->filled('busca')) {
            $query->where(function ($q) use ($request) {
                $q->where('codigo', 'like', '%' . $request->busca . '%')
                  ->orWhere('destinatario', 'like', '%' . $request->busca . '%')
                  ->orWhere('nome', 'like', '%' . $request->busca . '%');
            });
        }

        $pacotes = $query->latest()->paginate(10);

        return view('pacotes.index', compact('pacotes'));
    }

    public function create()
    {
        return view('pacotes.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome'                 => 'required|string|max:255',
            'codigo'               => 'required|string|max:100|unique:pacotes',
            'fabricante_fornecedor'=> 'required|string|max:255',
            'preco'                => 'required|numeric|min:0',
            'quantidade'           => 'required|integer|min:1',
            'destinatario'         => 'required|string|max:255',
            'peso'                 => 'required|numeric|min:0.01',
            'status'               => 'required|in:Pendente,Em Rota,Entregue',
        ], [
            'codigo.unique'        => 'Este código de rastreio já está cadastrado.',
            'peso.min'             => 'O peso mínimo é 0,01 kg.',
            'quantidade.min'       => 'A quantidade deve ser pelo menos 1.',
        ]);

        Pacote::create($dados);

        return redirect()->route('pacotes.index')
                         ->with('sucesso', 'Pacote cadastrado com sucesso!');
    }

    public function show(Pacote $pacote)
    {
        return view('pacotes.show', compact('pacote'));
    }

    public function edit(Pacote $pacote)
    {
        return view('pacotes.edit', compact('pacote'));
    }

    public function update(Request $request, Pacote $pacote)
    {
        $dados = $request->validate([
            'nome'                 => 'required|string|max:255',
            'codigo'               => 'required|string|max:100|unique:pacotes,codigo,' . $pacote->id,
            'fabricante_fornecedor'=> 'required|string|max:255',
            'preco'                => 'required|numeric|min:0',
            'quantidade'           => 'required|integer|min:1',
            'destinatario'         => 'required|string|max:255',
            'peso'                 => 'required|numeric|min:0.01',
            'status'               => 'required|in:Pendente,Em Rota,Entregue',
        ]);

        $pacote->update($dados);

        return redirect()->route('pacotes.index')
                         ->with('sucesso', 'Pacote atualizado com sucesso!');
    }

    public function destroy(Pacote $pacote)
    {
        $pacote->delete();

        return redirect()->route('pacotes.index')
                         ->with('sucesso', 'Pacote removido com sucesso!');
    }
}
```

---

## 6. Views (Blade)

### Layout Principal

**Arquivo:** `resources/views/layouts/app.blade.php`

```html
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ship-Smart Analytics</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --primary: #DB2777; --primary-dark: #9D174D; }
        .sidebar { width: 240px; background: #1f2937; min-height: calc(100vh - 64px); }
        .nav-top  { background: var(--primary); height: 64px; }
        .nav-link { color: #d1d5db; padding: .6rem 1rem; display: block; border-radius: .375rem; }
        .nav-link:hover, .nav-link.active { background: var(--primary); color: #fff; }
        .badge-pendente { background:#fef3c7; color:#92400e; }
        .badge-emrota   { background:#dbeafe; color:#1e40af; }
        .badge-entregue { background:#d1fae5; color:#065f46; }
    </style>
</head>
<body class="bg-gray-100">

    {{-- MENU SUPERIOR --}}
    <nav class="nav-top flex items-center justify-between px-6 fixed top-0 w-full z-50">
        <div class="flex items-center gap-3">
            <span class="text-white font-bold text-lg tracking-wide">📦 Ship-Smart Analytics</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-pink-100 text-sm">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-pink-200 hover:text-white text-sm">Sair</button>
            </form>
        </div>
    </nav>

    <div class="flex pt-16">

        {{-- MENU LATERAL --}}
        <aside class="sidebar fixed left-0 top-16 h-full p-4 flex flex-col gap-1">
            <p class="text-gray-500 text-xs uppercase tracking-widest px-3 mb-2">Navegação</p>
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                🏠 Dashboard
            </a>
            <a href="{{ route('pacotes.index') }}"
               class="nav-link {{ request()->routeIs('pacotes.*') ? 'active' : '' }}">
                📦 Pacotes
            </a>
            <a href="{{ route('pacotes.create') }}" class="nav-link">
                ➕ Novo Pacote
            </a>
        </aside>

        {{-- CONTEÚDO PRINCIPAL --}}
        <main class="ml-60 flex-1 p-8">
            @if(session('sucesso'))
                <div class="bg-green-100 border border-green-400 text-green-800
                            rounded px-4 py-3 mb-4">
                    {{ session('sucesso') }}
                </div>
            @endif

            @yield('content')
        </main>

    </div>
</body>
</html>
```

### Dashboard

**Arquivo:** `resources/views/dashboard.blade.php`

```html
@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

{{-- Cards de Estatísticas --}}
<div class="grid grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-pink-600">
        <p class="text-gray-500 text-sm">Total de Pacotes</p>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-yellow-400">
        <p class="text-gray-500 text-sm">Pendentes</p>
        <p class="text-3xl font-bold text-yellow-600">{{ $stats['pendente'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
        <p class="text-gray-500 text-sm">Em Rota</p>
        <p class="text-3xl font-bold text-blue-600">{{ $stats['em_rota'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
        <p class="text-gray-500 text-sm">Entregues</p>
        <p class="text-3xl font-bold text-green-600">{{ $stats['entregue'] }}</p>
    </div>
</div>

{{-- Tabela de Pacotes Recentes --}}
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h2 class="font-semibold text-gray-700">Pacotes Recentes</h2>
        <a href="{{ route('pacotes.index') }}" class="text-pink-600 text-sm hover:underline">Ver todos</a>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">Código</th>
                <th class="px-6 py-3 text-left">Destinatário</th>
                <th class="px-6 py-3 text-left">Peso</th>
                <th class="px-6 py-3 text-left">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($recentes as $pacote)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3 font-mono font-medium text-pink-700">{{ $pacote->codigo }}</td>
                <td class="px-6 py-3">{{ $pacote->destinatario }}</td>
                <td class="px-6 py-3">{{ number_format($pacote->peso, 3) }} kg</td>
                <td class="px-6 py-3">
                    @php
                        $badge = match($pacote->status) {
                            'Pendente' => 'badge-pendente',
                            'Em Rota'  => 'badge-emrota',
                            'Entregue' => 'badge-entregue',
                        };
                    @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                        {{ $pacote->status }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">Nenhum pacote cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
```

### Listar Pacotes (Index)

**Arquivo:** `resources/views/pacotes/index.blade.php`

```html
@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Pacotes</h1>
    <a href="{{ route('pacotes.create') }}"
       class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg text-sm">
        + Novo Pacote
    </a>
</div>

{{-- Filtros --}}
<form method="GET" action="{{ route('pacotes.index') }}"
      class="bg-white rounded-lg shadow p-4 mb-6 flex gap-3 items-end">
    <div class="flex-1">
        <label class="text-xs text-gray-500 mb-1 block">Buscar</label>
        <input type="text" name="busca" value="{{ request('busca') }}"
               placeholder="Código, destinatário ou nome..."
               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-400">
    </div>
    <div>
        <label class="text-xs text-gray-500 mb-1 block">Status</label>
        <select name="status" class="border rounded px-3 py-2 text-sm">
            <option value="">Todos</option>
            <option value="Pendente"  {{ request('status') == 'Pendente'  ? 'selected' : '' }}>Pendente</option>
            <option value="Em Rota"   {{ request('status') == 'Em Rota'   ? 'selected' : '' }}>Em Rota</option>
            <option value="Entregue"  {{ request('status') == 'Entregue'  ? 'selected' : '' }}>Entregue</option>
        </select>
    </div>
    <button type="submit"
            class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded text-sm">
        Filtrar
    </button>
    <a href="{{ route('pacotes.index') }}" class="text-gray-400 text-sm py-2">Limpar</a>
</form>

{{-- Tabela --}}
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">Código</th>
                <th class="px-6 py-3 text-left">Nome</th>
                <th class="px-6 py-3 text-left">Remetente</th>
                <th class="px-6 py-3 text-left">Destinatário</th>
                <th class="px-6 py-3 text-left">Peso</th>
                <th class="px-6 py-3 text-left">Qtd</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-left">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pacotes as $pacote)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3 font-mono font-semibold text-pink-700">{{ $pacote->codigo }}</td>
                <td class="px-6 py-3">{{ $pacote->nome }}</td>
                <td class="px-6 py-3">{{ $pacote->fabricante_fornecedor }}</td>
                <td class="px-6 py-3">{{ $pacote->destinatario }}</td>
                <td class="px-6 py-3">{{ number_format($pacote->peso, 3) }} kg</td>
                <td class="px-6 py-3">{{ $pacote->quantidade }}</td>
                <td class="px-6 py-3">
                    @php
                        $badge = match($pacote->status) {
                            'Pendente' => 'badge-pendente',
                            'Em Rota'  => 'badge-emrota',
                            'Entregue' => 'badge-entregue',
                        };
                    @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                        {{ $pacote->status }}
                    </span>
                </td>
                <td class="px-6 py-3 flex gap-2">
                    <a href="{{ route('pacotes.show', $pacote) }}"
                       class="text-blue-500 hover:underline text-xs">Ver</a>
                    <a href="{{ route('pacotes.edit', $pacote) }}"
                       class="text-yellow-500 hover:underline text-xs">Editar</a>
                    <form method="POST" action="{{ route('pacotes.destroy', $pacote) }}"
                          onsubmit="return confirm('Confirmar exclusão?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline text-xs">Excluir</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">Nenhum pacote encontrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t">{{ $pacotes->withQueryString()->links() }}</div>
</div>
@endsection
```

### Formulário (Create / Edit)

**Arquivo:** `resources/views/pacotes/create.blade.php`

```html
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Novo Pacote</h1>

    <form method="POST" action="{{ route('pacotes.store') }}"
          class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf

        @include('pacotes._form')

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded-lg">
                Salvar
            </button>
            <a href="{{ route('pacotes.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
```

### Formulário parcial (reutilizável)

**Arquivo:** `resources/views/pacotes/_form.blade.php`

```html
@php $p = $pacote ?? null; @endphp

{{-- Erros de validação --}}
@if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded p-3 text-sm text-red-700">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $erro) <li>{{ $erro }}</li> @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Pacote *</label>
        <input type="text" name="nome" value="{{ old('nome', $p?->nome) }}"
               class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-pink-400 @error('nome') border-red-500 @enderror">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Código de Rastreio *</label>
        <input type="text" name="codigo" value="{{ old('codigo', $p?->codigo) }}"
               class="w-full border rounded px-3 py-2 text-sm font-mono focus:ring-2 focus:ring-pink-400 @error('codigo') border-red-500 @enderror">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Remetente (Fabricante/Fornecedor) *</label>
        <input type="text" name="fabricante_fornecedor" value="{{ old('fabricante_fornecedor', $p?->fabricante_fornecedor) }}"
               class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-pink-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Destinatário *</label>
        <input type="text" name="destinatario" value="{{ old('destinatario', $p?->destinatario) }}"
               class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-pink-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Preço (R$) *</label>
        <input type="number" name="preco" value="{{ old('preco', $p?->preco) }}"
               step="0.01" min="0"
               class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-pink-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade *</label>
        <input type="number" name="quantidade" value="{{ old('quantidade', $p?->quantidade ?? 1) }}"
               min="1"
               class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-pink-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Peso (kg) *</label>
        <input type="number" name="peso" value="{{ old('peso', $p?->peso) }}"
               step="0.001" min="0.01"
               class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-pink-400">
        <p class="text-xs text-gray-400 mt-1">Mínimo: 0,010 kg</p>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
        <select name="status" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-pink-400">
            @foreach(['Pendente', 'Em Rota', 'Entregue'] as $s)
                <option value="{{ $s }}" {{ old('status', $p?->status ?? 'Pendente') == $s ? 'selected' : '' }}>
                    {{ $s }}
                </option>
            @endforeach
        </select>
    </div>
</div>
```

### Editar Pacote

**Arquivo:** `resources/views/pacotes/edit.blade.php`

```html
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Editar Pacote — <span class="font-mono text-pink-600">{{ $pacote->codigo }}</span>
    </h1>

    <form method="POST" action="{{ route('pacotes.update', $pacote) }}"
          class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf @method('PUT')

        @include('pacotes._form')

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded-lg">
                Atualizar
            </button>
            <a href="{{ route('pacotes.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
```

### Detalhes do Pacote (Show)

**Arquivo:** `resources/views/pacotes/show.blade.php`

```html
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detalhes do Pacote</h1>
        <a href="{{ route('pacotes.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">← Voltar</a>
    </div>

    <div class="bg-white rounded-lg shadow p-6 space-y-4">
        @php
            $badge = match($pacote->status) {
                'Pendente' => 'badge-pendente',
                'Em Rota'  => 'badge-emrota',
                'Entregue' => 'badge-entregue',
            };
        @endphp
        <div class="flex justify-between items-center pb-4 border-b">
            <span class="font-mono text-2xl font-bold text-pink-600">{{ $pacote->codigo }}</span>
            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $badge }}">{{ $pacote->status }}</span>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-gray-500">Nome</p><p class="font-medium">{{ $pacote->nome }}</p></div>
            <div><p class="text-gray-500">Remetente</p><p class="font-medium">{{ $pacote->fabricante_fornecedor }}</p></div>
            <div><p class="text-gray-500">Destinatário</p><p class="font-medium">{{ $pacote->destinatario }}</p></div>
            <div><p class="text-gray-500">Peso</p><p class="font-medium">{{ number_format($pacote->peso, 3) }} kg</p></div>
            <div><p class="text-gray-500">Preço</p><p class="font-medium">R$ {{ number_format($pacote->preco, 2, ',', '.') }}</p></div>
            <div><p class="text-gray-500">Quantidade</p><p class="font-medium">{{ $pacote->quantidade }}</p></div>
            <div><p class="text-gray-500">Criado em</p><p class="font-medium">{{ $pacote->created_at->format('d/m/Y H:i') }}</p></div>
            <div><p class="text-gray-500">Atualizado em</p><p class="font-medium">{{ $pacote->updated_at->format('d/m/Y H:i') }}</p></div>
        </div>

        <div class="flex gap-3 pt-4 border-t">
            <a href="{{ route('pacotes.edit', $pacote) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm">
                Editar
            </a>
            <form method="POST" action="{{ route('pacotes.destroy', $pacote) }}"
                  onsubmit="return confirm('Confirmar exclusão?')">
                @csrf @method('DELETE')
                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm">
                    Excluir
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
```

---

## 7. Rotas

**Arquivo:** `routes/web.php`

```php
<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PacoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

// Rotas protegidas por autenticação
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('pacotes', PacoteController::class);
});

// Rotas de auth geradas pelo Breeze
require __DIR__.'/auth.php';
```

---

## 8. Testes

### Criar o arquivo de teste

```bash
php artisan make:test PacoteTest
```

**Arquivo:** `tests/Feature/PacoteTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\Pacote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PacoteTest extends TestCase
{
    use RefreshDatabase;

    private function autenticar(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    public function test_dashboard_carrega_para_usuario_autenticado(): void
    {
        $this->autenticar();
        $this->get(route('dashboard'))->assertStatus(200);
    }

    public function test_listagem_de_pacotes(): void
    {
        $this->autenticar();
        Pacote::factory()->count(3)->create();
        $this->get(route('pacotes.index'))->assertStatus(200);
    }

    public function test_cadastro_de_pacote_com_dados_validos(): void
    {
        $this->autenticar();

        $response = $this->post(route('pacotes.store'), [
            'nome'                  => 'Caixa Teste',
            'codigo'                => 'SSA-00001',
            'fabricante_fornecedor' => 'Fornecedor Teste',
            'preco'                 => 99.90,
            'quantidade'            => 1,
            'destinatario'          => 'João Silva',
            'peso'                  => 2.500,
            'status'                => 'Pendente',
        ]);

        $response->assertRedirect(route('pacotes.index'));
        $this->assertDatabaseHas('pacotes', ['codigo' => 'SSA-00001']);
    }

    public function test_cadastro_falha_com_codigo_duplicado(): void
    {
        $this->autenticar();
        Pacote::factory()->create(['codigo' => 'SSA-DUPLO']);

        $this->post(route('pacotes.store'), [
            'nome'                  => 'Outro',
            'codigo'                => 'SSA-DUPLO',
            'fabricante_fornecedor' => 'Forn',
            'preco'                 => 10,
            'quantidade'            => 1,
            'destinatario'          => 'Maria',
            'peso'                  => 1.0,
            'status'                => 'Pendente',
        ])->assertSessionHasErrors('codigo');
    }

    public function test_regra_peso_minimo(): void
    {
        $this->autenticar();

        $this->post(route('pacotes.store'), [
            'nome'                  => 'Caixa',
            'codigo'                => 'SSA-00002',
            'fabricante_fornecedor' => 'Forn',
            'preco'                 => 10,
            'quantidade'            => 1,
            'destinatario'          => 'Carlos',
            'peso'                  => 0.00,        // Inválido — abaixo do mínimo
            'status'                => 'Pendente',
        ])->assertSessionHasErrors('peso');
    }

    public function test_atualizacao_de_status(): void
    {
        $this->autenticar();
        $pacote = Pacote::factory()->create(['status' => 'Pendente']);

        $this->put(route('pacotes.update', $pacote), array_merge(
            $pacote->toArray(),
            ['status' => 'Entregue']
        ));

        $this->assertDatabaseHas('pacotes', [
            'id'     => $pacote->id,
            'status' => 'Entregue',
        ]);
    }

    public function test_exclusao_de_pacote(): void
    {
        $this->autenticar();
        $pacote = Pacote::factory()->create();

        $this->delete(route('pacotes.destroy', $pacote))
             ->assertRedirect(route('pacotes.index'));

        $this->assertSoftDeleted('pacotes', ['id' => $pacote->id]);
    }
}
```

### Factory do Pacote

```bash
php artisan make:factory PacoteFactory --model=Pacote
```

**Arquivo:** `database/factories/PacoteFactory.php`

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PacoteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome'                  => $this->faker->words(3, true),
            'codigo'                => 'SSA-' . strtoupper($this->faker->bothify('??####')),
            'fabricante_fornecedor' => $this->faker->company(),
            'preco'                 => $this->faker->randomFloat(2, 5, 500),
            'quantidade'            => $this->faker->numberBetween(1, 50),
            'destinatario'          => $this->faker->name(),
            'peso'                  => $this->faker->randomFloat(3, 0.1, 30),
            'status'                => $this->faker->randomElement(['Pendente', 'Em Rota', 'Entregue']),
        ];
    }
}
```

### Executar os testes

```bash
php artisan test
# ou somente o arquivo específico:
php artisan test --filter PacoteTest
```

---

## Comandos Resumidos

```bash
# Instalação completa
composer create-project laravel/laravel ship-smart-analytics
cd ship-smart-analytics
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run dev

# Banco de dados
php artisan make:migration create_pacotes_table
php artisan migrate

# Arquivos do CRUD
php artisan make:model Pacote
php artisan make:controller PacoteController --resource
php artisan make:controller DashboardController
php artisan make:factory PacoteFactory --model=Pacote
php artisan make:test PacoteTest

# Iniciar
php artisan serve
```
