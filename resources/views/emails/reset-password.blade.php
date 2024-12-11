@extends('emails.layout')

@section('content')
<h1>Redefinir Senha</h1>

<p>Olá,<br>

Clique no botão abaixo para redefinir sua senha:</p>

<div style="text-align: center;">
    <a class="btn button-primary" href="{{ $resetUrl }}" style="display: inline-block; padding: 10px 20px; font-size: 16px; text-decoration: none; border-radius: 5px; margin: 10px 0;">
        {{ __('Redefinir Senha') }}
    </a>
</div>

<p>Se você não solicitou a redefinição de senha, ignore este e-mail.</p>

<p>Obrigado,<br>
{{ config('app.name') }}</p>
@endsection
