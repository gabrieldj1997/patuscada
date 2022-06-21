<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="APP_KEY" content="{{ config('broadcasting.connections.pusher.key') }}" />

    <title>Jogo</title>

    <link href="{{ url(mix('css/app.css')) }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="{{ url(mix('js/app.js')) }}"></script>
    @if (isset($jogo))
        <script>
            var myId = '<?= Auth::user()->id ?>';
            var estadoJogo = `<?= $jogo->estado_jogo ?>`;
        </script>
        @if ($jogo->estado_jogo != 0)
            <script>
                var jogadorLeitor =
                    '<?= json_decode($jogadores)[($jogo->rodada_jogo - 1) % count(json_decode($jogadores))]->id_jogador ?>';
                var jogadorCriador = '<?= $jogo->id_jogador_criador ?>';
            </script>
        @endif
    @endif
</head>

<body>
    <div id="mensagens">
        @if ($jogo->estado_jogo != 0)
            @if (json_decode($jogadores)[($jogo->rodada_jogo - 1) % count(json_decode($jogadores))]->id_jogador == Auth::user()->id)
                <h1>Escolha uma carta preta</h1>
            @else
                <h1>Aguarde o {{App\Models\User::find(json_decode($jogadores)[($jogo->rodada_jogo - 1) % count(json_decode($jogadores))]->id_jogador)->nickname}} escolher uma carta preta</h1>
            @endif
            @foreach ($jogadores as $jogador)
                <div>{{ App\Models\User::find($jogador->id_jogador)->nickname }} : {{ count(json_decode($jogador->pontuacao)) }}</div>
            @endforeach
            <div>
                <p>Cartas Brancas restantes: {{count(json_decode($jogo->cartas_brancas_monte))}}</p>
            </div>
            <div>
                <p>Cartas Pretas restantes: {{count(json_decode($jogo->cartas_pretas_monte))}}</p>
            </div>
        @endIf
    </div>
    @if (isset($jogo))
        <!--Jogo estado aguardando inicio-->
        @if ($jogo->estado_jogo == 0)
            @include('jogo.salaEspera')

            <!--Jogo estado iniciado-->
        @else
            @include('jogo.telaCentral')
            <!--Jogo encerrado-->
        @endif
    @else
        <h1>Jogo não encontrado</h1>
        <button type="button" class="btn btn-primary"
            onclick="window.location='{{ route('login.index') }}'">Login</button>
    @endif

    @if (Auth::user()->id == $jogo->id_jogador_criador)
        <button type="button" class="btn btn-primary" style="display:none;" id="buttonFinalizarRodada">Finalizar
            rodada</button>
        <input id="inputIdJogo" type="text" style="display: none;"/>
        <input id="inputJogadorGanhador" type="text" style="display: none;"/>
        <input id="inputCartaBrancaDescartada" type="text" style="display: none;"/>
        <input id="inputCartaPretaDescartada" type="text" style="display: none;"/>
        <input id="inputCartaBrancaGanhadora" type="text" style="display: none;"/>
    @endif
    @if ($jogo->estado_jogo != 0)
        @if (json_decode($jogadores)[($jogo->rodada_jogo - 1) % count(json_decode($jogadores))]->id_jogador == Auth::user()->id)
            <button type="button" class="btn btn-primary" id="button_trocar_cartas">Trocar todas cartas brancas</button>
        @endif
    @endif
    <button type="button" class="btn btn-primary"
        onclick="window.location='{{ route('login.index') }}'">Voltar</button>
</body>
@if ($jogo->estado_jogo == 0)
    <script src="{{ url(mix('js/jogo.js')) }}"></script>
@else
    <script src="{{ url(mix('js/jogo/client.js')) }}"></script>
@endif
@if (Auth::user()->id == $jogo->id_jogador_criador)
    <script src="{{ url('./js/jogo/host.js') }}"></script>
@endif

<script>
    var TipoJogador = () => {
        if (myId == jogadorLeitor) {
            return 1;
        } else {
            return 0;
        }
    }
</script>

</html>
