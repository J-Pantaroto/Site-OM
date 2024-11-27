<section>
    <header>
        <h2 id="banner-text" class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Informações do Perfil') }}
        </h2>

        <p id="banner-text" class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Atualize as informações de perfil e endereço de e-mail da sua conta.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Nome -->
        <div>
            <x-input-label id="banner-text" for="name" :value="__('Nome')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- CPF/CNPJ -->
        <div>
            <x-input-label id="banner-text" for="cpfCnpj" :value="__('CPF/CNPJ')" />
            <x-text-input class="mt-1 block w-full bg-gray-100 text-gray-500 cursor-not-allowed" disabled readonly id="cpfCnpj" name="cpfCnpj" type="text" :value="old('cpfCnpj', $user->cpf_cnpj)" />
            <x-input-error class="mt-2" :messages="$errors->get('cpfCnpj')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label id="banner-text" for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <!-- Estado -->
        <div>
            <x-input-label id="banner-text" for="state" :value="__('Estado')" />
            <select id="state" name="state" class="block mt-1 w-full">
                <option value="" disabled {{ !$user->state_id ? 'selected' : '' }}>Selecione um estado</option>
                @foreach ($states as $state)
                    <option value="{{ $state->id }}" {{ $user->state_id == $state->id ? 'selected' : '' }}>
                        {{ $state->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('state')" />
        </div>

        <!-- Cidade -->
        <div>
            <x-input-label id="banner-text" for="city" :value="__('Cidade')" />
            <select id="city" name="city" class="block mt-1 w-full" {{ !$user->city_id ? 'disabled' : '' }}>
                <option value="" disabled {{ !$user->city_id ? 'selected' : '' }}>Selecione uma cidade</option>
                @if ($user->state_id)
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}" {{ $user->city_id == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                @endif
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Salvar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Salvo.') }}</p>
            @endif
        </div>
    </form>
</section>
