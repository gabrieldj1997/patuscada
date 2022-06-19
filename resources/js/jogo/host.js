//Config
require('../bootstrap');

const { default: axios } = require('axios');

//URL's
const startGame = window.location.origin + '/api/jogoApi/start';
const finishRodada = window.location.origin + '/api/jogoApi/next';

//Variaveis
const jogadores_list = document.querySelector('#list_Jogadores');
const gameId = document.location.pathname.split('/')[2];
const buttonFinalizarRodada = document.querySelector('#buttonFinalizarRodada');
const inputIdJogo = document.querySelector('#inputIdJogo');
const inputJogadorGanhador = document.querySelector('#inputJogadorGanhador');
const inputCartaBrancaDescartada = document.querySelector('#inputCartaBrancaDescartada');
const inputCartaPretaDescartada = document.querySelector('#inputCartaPretaDescartada');

window.Echo.channel('jogo-jogada-' + gameId)
    .listen('.jogadas', (data) => {
        if (data.tp_jogada == 1) {
            inputCartaPretaDescartada.value = data.cartas.id
        } else if (data.tp_jogada == 2) {
            if (inputCartaBrancaDescartada.value == '') {
                inputCartaBrancaDescartada.value = JSON.stringify([])
            }
            let val = JSON.parse(inputCartaBrancaDescartada.value)
            if (val.findIndex((item) => { return item.id === data.jogadorId }) == -1) {
                val.push({ id: data.jogadorId, cartas: [data.cartas.id] })
            } else {
                val[val.findIndex((item) => { return item.id === data.jogadorId })].cartas.push(data.cartas.id)
            }
            inputCartaBrancaDescartada.value = JSON.stringify(val)
        } else if (data.tp_jogada == 3) {
            let jogadorGanhador = {
                id_jogador: data.cartas.id_jogador,
                id_carta_preta: data.cartas.id_carta_preta,
                id_carta_branca: data.cartas.id_carta_branca
            }
            inputIdJogo.value = data.jogoId
            inputJogadorGanhador.value = JSON.stringify(jogadorGanhador)
            buttonFinalizarRodada.style.display = 'block';
        }
    })

if (estadoJogo == 0) {
    var button_start = document.querySelector('#button_start');
    button_start.onclick = () => {
        let jogadores = [];
        let confirmMessage = 'Tem certeza que deseja iniciar o jogo? \n';
        for (i = 0; i < jogadores_list.children.length; i++) {
            confirmMessage += `Jogador ${i + 1}:  ${jogadores_list.children[i].className.replace('jogador-', '')}\n`;
            jogadores.push(parseInt(jogadores_list.children[i].attributes.user_id.value));
        }
        let host_confirm = confirm(confirmMessage);
        if (host_confirm) {
            const options = {
                method: 'POST',
                url: startGame,
                data: {
                    id_jogo: gameId,
                    id_user: myId,
                    jogadores: jogadores
                }
            }

            axios(options);
        }
    }
}
buttonFinalizarRodada.onclick = () => {
    options = {
        method: 'POST',
        url: finishRodada,
        data: {
            id_jogo: inputIdJogo.value,
            cartas_brancas_descartadas: inputCartaBrancaDescartada.value,
            carta_preta_descartada: inputCartaPretaDescartada.value,
            jogador_ganhador: inputJogadorGanhador.value,
            my_id: myId
        }
    }
    axios(options)

}