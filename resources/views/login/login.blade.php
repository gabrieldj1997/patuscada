<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="grecaptcha-key" content="{{ config('recaptcha.v3.public_key') }}">
    <title>Login Patuscada v.Dj1997</title>

    <script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.v3.public_key') }}"></script>
</head>

<body>
    @if (Auth::check())
        <p>Ola {{ $user->nickname }}</p>
    @else
        <p>Usuario não logado</p>
    @endif
    <a href="{{ url('/login/cadastro') }}">Cadastro</a>
    <a href="{{ url('/login/truncate') }}">Excluir logins</a>
    @if (Auth::check())
    <a href="{{ url('/login/logout') }}">logout</a>
    @else
        <form id="login_form" data-grecaptcha-action="login">
            <input type="text" name="nickname" id="nickname_input" placeholder="Digite seu nickname...">
            <input type="text" name="password" id="password_input" placeholder="Digite sua senha...">
            <button type="submit" id="login_submit">login</button>
        </form>
    @endif
    <script src="{{ url('./js/getLogin.js') }}"></script>
</body>

</html>
