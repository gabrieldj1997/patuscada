<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Criar partida</title>
</head>

<body>
    <form id="form_register_game">
        <input type="text" name="cod_game_input" placeholder="Insira o codigo da sala...">
        <input type="text" name="name_game_input" placeholder="Insira o nome da sala...">
        <button type="submit" id="login_submit">Criar sala</button>
    </form>
</body>
<script src="{{ url('./js/registerGame.js') }}"></script>

</html>
