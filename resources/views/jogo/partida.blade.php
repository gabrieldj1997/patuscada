<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="APP_KEY" content="{{ config('broadcasting.connections.pusher.key') }}" />

    <title>Jogo</title>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
</head>

<body>
    @if (isset($jogo))
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
    @else
        <h1>Jogo não encontrado</h1>
    @endif
</body>

<script src="{{ url(mix('js/app.js')) }}"></script>

@if (Auth::user()->id == $jogo->id_usuario_criador)
    {
    <script src="{{ url('./js/jogo/host.js') }}"></script>
    }
@else
    {
    <script src="{{ url(mix('js/jogo/client.js')) }}"></script>
    }
@endif

</html>
