<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            color: yellow;
            padding: 50px;
            background-color: #24252a;
        }
        h1 {
            font-size: 2em;
            color: yellow;
        }
        img {

        }
    </style>
</head>
<body>
    <img src="{{ asset('images/404.png') }}" style="width:40vh;"alt="">
    <h1>404 - Página Não Encontrada</h1>
    <p>A página que você está procurando não existe.</p>
    <p>Isso pode acontecer caso você esteja informando endereços invalidos na URL =(</p>
</body>
</html>
