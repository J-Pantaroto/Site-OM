<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('css/bootstrap.min.css') }}">
    <title>{{ config('app.name', 'Mercado das Latas') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        body {
            background-color: #24252a;
            font-family: Arial, sans-serif;
        }
        .card {
            border-radius: 10px;
            border: none;
        }
        .alert {
            border-radius: 5px;
        }
    </style>
</head>

<body>
    @section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4"> <!-- Ajusta a largura em telas maiores -->
                <div class="card shadow-lg"> <!-- Sombra maior -->
                    <div class="card-header text-center">
                        <h2 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" height="50" width="50" viewBox="0 0 512 512"><path fill="#00a876" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg></h2>
                    </div>
                    <div class="card-body">
                        @if(isset($message))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        @else
                            <div class="alert alert-info">
                                Nenhuma mensagem foi fornecida.
                            </div>
                        @endif

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="btn btn-warning">Voltar para Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
