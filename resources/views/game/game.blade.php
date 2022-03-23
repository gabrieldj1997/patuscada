<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Patuscada</title>
</head>
<body>
</body>
    <h1>{{ Auth::get('nickcname')}}</h1>
    <a href="">Criar sala</a>
    <form>
        <input type="text" name="id_game_input" placeholder="Insira o id da sala...">
        <button id="game_submit">Entrar</button>
    </form>
    <button>Entrar na sala</button>
</body>
<script src="{{ url('./js/app.js') }}"></script>
<script src="{{ url('./js/indexGame.js') }}"></script>
</html>