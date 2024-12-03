<section>
    <header>
        <h2 id="banner-text" class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Informações do Perfil') }}
        </h2>

        <p id="banner-text" class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Atualize as informações de perfil e endereço de e-mail da sua conta.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form id="profile-form" method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Nome -->
        <div>
            <x-input-label id="banner-text" for="name" :value="__('Nome *')" />
            <x-text-input id="name" name="name" type="text"
                class="mt-1 block w-full required {{ $errors->has('name') ? 'border-red-500' : '' }}"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- CPF/CNPJ -->
        <div>
            <x-input-label id="banner-text" for="cpfCnpj" :value="__('CPF/CNPJ')" />
            <x-text-input class="mt-1 block w-full bg-gray-100 text-gray-500 cursor-not-allowed" disabled readonly
                id="cpfCnpj" name="cpfCnpj" type="text" value="{{ old('cpfCnpj', $user->cpf_cnpj) }}" />
            <x-input-error class="mt-2" :messages="$errors->get('cpfCnpj')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label id="banner-text" for="email" :value="__('Email *')" />
            <x-text-input id="email" name="email" type="email"
                class="mt-1 block w-full required {{ $errors->has('email') ? 'border-red-500' : '' }}"
                value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <!-- Estado -->
        <div>
            <x-input-label id="banner-text" for="state" :value="__('Estado *')" />
            <select id="state" name="state"
                class="block mt-1 w-full {{ $errors->has('state') ? 'border-red-500' : '' }}"
                data-selected-state="{{ old('state', $user->state->abbreviation ?? '') }}">
                <option value="" disabled {{ old('state', $user->state_id) ? '' : 'selected' }}>Selecione um
                    estado</option>
                @foreach ($states as $state)
                    <option value="{{ $state->abbreviation }}"
                        {{ old('state', $user->state->abbreviation ?? '') == $state->abbreviation ? 'selected' : '' }}>
                        {{ $state->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('state')" />
        </div>

        <!-- Cidade -->
        <div>
            <x-input-label id="banner-text" for="city" :value="__('Cidade *')" />
            <select id="city" name="city"
                class="block mt-1 w-full {{ $errors->has('city') ? 'border-red-500' : '' }}"
                data-selected-city="{{ old('city', $user->city->ibge_code ?? '') }}"
                {{ !$user->city_id ? 'disabled' : '' }}>
                <option value="" disabled {{ old('city', $user->city_id) ? '' : 'selected' }}>Selecione uma
                    cidade</option>
                @foreach ($cities as $city)
                    <option value="{{ $city->ibge_code }}"
                        {{ old('city', $user->city->ibge_code ?? '') == $city->ibge_code ? 'selected' : '' }}>
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
        </div>

        <!-- CEP -->
        <div>
            <x-input-label id="banner-text" for="zip_code" :value="__('CEP *')" />
            <x-text-input id="zip_code" name="zip_code" type="text" maxlength="9"
                class="mt-1 block w-full required {{ $errors->has('zip_code') ? 'border-red-500' : '' }}"
                placeholder="00000-000" value="{{ old('zip_code', $user->zip_code) }}" />
            <x-input-error class="mt-2" :messages="$errors->get('zip_code')" />
        </div>

        <!-- Endereço -->
        <div>
            <x-input-label id="banner-text" for="address" :value="__('Endereço *')" />
            <x-text-input id="address" name="address" type="text"
                class="mt-1 block w-full required {{ $errors->has('address') ? 'border-red-500' : '' }}"
                placeholder="Rua Exemplo" value="{{ old('address', $user->address) }}" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <!-- Número -->
        <div>
            <x-input-label id="banner-text" for="house_number" :value="__('Número *')" />
            <x-text-input id="house_number" name="house_number" type="text"
                class="mt-1 block w-full required {{ $errors->has('house_number') ? 'border-red-500' : '' }}"
                placeholder="Número" value="{{ old('house_number', $user->house_number) }}" />
            <x-input-error class="mt-2" :messages="$errors->get('house_number')" />
        </div>

        <!-- Complemento -->
        <div>
            <x-input-label id="banner-text" for="complement" :value="__('Complemento')" />
            <x-text-input id="complement" name="complement" type="text" class="mt-1 block w-full"
                placeholder="Apartamento, Bloco, etc." value="{{ old('complement', $user->complement) }}" />
            <x-input-error class="mt-2" :messages="$errors->get('complement')" />
        </div>

        <!-- Bairro -->
        <div>
            <x-input-label id="banner-text" for="neighborhood" :value="__('Bairro *')" />
            <x-text-input id="neighborhood" name="neighborhood" type="text"
                class="mt-1 block w-full required {{ $errors->has('neighborhood') ? 'border-red-500' : '' }}"
                placeholder="Bairro" value="{{ old('neighborhood', $user->neighborhood) }}" />
            <x-input-error class="mt-2" :messages="$errors->get('neighborhood')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Salvar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Salvo.') }}</p>
            @endif
        </div>
    </form>
</section>
