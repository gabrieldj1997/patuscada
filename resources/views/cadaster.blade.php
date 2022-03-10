<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="grecaptcha-key" content="{{config('recaptcha.v3.public_key')}}">
    <title>Cadastro de jogador</title>
</head>
<body>
    <a href="{{ url('/login') }}">voltar</a>
    <form id="cadaster_form">
        <input type="text" name="username" id="username_input" placeholder="Digite seu login...">
        <input type="text" name="password" id="password_input" placeholder="Digite sua senha...">
        <input type="text" name="email" id="email_input" placeholder="Digite seu email...">
        <button type="submit" id="cadaster">Registrar</button>
    </form>
    <script src="https://www.google.com/recaptcha/api.js?render={{config('recaptcha.v3.public_key')}}"></script>
    <script src="{{ url('./js/register.js') }}"></script>
</body>
</html>