<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Jogo</title>
</head>
<body>
     
    <h1>Jogo {{ $jogo->id }}</h1>
    <h2>{{ $jogo->nome_jogo }}</h2>
    <h2>{{ $jogo->codigo }}</h2>
    <h3 id="estadoJogo">{{ $jogo->id_estado_jogo }}</h3>
    <button class="btn btn-primary" id="btn-iniciar">Iniciar Partida</button>
    <div id="container">
        <div id="aba-1">

        </div>
        <div id="aba-2">

        </div>
        <div id="aba-3">

        </div>
    </div>
    <div id="jogadores">
        
    </div>
</body>
<script src="{{ url('./js/jogo.js') }}"></script>
</html>