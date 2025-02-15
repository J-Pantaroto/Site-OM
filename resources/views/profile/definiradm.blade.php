<x-app-layout>
    <x-slot name="header">
        <h2 id="banner-text" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Alteração de usuários') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Fechar"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.updateRole', $usuario->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Usuário</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="{{ $usuario->name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $usuario->email }}" readonly>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Definir Permissões</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="admin" id="admin" value="1"
                                    {{ $usuario->isAdmin() ? 'checked' : '' }}>
                                <label class="form-check-label" for="admin">
                                    Administrador
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="supervisor" id="supervisor" value="1"
                                    {{ $usuario->isSupervisor() ? 'checked' : '' }}>
                                <label class="form-check-label" for="supervisor">
                                    Supervisor
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">{{ __('Salvar') }}</button>
                            <a href="{{ route('usuarios') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
