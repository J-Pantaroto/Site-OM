
@extends('emails.layout')

@section('content')
    # Verifique seu E-mail

    Olá,

    Por favor, clique no botão abaixo para verificar seu endereço de e-mail:
    <div style="text-align: center;">
        <a class="btn button-primary" href="{{ $verifyUrl }}"
        style="display: inline-block; padding: 10px 20px; font-size: 16px; text-decoration: none; border-radius: 5px; margin: 10px 0;">
            {{ __('Verificar E-mail') }}
        </a>
    </div>
    Se você não criou uma conta, ignore este e-mail.

    Obrigado,<br>
    {{ config('app.name') }}
@endsection
