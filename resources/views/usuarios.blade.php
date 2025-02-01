<x-app-layout>
    <x-slot name="header">
        <h2 id="banner-text" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Usuários') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div id="success-message" style="display: none;">{{ session('success') }}</div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="banner" class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <x-barra-pesquisa name="pesquisa">
                    {{ __('Digite para pesquisar um usuário...') }}
                </x-barra-pesquisa>
                <div class="table-responsive mt-4">
                    <table class="table">
                        <thead class="table-dark">
                            <tr>
                                <th class="tablebackground" scope="col">ID</th>
                                <th class="tablebackground" scope="col">Nome</th>
                                <th class="tablebackground" scope="col">Email</th>
                                <th class="tablebackground" scope="col">CPF/CNPJ</th>
                                <th class="tablebackground" scope="col">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($usuarios->isEmpty())
                                <tr>
                                    <td colspan="5"> Nenhum usuário cadastrado.</td>
                                </tr>
                            @else
                                @foreach ($usuarios as $usuario)
                                    <tr>
                                        <th scope="row">{{ $usuario->id }}</th>
                                        <td>{{ $usuario->name }}</td>
                                        <td>{{ $usuario->email }}</td>
                                        <td>{{ $usuario->cpf_cnpj }}</td>
                                        <td>
                                            @if (auth()->check() && auth()->user()->isSupervisor())
                                                <a href="{{ route('profile.definir', $usuario->id) }}"
                                                    class="btn btn-outline-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="dark"
                                                        class="bi bi-pen" viewBox="0 0 16 16">
                                                        <path
                                                            d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z" />
                                                    </svg> </a>
                                            @endif
                                            <form style="display:inline" action="{{ route('usuarios.destroy', $usuario->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Tem certeza que deseja excluir o acesso deste usuário?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" id="excluir">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="dark"
                                                        class="bi bi-trash3" viewBox="0 0 16 16">
                                                        <path
                                                            d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div id="paginacao" class="d-flex justify-content-end">
                    {{ $usuarios->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        const isSupervisor = {{ auth()->check() && auth()->user()->isSupervisor() ? 'true' : 'false' }};
    </script>
</x-app-layout>