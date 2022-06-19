<div id="tela_central">
    <div id="box_cartas_pretas_leitor" style="display: none;">
        @foreach (json_decode($jogo->cartas_pretas_jogo) as $carta_preta)
            <div style="display: flex;">
                <div class="carta_preta_leitor card text-white bg-dark mb-3" style="max-width: 18rem;"
                    idCartaPreta="{{ $carta_preta }}">
                    <div class="card-header">Patuscada carta_id = {{ $carta_preta }}</div>
                    <div class="card-body">
                        <p class="card-text">
                            {{ App\Models\CartasPretas::find($carta_preta)->texto }}
                        </p>
                    </div>
                </div>
                <div style="display: flex;align-items: center;">
                    <button type="button" class="btn btn-primary button_carta_preta"> Selecionar carta
                        {{ $carta_preta }} </button>
                </div>
            </div>
        @endforeach
    </div>
    <div id="box_cartas_brancas_leitor" style="display: none;">
        <h1>Cartas Brancas Escolhidas</h1>

    </div>
    <div id="box_cartas_pretas" style="display: none;">
        <h1>Carta Preta Escolhida</h1>

    </div>
    <div id="box_cartas_brancas" style="display: none;">
        <h1>Minhas Cartas Brancas</h1>
        @foreach (json_decode($jogadores) as $jogador)
            @if (Auth::user()->id == $jogador->id_jogador)
                @foreach (json_decode($jogador->cartas) as $carta_branca)
                    <div style="display: flex;">
                        <div class="carta_branca card bg-light mb-3" style="max-width: 18rem;"
                            idCartaBranca="{{ $carta_branca }}">
                            <div class="card-header">Patuscada carta_id = {{ $carta_branca }}</div>
                            <div class="card-body">
                                <p class="card-text">
                                    {{ App\Models\CartasBrancas::find($carta_branca)->texto }}
                                </p>
                            </div>
                        </div>
                        <div style="display: none;align-items: center;">
                            <button type="button" class="btn btn-primary button_carta_branca"> Selecionar carta
                                {{ $carta_branca }} </button>
                        </div>
                    </div>
                @endforeach
            @endif
        @endforeach

    </div>
</div>
