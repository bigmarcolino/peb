<!DOCTYPE html>

<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/png" href="/img/favicon.png"/>
        <meta name="theme-color" content="#1a6f9d"/>

        <title>Página não encontrada - {{ config('app.name', 'Laravel Multi Auth Guard') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300italic,300,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="/css/notfound.css" rel="stylesheet" type="text/css">
    </head>
    
    <body>
        <div class="container">
            <img class="line" src="/img/linha-404.gif" alt="">
            <h1>404</h1>
            <h2>A página que você está procurando não existe</h2>
            <p class="text">
                Você pode ter digitado algo errado ou talvez este conteúdo tenha sido movido para outro endereço
            </p>

            <div class="buttons">
                <a href="{{ url('/usuario/login') }}" class="btn btn-empty">Página Inicial</a>
            </div>
        </div>
        
        <img class="logo" src="/img/logo-named.png">
        
        <div class="cloud-group">
            <div class="clouds"></div>
            <div class="base"></div>
        </div>
    </body>
</html>
