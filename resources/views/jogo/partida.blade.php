<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="APP_KEY" content="{{ config('broadcasting.connections.pusher.key') }}" />

    <title>Jogo</title>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="{{ url(mix('js/app.js')) }}"></script>



    @if (isset($jogo))
        <script>
            var myId = '<?= Auth::user()->id ?>';
            var jogo = '<?= $jogo ?>';
            jogo = JSON.parse(jogo);
        </script>
    @endif
</head>

<body>
    @if (isset($jogo))
        <!--Jogo estado aguardando inicio-->
        @if ($jogo->estado_jogo == 0)
            @include('jogo.salaEspera')

            <!--Jogo estado iniciado-->
        @else
        
            <!--Jogo encerrado-->
        @endif
    @else
        <h1>Jogo não encontrado</h1>
        <button type="button" class="btn btn-primary"
            onclick="window.location='{{ route('login.index') }}'">Login</button>
    @endif
    <button type="button" class="btn btn-primary"
    id="button_test">Teste Message</button>
</body>
<script src="{{ url(mix('js/jogo.js')) }}"></script>
<script src="{{ url(mix('js/jogo/client.js')) }}"></script>
@if (Auth::user()->id == $jogo->id_jogador_criador)
<script src="{{ url('./js/jogo/host.js') }}"></script>
@endif


</html>
