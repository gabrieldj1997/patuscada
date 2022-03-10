<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="grecaptcha-key" content="{{config('recaptcha.v3.public_key')}}">
    <title>Login Patuscada v.Dj1997</title>
</head>
<body>
    <form id="login" data-grecaptcha-action="message">
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
    <script src="./js/app.js"></script>
</body>

</html>