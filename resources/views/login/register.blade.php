<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="grecaptcha-key" content="{{config('recaptcha.v3.public_key')}}">
    
    <title>Cadastro de jogador</title>

    <script src="https://www.google.com/recaptcha/api.js?render={{config('recaptcha.v3.public_key')}}"></script>
</head>
<body>
    <a href="{{ url('/login') }}">voltar</a>
    <form id="cadaster_form" data-grecaptcha-action="registerLogin">
        <input type="text" name="username" id="username_input" placeholder="Digite seu login...">
        <input type="text" name="password" id="password_input" placeholder="Digite sua senha...">
        <input type="text" name="email" id="email_input" placeholder="Digite seu email...">
        <button type="submit" id="cadaster">Cadastrar</button>
    </form>
    <script src="{{ url('./js/login.js') }}"></script>
</body>
</html>