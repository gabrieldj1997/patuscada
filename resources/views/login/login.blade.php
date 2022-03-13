<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="grecaptcha-key" content="{{config('recaptcha.v3.public_key')}}">
    <title>Login Patuscada v.Dj1997</title>

    <script src="https://www.google.com/recaptcha/api.js?render={{config('recaptcha.v3.public_key')}}"></script>
</head>

<body>
    <div>Quantidade de jogadore online = {{ $qtdJogadoresLogados }}</div>
    <a href="{{ url('/login/cadastro') }}">Cadastro</a>
    <a href="{{ url('/login/truncate') }}">Excluir logins</a>
    <form id="login_form" data-grecaptcha-action="login">
        <input type="text" name="username" id="username_input" placeholder="Digite seu login...">
        <input type="text" name="password" id="password_input" placeholder="Digite sua senha...">
        <button type="submit" id="login_submit">login</button>
    </form>

    <script src="{{ url('./js/getLogin.js') }}"></script>
</body>

</html>