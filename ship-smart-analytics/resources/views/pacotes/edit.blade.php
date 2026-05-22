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
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg">
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