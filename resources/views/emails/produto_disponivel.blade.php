@extends('emails.layout')
@section('content')
# O produto {{ $produtoNome }} está de volta ao estoque! 🎉

Agora você pode comprá-lo antes que acabe novamente.

@component('mail::button', ['url' => $produtoLink])
Ver Produto
@endcomponent

Obrigado,<br>
@endsection