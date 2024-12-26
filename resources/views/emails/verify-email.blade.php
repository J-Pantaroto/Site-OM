
@extends('emails.layout')

@section('content')
    <h2>Verifique seu E-mail</h2>
    <p>Olá</p>
    <p>Por favor, clique no botão abaixo para verificar seu endereço de e-mail:</p>
    <div style="text-align: center;">
        <a class="btn button-primary" href="{{ $verificationUrl }}" style="display: inline-block; padding: 10px 20px; font-size: 16px; text-decoration: none; border-radius: 5px; margin: 10px 0;"> {{ __('Verificar E-mail') }}</a>
    </div>
    <p>Se você não criou uma conta, ignore este e-mail.</p>
    <p>Obrigado</p>
    {{ config('app.name') }}
@endsection
