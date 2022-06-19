//config
require('../bootstrap');

const { default: axios } = require('axios');

//URL's
const startGame = window.location.origin + '/jogo/start';
const getCartaPreta = window.location.origin + '/api/cartaspretas';
const getCartaBranca = window.location.origin + '/api/cartasbrancas';
const chosseCartaPreta = window.location.origin + `/api/jogoApi/${document.location.pathname.split('/')[2]}/cartapreta`;
const chosseCartaBranca = window.location.origin + `/api/jogoApi/${document.location.pathname.split('/')[2]}/cartabranca`;
const chosseVencedor = window.location.origin + `/api/jogoApi/${document.location.pathname.split('/')[2]}/vencedor`;

//Variaveis
const users_list = document.querySelector('#list_Jogadores');
const gameId = document.location.pathname.split('/')[2];
const box_cartas_brancas_leitor = document.querySelector('#box_cartas_brancas_leitor');
const box_cartas_brancas_jogador = document.querySelector('#box_cartas_brancas');
const box_cartas_pretas_leitor = document.querySelector('#box_cartas_pretas_leitor');
const box_cartas_pretas_jogador = document.querySelector('#box_cartas_pretas');
const botao_cartas_pretas = document.querySelectorAll('.button_carta_preta');
const botao_cartas_brancas = document.querySelectorAll('.button_carta_branca');

window.Echo.channel('jogo-message-' + gameId)
    .listen('.message', (data) => {
        console.log(data)
        MessageTrigger(data)
    });

// window.Echo.channel('jogo-cartas-' + gameId)
//     .listen('.cartas-' + myId, (data) => {
//         console.log(data)
//     })
window.Echo.channel('jogo-jogada-' + gameId)
    .listen('.jogadas', (data) => {
        console.log(data)
        JogadaTrigger(data)
    })

//Function's
function MessageTrigger(message) {
    //primeira classe
    //1 = Partida; 2 = cartas; 3= rodada; 4 = jogador;
    switch (message.data.tp_message[0]) {
        case 1:
            if (message.data.tp_message[1] == 2) {
                window.location.href = window.location.href;
            }
            break;
        case 2:
            if (message.data.tp_message[1] == 1) {
                //loading com mensagem "Embaralhando e distirbuindo as cartas..."
            } else {
                window.location.href = window.location.href;
            }
            break;
        case 3:
            //implementar
            break;
    }
}

async function JogadaTrigger(message) {
    var tpJogador = TipoJogador();
    if (tpJogador == 1) {
        if (message.tp_jogada == 2) {
            box_cartas_brancas_leitor.style.display = 'block'
            var carta = await GeradorCarta(message.cartas.id, 'branca', message.jogadorId);
            box_cartas_brancas_leitor.innerHTML += carta;
            let botao_cartas_brancas_leitor = document.querySelectorAll('.button_carta_branca_leitor');
            if (botao_cartas_brancas_leitor.length > 0) {
                botao_cartas_brancas_leitor.forEach(carta => {
                    carta.addEventListener('click', (event) => {
                        console.log(event.path[1].previousElementSibling.attributes.idCartaBranca.value);
                        let idCartaPreta = document.querySelector('.carta_preta_leitor').attributes.idcartapreta.value
                        let idCartaBranca = event.path[1].previousElementSibling.attributes.idCartaBranca.value;
                        let jogadorGanhador = event.path[1].previousElementSibling.attributes.idjogador.value;
                        let userConfirm = confirm('Escolher carta ' + idCartaBranca + '?');
                        if (userConfirm) {
                            options = {
                                method: 'POST',
                                url: chosseVencedor,
                                data: {
                                    id_carta_preta: idCartaPreta,
                                    id_carta_branca: idCartaBranca,
                                    my_id: myId,
                                    id_jogador_ganhador: jogadorGanhador
                                }
                            }
                            axios(options);
                            botao_cartas_brancas_leitor.forEach(item => {
                                item.remove();
                            })
                        }
                    })
                })
            }
        }else if(message.tp_jogada == 1){
            document.querySelector('#mensagens').innerHTML = `<h1>Aguarde todos escolherem uma carta branca</h1>`
        }
    } else {
        if (message.tp_jogada == 1) {
            box_cartas_pretas_jogador.style.display = 'block'
            var carta = await GeradorCarta(message.cartas.id, 'preta', message.jogadorId);
            box_cartas_pretas_jogador.innerHTML += carta;
            botao_cartas_brancas.forEach(botao => {
                botao.parentElement.style.display = 'flex'
            })
            document.querySelector('#mensagens').innerHTML = `<h1>Escolha uma carta branca</h1>`
        }else if(message.tp_jogada == 3){
            box_cartas_brancas_leitor.style.display = 'block'
            var carta = await GeradorCarta(message.cartas.id_carta_branca, 'branca', jogadorId);
            box_cartas_brancas_leitor.innerHTML += carta;
        }
    }
    if(message.tp_jogada == 3){
        document.querySelector('#mensagens').innerHTML = `<h1>Jogador ${message.cartas.id_jogador} venceu a rodada!</h1>`
    }
}

async function GeradorCarta(id, tipo, idUser) {
    var cartaObj;
    var option = {
        method: 'GET',
        url: ((tipo == 'branca') ? getCartaBranca : getCartaPreta) + '/' + id
    }
    cartaObj = await axios(option);
    cartaObj = cartaObj.data
    var carta = `<div style="display: flex;">`
    carta += (tipo == 'branca') ? `<div class="carta_branca card bg-light mb-3" style="max-width: 18rem;"` : `<div class="carta_preta card text-white bg-dark mb-3" style="max-width: 18rem;"`
    carta += ((tipo == 'branca') ? `idCartaBranca="${cartaObj.id}"` : `idCartaPreta="${cartaObj.id}"`) + ` idJogador="${idUser}">`
    carta += `<div class="card-header">Patuscada carta_id = ${cartaObj.id}</div>`
    carta += `<div class="card-body">`
    carta += `<p class="card-text">`
    carta += `${cartaObj.texto}`
    carta += `</p>`
    carta += `</div>`
    carta += `</div>`
    carta += (tipo == 'preta') ? '</div>' : ` <div style="display: flex;align-items: center;"><button type="button" class="btn btn-primary button_carta_branca_leitor"> Selecionar carta ${cartaObj.id} </button></div></div>`
    return carta;
}