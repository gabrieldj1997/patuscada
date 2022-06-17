<div id="sala_espera">
    <h1>Codigo do jogo: {{ $jogo->codigo }}</h1>
    <div id="list_Jogadores">

    </div>
    <br>
    @if (Auth::user()->id == $jogo->id_jogador_criador)
        <button type="button" class="btn btn-primary"
            id="button_start">Iniciar Partida</button>
    @endif
</div>
