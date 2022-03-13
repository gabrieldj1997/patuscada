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
    <a href="{{ url('/login/cadastro') }}">Cadastro</a>
    <a href="{{ url('/login/truncate') }}">Excluir logins</a>
    <form id="login" data-grecaptcha-action="login">
        <input type="text" name="username" id="username" placeholder="Digite seu login...">
        <input type="text" name="password" id="password" placeholder="Digite sua senha...">
        <button type="submit" id="login">login</button>
        <button type="submit" id="cadaster">cadaster</button>
    </form>

    <script>
        var form = document.getElementById('cadaster');
        form.addEventListener('submit', function(e) {
            console.log(e)
        });
    </script>
    <script src="{{ url('./js/login.js') }}"></script>
</body>

</html>