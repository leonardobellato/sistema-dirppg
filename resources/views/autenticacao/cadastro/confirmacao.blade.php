<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Candidato</title>
    <link rel="stylesheet" href="{{ asset('css/autenticacao.css') }}">
</head>
<body>
    <main>
        <div class="login-container">
            <img src="{{ asset('./logo_dirppg_preto.png')}}" alt="logo" class="img-logo"/>
            
            <div class="information-container">
                <p>Sua conta foi criada com sucesso!</p>   
                <a href={{ route('autenticacao.login') }}>Entrar na conta</a> 
            <div>
        </div>
    </main>
</body>
</html>