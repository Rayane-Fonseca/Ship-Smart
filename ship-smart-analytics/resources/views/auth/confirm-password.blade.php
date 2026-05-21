<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-extrabold text-gray-800">Ship-Smart</h1>
        <p class="text-sm text-gray-500 mt-2">
            Confirme sua senha para continuar.
        </p>
    </div>

    <div class="mb-4 text-sm text-gray-600">
        {{ __('Esta é uma área segura do sistema. Confirme sua senha antes de continuar.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Senha')" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Confirmar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>